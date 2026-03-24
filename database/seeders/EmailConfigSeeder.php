<?php

namespace Database\Seeders;

use App\Models\SmtpConfig;
use App\Models\EmailSetting;
use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 初始化 SMTP 配置
        $smtpConfigs = [
            ['key' => 'smtp_host', 'value' => 'smtp.qq.com', 'description' => 'SMTP 服务器', 'is_encrypted' => false],
            ['key' => 'smtp_port', 'value' => '465', 'description' => 'SMTP 端口', 'is_encrypted' => false],
            ['key' => 'smtp_encryption', 'value' => 'ssl', 'description' => '加密方式', 'is_encrypted' => false],
            ['key' => 'smtp_username', 'value' => '2801359160@qq.com', 'description' => 'SMTP 用户名', 'is_encrypted' => false],
            ['key' => 'smtp_password', 'value' => 'uvxftlhiicvzdffa', 'description' => 'SMTP 密码/授权码', 'is_encrypted' => true],
            ['key' => 'smtp_from_address', 'value' => '2801359160@qq.com', 'description' => '发件邮箱', 'is_encrypted' => false],
            ['key' => 'smtp_from_name', 'value' => 'AI 副业情报局', 'description' => '发件人名称', 'is_encrypted' => false],
        ];

        foreach ($smtpConfigs as $config) {
            SmtpConfig::updateOrCreate(
                ['key' => $config['key']],
                $config
            );
        }

        // 初始化邮件配置
        $emailSettings = [
            ['key' => 'email_recipients', 'value' => json_encode(['2801359160@qq.com']), 'description' => '邮件接收人列表'],
            ['key' => 'email_send_time', 'value' => '10:00', 'description' => '邮件发送时间'],
        ];

        foreach ($emailSettings as $setting) {
            EmailSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        // 初始化邮件模板
        $templates = [
            [
                'name' => '每日资讯日报',
                'key' => 'daily_digest',
                'subject' => '🤖 AI & 副业资讯日报 - {{date}}',
                'content' => '<h2>📅 每日资讯日报</h2><p>你好，{{name}}！</p><p>今天是 {{date}}，为你精选了以下内容：</p><h3>🔥 热门 AI 项目</h3>{{projects}}<h3>💡 副业灵感</h3>{{side_hustles}}<h3>📚 学习资源</h3>{{resources}}<p style="margin-top: 30px; color: #64748b; font-size: 14px;">祝你今天愉快！<br>AI 副业情报局团队</p>',
                'variables' => ['date', 'name', 'projects', 'side_hustles', 'resources'],
                'is_active' => true,
            ],
            [
                'name' => '每周精选汇总',
                'key' => 'weekly_summary',
                'subject' => '📊 本周 AI 副业精选 - {{week}}',
                'content' => '<h2>📊 每周精选汇总</h2><p>你好，{{name}}！</p><p>这是 {{week}} 的精选内容汇总：</p><h3>🏆 Top 项目</h3>{{top_projects}}<h3>📰 热门文章</h3>{{articles}}<p style="margin-top: 30px; color: #64748b; font-size: 14px;">下周见！<br>AI 副业情报局团队</p>',
                'variables' => ['week', 'name', 'top_projects', 'articles'],
                'is_active' => true,
            ],
            [
                'name' => '欢迎邮件',
                'key' => 'welcome',
                'subject' => '🎉 欢迎加入 AI 副业情报局！',
                'content' => '<h2>🎉 欢迎加入！</h2><p>你好，{{name}}！</p><p>感谢你加入 AI 副业情报局！从今天开始，你将每天收到精心挑选的 AI 项目、副业灵感和学习资源。</p><p>我们相信，每个人都能找到适合自己的副业方向。</p><p style="margin-top: 30px;">祝你副业顺利！<br>AI 副业情报局团队</p>',
                'variables' => ['name', 'email'],
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::updateOrCreate(
                ['key' => $template['key']],
                $template
            );
        }

        $this->command->info('✅ 邮件配置初始化完成！');
        $this->command->info('   - SMTP 配置：7 项');
        $this->command->info('   - 邮件设置：2 项');
        $this->command->info('   - 邮件模板：3 个');
    }
}
