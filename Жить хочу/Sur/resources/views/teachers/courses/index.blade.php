
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Мои курсы</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <h4>Выбранные курсы</h4>
                    @if($selectedCourses->isEmpty())
                        <div class="alert alert-info">Вы пока не выбрали ни одного курса</div>
                    @else
                        <ul class="list-group mb-4">
                            @foreach($selectedCourses as $course)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $course->title }}
                                    <form action="{{ route('teachers.courses.destroy', $course->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <hr>

                    <h4>Доступные курсы</h4>
                    @if($availableCourses->isEmpty())
                        <div class="alert alert-info">Нет доступных курсов для выбора</div>
                    @else
                        <ul class="list-group">
                            @foreach($availableCourses as $course)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $course->title }}
                                    <form action="{{ route('teachers.courses.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                                        <button type="submit" class="btn btn-sm btn-primary">Выбрать</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection