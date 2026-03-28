<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FixAdminPasswordSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🔑 修复管理员密码...');
        
        $password = Hash::make('mqq123');
        
        DB::table('users')->where('email', '2801359160@qq.com')->update([
            'password' => $password,
            'role' => 'admin',
        ]);
        
        $this->command->info('✅ 密码已重置！');
        $this->command->info('   邮箱：2801359160@qq.com');
        $this->command->info('   密码：mqq123');
    }
}
