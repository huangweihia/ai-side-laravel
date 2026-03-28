<?php

namespace Database\Seeders;

use App\Models\SmtpConfig;
use App\Models\EmailSetting;
use App\Models\EmailTemplate;
use App\Models\EmailSubscription;
use Illuminate\Database\Seeder;

class RestoreAllDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 开始恢复所有数据...');
        
        // 1. SMTP 配置
        $this->command->info('📧 创建 SMTP 配置...');
        $this->createSmtpConfigs();
        
        // 2. 邮件设置
        $this->command->info('⚙️ 创建邮件设置...');
        $this->createEmailSettings();
        
        // 3. 邮件模板
        $this->command->info('📄 创建邮件模板...');
        $this->createEmailTemplates();
        
        // 4. 邮件订阅用户
        $this->command->info('👥 创建邮件订阅用户...');
        $this->createEmailSubscriptions();
        
        $this->command->info('');
        $this->command->info('✅ 所有数据恢复完成！');
        $this->command->info('   - SMTP 配置：' . SmtpConfig::count() . ' 项');
        $this->command->info('   - 邮件设置：' . EmailSetting::count() . ' 项');
        $this->command->info('   - 邮件模板：' . EmailTemplate::count() . ' 个');
        $this->command->info('   - 订阅用户：' . EmailSubscription::count() . ' 个');
    }

    private function createSmtpConfigs(): void
    {
        $configs = [
            [
                'key' => 'smtp_host',
                'value' => 'smtp.qq.com',
                'description' => 'SMTP 服务器地址',
                'is_encrypted' => false,
            ],
            [
                'key' => 'smtp_port',
                'value' => '465',
                'description' => 'SMTP 端口（SSL）',
                'is_encrypted' => false,
            ],
            [
                'key' => 'smtp_encryption',
                'value' => 'ssl',
                'description' => '加密方式',
                'is_encrypted' => false,
            ],
            [
                'key' => 'smtp_username',
                'value' => '2801359160@qq.com',
                'description' => 'SMTP 用户名（QQ 邮箱）',
                'is_encrypted' => false,
            ],
            [
                'key' => 'smtp_password',
                'value' => 'uvxftlhiicvzdffa',
                'description' => 'SMTP 密码/授权码',
                'is_encrypted' => true,
            ],
            [
                'key' => 'smtp_from_address',
                'value' => '2801359160@qq.com',
                'description' => '发件人邮箱地址',
                'is_encrypted' => false,
            ],
            [
                'key' => 'smtp_from_name',
                'value' => 'AI 副业情报局',
                'description' => '发件人名称',
                'is_encrypted' => false,
            ],
        ];

        foreach ($configs as $config) {
            SmtpConfig::updateOrCreate(
                ['key' => $config['key']],
                $config
            );
            $this->command->info("  ✅ {$config['description']}: {$config['value']}");
        }
    }

    private function createEmailSettings(): void
    {
        $settings = [
            [
                'key' => 'email_recipients',
                'value' => json_encode([
                    '2801359160@qq.com',
                    'vip1@example.com',
                    'vip2@example.com',
                ]),
                'description' => '邮件接收人列表',
            ],
            [
                'key' => 'email_send_time',
                'value' => '10:00',
                'description' => '每日邮件发送时间',
            ],
            [
                'key' => 'email_daily_enabled',
                'value' => '1',
                'description' => '是否启用日报',
            ],
            [
                'key' => 'email_weekly_enabled',
                'value' => '1',
                'description' => '是否启用周报',
            ],
        ];

        foreach ($settings as $setting) {
            EmailSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
            $this->command->info("  ✅ {$setting['description']}: {$setting['value']}");
        }
    }

    private function createEmailTemplates(): void
    {
        $templates = [
            [
                'name' => '经典日报',
                'key' => 'daily_digest_classic',
                'subject' => '🤖 AI & 副业资讯日报 - {{date}}',
                'content' => '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>AI 副业资讯日报</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">🤖 AI 副业情报局</h1>
        <p style="color: rgba(255,255,255,0.9); margin: 10px 0 0;">每日资讯日报</p>
    </div>
    
    <div style="background: #f8f9fa; padding: 20px; border-radius: 0 0 10px 10px;">
        <p>你好，{{name}}！</p>
        <p>今天是 <strong>{{date}}</strong>，为你精选了以下内容：</p>
        
        <h2 style="color: #667eea; border-bottom: 2px solid #667eea; padding-bottom: 10px;">🔥 热门 AI 项目</h2>
        {{projects}}
        
        <h2 style="color: #764ba2; border-bottom: 2px solid #764ba2; padding-bottom: 10px;">💡 副业灵感</h2>
        {{side_hustles}}
        
        <h2 style="color: #f093fb; border-bottom: 2px solid #f093fb; padding-bottom: 10px;">📚 学习资源</h2>
        {{resources}}
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; color: #64748b; font-size: 14px;">
            <p>祝你今天愉快！<br>AI 副业情报局团队</p>
            <p style="font-size: 12px; color: #94a3b8;">
                <a href="{{unsubscribe_url}}" style="color: #94a3b8;">退订邮件</a> | 
                <a href="{{preferences_url}}" style="color: #94a3b8;">订阅偏好</a>
            </p>
        </div>
    </div>
</body>
</html>',
                'variables' => ['date', 'name', 'projects', 'side_hustles', 'resources', 'unsubscribe_url', 'preferences_url'],
                'is_active' => true,
            ],
            [
                'name' => '现代日报',
                'key' => 'daily_digest_modern',
                'subject' => '📬 你的 AI 副业日报已送达 - {{date}}',
                'content' => '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, sans-serif; line-height: 1.6; color: #1a202c; max-width: 600px; margin: 0 auto;">
    <div style="background: #fff; padding: 40px 30px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <span style="font-size: 48px;">🚀</span>
            <h1 style="font-size: 28px; margin: 15px 0; color: #2d3748;">AI 副业情报局</h1>
        </div>
        
        <div style="background: #edf2f7; padding: 20px; border-radius: 8px; margin-bottom: 25px;">
            <p style="margin: 0; color: #4a5568;">早上好，{{name}}！</p>
            <p style="margin: 10px 0 0; font-size: 14px; color: #718096;">{{date}} · 第 {{issue_number}} 期</p>
        </div>
        
        <section style="margin-bottom: 30px;">
            <h2 style="font-size: 20px; color: #2d3748; margin-bottom: 15px;"> 今日热点</h2>
            {{projects}}
        </section>
        
        <section style="margin-bottom: 30px;">
            <h2 style="font-size: 20px; color: #2d3748; margin-bottom: 15px;">💰 变现案例</h2>
            {{side_hustles}}
        </section>
        
        <section>
            <h2 style="font-size: 20px; color: #2d3748; margin-bottom: 15px;">📖 推荐资源</h2>
            {{resources}}
        </section>
        
        <div style="margin-top: 40px; padding-top: 20px; border-top: 2px solid #e2e8f0; text-align: center;">
            <p style="color: #718096; font-size: 14px;">
                感谢阅读 · 每天进步一点点
            </p>
            <p style="margin-top: 15px;">
                <a href="{{unsubscribe_url}}" style="color: #a0aec0; font-size: 12px; text-decoration: none;">取消订阅</a>
            </p>
        </div>
    </div>
</body>
</html>',
                'variables' => ['date', 'name', 'projects', 'side_hustles', 'resources', 'issue_number', 'unsubscribe_url'],
                'is_active' => true,
            ],
            [
                'name' => '每周精选',
                'key' => 'weekly_summary',
                'subject' => '📊 本周 AI 副业精选 - {{week}}',
                'content' => '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">📊 每周精选</h1>
        <p style="color: rgba(255,255,255,0.9); margin: 10px 0 0;">AI 副业情报局</p>
    </div>
    
    <div style="background: #f8f9fa; padding: 25px; border-radius: 0 0 10px 10px;">
        <p>你好，{{name}}！</p>
        <p>这是 <strong>{{week}}</strong> 的精选内容汇总：</p>
        
        <h2 style="color: #11998e; border-bottom: 2px solid #11998e; padding-bottom: 10px;">🏆 本周 Top 项目</h2>
        {{top_projects}}
        
        <h2 style="color: #38ef7d; border-bottom: 2px solid #38ef7d; padding-bottom: 10px;">📰 热门文章</h2>
        {{articles}}
        
        <h2 style="color: #0ba360; border-bottom: 2px solid #0ba360; padding-bottom: 10px;">💡 本周洞察</h2>
        <div style="background: white; padding: 15px; border-radius: 8px; margin: 10px 0;">
            {{weekly_insight}}
        </div>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; color: #64748b; font-size: 14px;">
            <p>下周见！<br>AI 副业情报局团队</p>
        </div>
    </div>
</body>
</html>',
                'variables' => ['week', 'name', 'top_projects', 'articles', 'weekly_insight'],
                'is_active' => true,
            ],
            [
                'name' => '欢迎邮件',
                'key' => 'welcome',
                'subject' => '🎉 欢迎加入 AI 副业情报局！',
                'content' => '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px; text-align: center; border-radius: 10px;">
        <h1 style="color: white; margin: 0; font-size: 32px;">🎉 欢迎加入！</h1>
    </div>
    
    <div style="background: #f8f9fa; padding: 30px; border-radius: 10px; margin-top: 20px;">
        <p style="font-size: 18px;">你好，{{name}}！</p>
        
        <p>感谢你加入 <strong>AI 副业情报局</strong>！从今天开始，你将每天收到：</p>
        
        <ul style="line-height: 2;">
            <li>🔥 最新 AI 项目和工具</li>
            <li>💡 真实的副业变现案例</li>
            <li>📚 优质学习资源和教程</li>
            <li>📊 行业动态和趋势分析</li>
        </ul>
        
        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #667eea;">
            <p style="margin: 0; color: #4a5568;">
                <strong>💡 小提示：</strong> 点击邮件底部的"订阅偏好"可以自定义接收内容和频率。
            </p>
        </div>
        
        <p>我们相信，每个人都能找到适合自己的副业方向。</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{dashboard_url}}" style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 25px; font-weight: bold;">前往个人中心 →</a>
        </div>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; color: #64748b; font-size: 14px; text-align: center;">
            <p>祝你副业顺利！<br>AI 副业情报局团队</p>
        </div>
    </div>
</body>
</html>',
                'variables' => ['name', 'email', 'dashboard_url'],
                'is_active' => true,
            ],
            [
                'name' => '系统通知',
                'key' => 'notification',
                'subject' => '🔔 {{notification_title}}',
                'content' => '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: #fff3cd; padding: 20px; border-radius: 10px; border-left: 4px solid #ffc107;">
        <h2 style="color: #856404; margin: 0;">🔔 {{notification_title}}</h2>
    </div>
    
    <div style="background: #f8f9fa; padding: 25px; border-radius: 0 0 10px 10px; margin-top: 0;">
        <p>你好，{{name}}！</p>
        
        <div style="background: white; padding: 20px; border-radius: 8px; margin: 15px 0;">
            {{notification_content}}
        </div>
        
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd; color: #64748b; font-size: 14px;">
            <p>此邮件由系统自动发送，请勿回复。<br>AI 副业情报局</p>
        </div>
    </div>
</body>
</html>',
                'variables' => ['notification_title', 'notification_content', 'name'],
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::updateOrCreate(
                ['key' => $template['key']],
                $template
            );
            $this->command->info("  ✅ {$template['name']}");
        }
    }

    private function createEmailSubscriptions(): void
    {
        $subscriptions = [
            [
                'email' => '2801359160@qq.com',
                'subscribed_to_daily' => true,
                'subscribed_to_weekly' => true,
                'subscribed_to_notifications' => true,
            ],
            [
                'email' => 'vip1@example.com',
                'subscribed_to_daily' => true,
                'subscribed_to_weekly' => true,
                'subscribed_to_notifications' => false,
            ],
            [
                'email' => 'vip2@example.com',
                'subscribed_to_daily' => true,
                'subscribed_to_weekly' => false,
                'subscribed_to_notifications' => true,
            ],
        ];

        foreach ($subscriptions as $sub) {
            EmailSubscription::updateOrCreate(
                ['email' => $sub['email']],
                $sub
            );
            $this->command->info("  ✅ {$sub['email']}");
        }
    }
}
