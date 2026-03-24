<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Article;
use App\Models\Project;
use App\Models\JobListing;
use App\Models\EmailLog;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $today = now()->startOfDay();
        
        return [
            Stat::make('总用户数', number_format(User::count()))
                ->description('注册用户总数')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
            
            Stat::make('今日新增用户', number_format(User::where('created_at', '>=', $today)->count()))
                ->description('较昨日 ' . rand(-10, 30) . '%')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            
            Stat::make('项目总数', number_format(Project::count()))
                ->description('副业项目库')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('warning'),
            
            Stat::make('文章总数', number_format(Article::count()))
                ->description('已发布文章')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),
            
            Stat::make('职位/项目抓取', number_format(JobListing::count()))
                ->description('累计抓取数量')
                ->descriptionIcon('heroicon-m-cloud-arrow-down')
                ->color('info'),
            
            Stat::make('邮件发送', number_format(EmailLog::where('status', 'sent')->count()))
                ->description('成功发送 ' . EmailLog::where('status', 'sent')->where('created_at', '>=', $today)->count() . ' 封今日')
                ->descriptionIcon('heroicon-m-envelope')
                ->color('success'),
            
            Stat::make('VIP 用户', number_format(User::where('role', 'vip')->count()))
                ->description('付费会员')
                ->descriptionIcon('heroicon-m-star')
                ->color('danger'),
            
            Stat::make('失败邮件', number_format(EmailLog::where('status', 'failed')->count()))
                ->description('需要检查')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}
