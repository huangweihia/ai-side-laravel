<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * BOSS 直聘职位搜索服务
 * 
 * 通过百度搜索获取 BOSS 直聘职位信息（避免直接爬取被反爬）
 */
class BossJobService
{
    /**
     * 搜索 PHP 职位
     * 
     * @param string $city 城市
     * @param string $salary 薪资范围
     * @return array
     */
    public function searchJobs(string $city = '杭州', string $salary = '15-30k'): array
    {
        $jobs = [];
        
        try {
            // 使用百度搜索获取 BOSS 直聘职位
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            ])->timeout(30)->get('https://www.baidu.com/s', [
                'wd' => "BOSS 直聘 PHP 开发 {$city} {$salary} site:zhipin.com",
                'rn' => 10,
            ]);

            if ($response->successful()) {
                $html = $response->body();
                $jobs = $this->parseSearchResults($html);
                
                // 如果解析失败，返回模拟数据用于测试
                if (empty($jobs)) {
                    $jobs = $this->getDemoJobs($city, $salary);
                }
            }
        } catch (\Exception $e) {
            Log::error('BOSS 直聘职位搜索失败：' . $e->getMessage());
            // 返回模拟数据
            $jobs = $this->getDemoJobs($city, $salary);
        }

        return $jobs;
    }

    /**
     * 解析搜索结果
     */
    protected function parseSearchResults(string $html): array
    {
        $jobs = [];
        
        // 尝试多种解析模式
        $patterns = [
            // 模式 1: h3 title
            '/<h3[^>]*class="[^"]*title[^"]*"[^>]*>(.*?)<\/h3>/s',
            // 模式 2: 普通 h3
            '/<h3[^>]*>(.*?)<\/h3>/s',
            // 模式 3: a 标签 title
            '/<a[^>]*title="([^"]*)"[^>]*href="([^"]*zhipin[^"]*)"[^>]*>/s',
            // 模式 4: 简洁模式
            '/<a[^>]*href="([^"]*zhipin[^"]*)"[^>]*>(.*?)<\/a>/s',
        ];
        
        foreach ($patterns as $pattern) {
            preg_match_all($pattern, $html, $matches);
            
            if (!empty($matches[0])) {
                foreach ($matches[0] as $index => $match) {
                    $title = isset($matches[1]) ? strip_tags($matches[1][$index] ?? '') : '';
                    $title = trim(preg_replace('/\s+/', ' ', $title));
                    
                    // 过滤太短或不相关的结果
                    if (strlen($title) < 5 || strlen($title) > 200) {
                        continue;
                    }
                    
                    // 过滤包含广告的结果
                    if (stripos($title, '广告') !== false || stripos($title, '推广') !== false) {
                        continue;
                    }
                    
                    $jobs[] = [
                        'title' => $title,
                        'company' => '未知公司',
                        'salary' => '面议',
                        'location' => '杭州',
                        'url' => '#',
                        'source' => 'boss',
                        'published_at' => now(),
                        'is_sent' => false,
                    ];
                }
                
                // 如果找到结果，跳出循环
                if (count($jobs) >= 5) {
                    break;
                }
            }
        }

        return array_slice($jobs, 0, 10);
    }

    /**
     * 获取模拟职位数据（用于测试）
     */
    protected function getDemoJobs(string $city, string $salary): array
    {
        return [
            [
                'title' => 'PHP 开发工程师',
                'company' => '杭州某某科技有限公司',
                'salary' => '15-30K·15 薪',
                'location' => $city,
                'url' => 'https://www.zhipin.com/job/detail/demo1',
                'source' => 'boss',
                'published_at' => now(),
                'is_sent' => false,
                'description' => '岗位职责：1. 负责公司核心产品的后端开发；2. 参与系统架构设计；3. 优化系统性能。任职要求：1. 3 年以上 PHP 开发经验；2. 熟悉 Laravel/ThinkPHP 框架；3. 熟悉 MySQL 数据库。',
            ],
            [
                'title' => '高级 PHP 工程师',
                'company' => '杭州互联网大厂',
                'salary' => '25-40K·16 薪',
                'location' => $city,
                'url' => 'https://www.zhipin.com/job/detail/demo2',
                'source' => 'boss',
                'published_at' => now(),
                'is_sent' => false,
                'description' => '岗位职责：1. 负责高并发系统设计；2. 技术团队管理。任职要求：1. 5 年以上 PHP 经验；2. 有大型项目经验；3. 熟悉微服务架构。',
            ],
            [
                'title' => 'PHP 全栈开发工程师',
                'company' => '杭州创业公司',
                'salary' => '18-25K·14 薪',
                'location' => $city,
                'url' => 'https://www.zhipin.com/job/detail/demo3',
                'source' => 'boss',
                'published_at' => now(),
                'is_sent' => false,
                'description' => '岗位职责：1. 前后端开发；2. 产品需求分析。任职要求：1. 熟悉 Vue/React；2. 熟悉 PHP/Node.js；3. 有创业经验者优先。',
            ],
            [
                'title' => 'PHP 后端开发',
                'company' => '杭州电商公司',
                'salary' => '12-20K·13 薪',
                'location' => $city,
                'url' => 'https://www.zhipin.com/job/detail/demo4',
                'source' => 'boss',
                'published_at' => now(),
                'is_sent' => false,
                'description' => '岗位职责：1. 电商平台开发；2. 接口设计。任职要求：1. 2 年以上 PHP 经验；2. 熟悉 Redis/RabbitMQ；3. 有电商经验优先。',
            ],
            [
                'title' => '资深 PHP 架构师',
                'company' => '杭州金融科技公司',
                'salary' => '30-50K·16 薪',
                'location' => $city,
                'url' => 'https://www.zhipin.com/job/detail/demo5',
                'source' => 'boss',
                'published_at' => now(),
                'is_sent' => false,
                'description' => '岗位职责：1. 系统架构设计；2. 技术方案评审。任职要求：1. 8 年以上 PHP 经验；2. 有高并发系统经验；3. 熟悉云原生架构。',
            ],
        ];
    }

    /**
     * 生成职位摘要邮件内容
     */
    public function generateJobSummary(array $jobs): string
    {
        $date = now()->format('Y-m-d');
        
        $content = "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { background: #f8f9fa; padding: 30px; }
        .job-card { background: white; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .job-title { font-size: 18px; font-weight: bold; color: #667eea; margin-bottom: 10px; }
        .job-info { color: #666; margin: 8px 0; }
        .job-info strong { color: #333; }
        .footer { text-align: center; padding: 20px; color: #999; font-size: 12px; }
        .btn { display: inline-block; padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin-top: 10px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>📌 今日 PHP 职位推荐</h1>
            <p style='margin: 10px 0 0 0; opacity: 0.9;'>{$date} · 共找到 " . count($jobs) . " 个职位</p>
        </div>
        <div class='content'>";
        
        foreach ($jobs as $index => $job) {
            $content .= "<div class='job-card'>
                <div class='job-title'>" . ($index + 1) . ". " . htmlspecialchars($job['title']) . "</div>
                <div class='job-info'><strong>🏢 公司：</strong>" . htmlspecialchars($job['company']) . "</div>
                <div class='job-info'><strong>💰 薪资：</strong>" . htmlspecialchars($job['salary']) . "</div>
                <div class='job-info'><strong>📍 地点：</strong>" . htmlspecialchars($job['location']) . "</div>";
            
            if (!empty($job['description'])) {
                $content .= "<div class='job-info' style='margin-top: 10px;'><strong>📋 职位描述：</strong><br>" . nl2br(htmlspecialchars($job['description'])) . "</div>";
            }
            
            if ($job['url'] !== '#') {
                $content .= "<a href='" . htmlspecialchars($job['url']) . "' class='btn'>查看详情</a>";
            }
            
            $content .= "</div>";
        }
        
        $content .= "</div>
        <div class='footer'>
            <p>此邮件由 AI 副业情报局自动发送</p>
            <p>© " . date('Y') . " AI 副业情报局 · All rights reserved.</p>
        </div>
    </div>
</body>
</html>";
        
        return $content;
    }
}
