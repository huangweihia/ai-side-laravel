@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <a href="{{ route('articles.index') }}" class="text-blue-500 hover:text-blue-700">← 返回列表</a>
    
    <div class="bg-white shadow-md rounded p-6 mt-4">
        <h1 class="text-2xl font-bold mb-4">{{ $article->title }}</h1>
        <div class="prose max-w-none">
            {!! $article->content !!}
        </div>
    </div>
</div>
@endsection
