<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * 项目列表
     */
    public function index(Request $request)
    {
        $query = Project::query();
        
        // 分类筛选
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        // 搜索
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('full_name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        // 难度筛选
        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }
        
        // 变现能力筛选
        if ($request->filled('monetization')) {
            $query->where('monetization', $request->monetization);
        }
        
        $projects = $query->latest()->paginate(12)->withQueryString();
        
        return view('projects.index', compact('projects'));
    }

    /**
     * 项目详情
     */
    public function show($id)
    {
        $project = Project::findOrFail($id);
        return view('projects.show', compact('project'));
    }
}
