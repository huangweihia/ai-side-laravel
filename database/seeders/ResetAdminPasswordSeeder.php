<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetAdminPasswordSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🔑 重置管理员密码...');
        
        // 生成正确的密码哈希
        $password = Hash::make('mqq123');
        
        $this->command->info("生成的哈希：{$password}");
        
        // 更新或创建管理员账号
        $updated = DB::table('users')
            ->where('email', '2801359160@qq.com')
            ->update([
                'password' => $password,
                'role' => 'admin',
            ]);
        
        if ($updated === 0) {
            // 如果用户不存在，创建
            DB::table('users')->insert([
                'name' => '海哥',
                'email' => '2801359160@qq.com',
                'password' => $password,
                'role' => 'admin',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->command->info('✅ 账号已创建！');
        } else {
            $this->command->info('✅ 密码已重置！');
        }
        
        $this->command->info('');
        $this->command->info('登录信息：');
        $this->command->info('   邮箱：2801359160@qq.com');
        $this->command->info('   密码：mqq123');
        $this->command->info('');
        
        // 验证
        $user = DB::table('users')->where('email', '2801359160@qq.com')->first();
        if ($user) {
            $this->command->info('验证：');
            $this->command->info("   用户 ID: {$user->id}");
            $this->command->info("   用户名：{$user->name}");
            $this->command->info("   角色：{$user->role}");
        }
    }
}
