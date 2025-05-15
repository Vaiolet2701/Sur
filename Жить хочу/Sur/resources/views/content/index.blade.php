@extends('layouts.app')
<link href="{{ asset('css/content.css') }}" rel="stylesheet">

@section('content')
<div class="container all-materials-page">
    <h1>Все материалы</h1>

    <div class="row gy-4">
        <!-- Статьи администратора -->
        <div class="col-12">
            <h2>Статьи администратора</h2>
            <div class="row">
                @forelse($adminArticles as $article)
                    <div class="col-md-6">
                        <div class="card material-card mb-4">
                            <div class="card-body">
                                <h3 class="card-title">{{ $article->title }}</h3>
                                @if($article->image_path)
                                <img src="{{ asset($article->image_path) }}" alt="{{ $article->title }}" class="img-fluid mb-3">
                                @endif
                                <p class="card-text">{{ $article->content }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p>Нет статей от администратора</p>
                @endforelse
            </div>
        </div>

        <!-- Статьи пользователей -->
        <div class="col-12">
            <h2>Статьи пользователей</h2>
            <div class="row">
                @forelse($userArticles as $article)
                    <div class="col-md-6">
                        <div class="card material-card mb-4">
                            <div class="card-body">
                                <h3 class="card-title">{{ $article->title }}</h3>
                                <p class="text-muted">Автор: {{ $article->user->name }}</p>
                                @if($article->image_path)
                                <img src="{{ asset($article->image_path) }}" alt="{{ $article->title }}" class="img-fluid mb-3">
                                @endif
                                <p class="card-text">{{ $article->content }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p>Нет одобренных статей от пользователей</p>
                @endforelse
            </div>
        </div>

        <!-- Видео -->
        <div class="col-12">
            <h2>Видео</h2>
            <div class="row">
                @forelse($videos as $video)
                    <div class="col-md-6">
                        <div class="card material-card mb-4">
                            <div class="card-body">
                                <h3 class="card-title">{{ $video->title }}</h3>
                                <div class="ratio ratio-16x9">
                                    <iframe src="{{ $video->url }}" allowfullscreen></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p>Нет доступных видео</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
