<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('👑 创建超级管理员账号...');
        
        $user = User::updateOrCreate(
            ['email' => '2801359160@qq.com'],
            [
                'name' => '海哥',
                'password' => Hash::make('mqq123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
        
        // 同时创建积分记录
        \App\Models\UserPoint::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 99999, 'total_earned' => 99999, 'total_spent' => 0]
        );
        
        $this->command->info('');
        $this->command->info('✅ 超管账号创建/更新成功！');
        $this->command->info('   用户名：海哥');
        $this->command->info('   邮箱：2801359160@qq.com');
        $this->command->info('   密码：mqq123');
        $this->command->info('   积分：99999');
        $this->command->info('');
    }
}
