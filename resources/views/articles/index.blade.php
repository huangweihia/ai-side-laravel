@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">文章列表</h1>
    
    @if($articles->count() > 0)
        <div class="space-y-4">
            @foreach($articles as $article)
                <div class="bg-white shadow-md rounded p-6">
                    <h2 class="text-xl font-bold mb-2">{{ $article->title }}</h2>
                    <p class="text-gray-600 mb-4">{{ Str::limit($article->summary, 200) }}</p>
                    <a href="{{ route('articles.show', $article->id) }}" class="text-blue-500 hover:text-blue-700">阅读全文 →</a>
                </div>
            @endforeach
        </div>
        
        <div class="mt-6">
            {{ $articles->links() }}
        </div>
    @else
        <p class="text-gray-600">暂无文章</p>
    @endif
</div>
@endsection
