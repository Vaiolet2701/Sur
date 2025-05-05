
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Список преподавателей</h1>
    
    <div class="row">
        @foreach($teachers as $teacher)
        <div class="col-md-4 mb-4">
            <div class="card">
                @if($teacher->avatar)
                    <img src="{{ asset('storage/'.$teacher->avatar) }}" class="card-img-top" alt="{{ $teacher->full_name }}">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $teacher->full_name }}</h5>
                    <p class="card-text">
                        Возраст: {{ $teacher->age }} лет<br>
                        Опыт работы: {{ $teacher->work_experience }} лет<br>
                        Проведено курсов: {{ $teacher->taughtCourses->count() }}
                    </p>
                    <a href="{{ route('teachers.show', $teacher->id) }}" class="btn btn-primary">Подробнее</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection