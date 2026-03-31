<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 插入 AI 学习资料库
        DB::table('knowledge_bases')->insert([
            'user_id' => DB::table('users')->where('role', 'admin')->value('id') ?? 1,
            'title' => 'AI 学习资料库',
            'description' => '收集 AI 相关的 PDF 文档、网盘资源、视频教程、电子书等各种学习资料',
            'category' => 'learning_materials',
            'is_public' => true,
            'is_vip_only' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('knowledge_bases')
            ->where('title', 'AI 学习资料库')
            ->delete();
    }
};
