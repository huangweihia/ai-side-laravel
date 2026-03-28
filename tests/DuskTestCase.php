<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Support\Collection;
use Laravel\Dusk\TestCase as BaseTestCase;
use PHPUnit\Framework\Attributes\BeforeClass;

abstract class DuskTestCase extends BaseTestCase
{
    private static function isTcpPortOpen(string $host, int $port, float $timeoutSeconds = 1.0): bool
    {
        $errno = null;
        $errstr = null;

        $fp = @fsockopen($host, $port, $errno, $errstr, $timeoutSeconds);
        if (is_resource($fp)) {
            fclose($fp);
            return true;
        }

        return false;
    }

    /**
     * Prepare for Dusk test execution.
     */
    #[BeforeClass]
    public static function prepare(): void
    {
        $driverUrl = $_ENV['DUSK_DRIVER_URL'] ?? env('DUSK_DRIVER_URL') ?? 'http://localhost:9515';

        $host = parse_url($driverUrl, PHP_URL_HOST) ?: 'localhost';
        $port = (int) (parse_url($driverUrl, PHP_URL_PORT) ?: 9515);

        // 如果外部 chromedriver 已经在宿主机/其它机器上启动（并且容器能连通），
        // 那么我们不要求容器内必须安装 Chrome/Chromium（chromedriver 会在它所在机器上找浏览器）。
        if (! static::isTcpPortOpen($host, $port)) {
            // 容器内没有可用远程 chromedriver，则尝试本地启动 chrome driver。
            // 但前提是容器内确实具备 Chrome/Chromium 可执行文件。
            $chromeCandidatePaths = [
                '/usr/bin/google-chrome',
                '/usr/bin/google-chrome-stable',
                '/usr/bin/chromium',
                '/usr/bin/chromium-browser',
                '/bin/google-chrome',
                '/bin/chromium',
            ];

            $hasChrome = false;
            foreach ($chromeCandidatePaths as $path) {
                if (is_file($path) && is_executable($path)) {
                    $hasChrome = true;
                    break;
                }
            }

            if (! $hasChrome) {
                throw new \PHPUnit\Framework\SkippedTestSuiteError('当前容器内无 Chrome/Chromium 且远程 DUSK_DRIVER_URL 不可达，跳过 Dusk 浏览器用例。');
            }

            static::startChromeDriver(['--port=' . $port]);

            // Chromedriver 启动需要一点时间；等待端口可连并确认进程仍在运行。
            $deadline = microtime(true) + 8;
            while (microtime(true) < $deadline) {
                if (static::isTcpPortOpen($host, $port)) {
                    return;
                }

                if (isset(static::$chromeProcess) && static::$chromeProcess && ! static::$chromeProcess->isRunning()) {
                    break;
                }

                usleep(200000); // 0.2s
            }

            $exitCode = isset(static::$chromeProcess) && static::$chromeProcess
                ? static::$chromeProcess->getExitCode()
                : null;

            throw new \RuntimeException(
                "ChromeDriver 未能在 {$host}:{$port} 启动成功（exitCode=" . ($exitCode ?? 'null') . "）。"
            );
        }
    }

    /**
     * Create the RemoteWebDriver instance.
     */
    protected function driver(): RemoteWebDriver
    {
        $options = (new ChromeOptions)->addArguments(collect([
            $this->shouldStartMaximized() ? '--start-maximized' : '--window-size=1920,1080',
            '--disable-search-engine-choice-screen',
            '--disable-smooth-scrolling',
        ])->unless($this->hasHeadlessDisabled(), function (Collection $items) {
            return $items->merge([
                '--disable-gpu',
                '--headless=new',
            ]);
        })->all());

        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? env('DUSK_DRIVER_URL') ?? 'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
    }

    /**
     * Dusk 在运行时需要一个“容器内可访问”的 base URL。
     * 我们优先使用外部注入的 DUSK_BASE_URL（例如 host.docker.internal），
     * 避免容器内访问不了 localhost:8081 这类地址导致访问错站点。
     */
    protected function baseUrl(): string
    {
        $base = $_ENV['DUSK_BASE_URL']
            ?? env('DUSK_BASE_URL')
            ?? config('app.url');

        return rtrim($base, '/');
    }
}
