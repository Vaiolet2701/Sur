@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Список курсов</h1>
        <a href="{{ route('admin.courses.create') }}" class="btn btn-primary mb-3">Создать курс</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Категория</th>
                    <th>Период</th>
                    <th>Изображение</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courses as $course)
                    <tr>
                        <td>{{ $course->title }}</td>
                        <td>{{ $course->category->name }}</td>
                        <td>{{ $course->start_date }} - {{ $course->end_date }}</td>
                        <td>
                            @if($course->image_path)
                                <img src="{{ asset($course->image_path) }}" alt="Course Image" width="50">
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-sm btn-warning">Редактировать</a>
                            <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" class="d-inline">
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