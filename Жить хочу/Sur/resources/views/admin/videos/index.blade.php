@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Список видео</h1>
        <a href="{{ route('admin.videos.create') }}" class="btn btn-primary mb-3">Добавить видео</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Ссылка</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($videos as $video)
                    <tr>
                        <td>{{ $video->title }}</td>
                        <td><a href="{{ $video->url }}" target="_blank">{{ $video->url }}</a></td>
                        <td>
                            <a href="{{ route('admin.videos.edit', $video->id) }}" class="btn btn-sm btn-warning">Редактировать</a>
                            <form action="{{ route('admin.videos.destroy', $video->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection