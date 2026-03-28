<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    /**
     * 为了避免 Dusk/Laravel 测试基类初始化不完整导致 Facade root 未设置，
     * createApplication 需要与项目已有 `tests/CreatesApplication.php` 一致，
     * 即执行 `$app->make(Kernel::class)->bootstrap()`。
     */
    public function createApplication(): Application
    {
        $app = require __DIR__ . '/../../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * A basic browser test example.
     */
    public function test_basic_example(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('每天 10 分钟，发现 AI 副业机会');
        });
    }
}
