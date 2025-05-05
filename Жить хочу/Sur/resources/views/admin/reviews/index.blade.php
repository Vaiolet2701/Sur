@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Список отзывов</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Автор</th>
                    <th>Содержание</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviews as $review)
                    <tr>
                        <td>{{ $review->author_name }}</td>
                        <td>{{ $review->content }}</td>
                        <td>
                            <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="d-inline">
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