<?php

namespace App\Http\Controllers;

use App\Models\ProblemFeedback;
use Illuminate\Http\Request;

class ProblemFeedbackController extends Controller
{
    public function create()
    {
        $feedbacks = ProblemFeedback::query()
            ->where('user_id', auth()->id())
            ->latest()
            ->limit(10)
            ->get();

        return view('feedback.create', compact('feedbacks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:5000'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('feedback', 'public');
        }

        ProblemFeedback::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'content' => $validated['content'],
            'image_path' => $path,
            'status' => 'pending',
        ]);

        return redirect()->route('feedback.create')->with('success', '反馈已提交，管理员审核通过后将奖励 1 天 VIP。');
    }
}

