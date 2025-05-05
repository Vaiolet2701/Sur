@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    @if($teacher->image_path)
                        <img src="{{ asset($teacher->image_path) }}" 
                             class="rounded-circle mb-3" width="150" height="150"
                             alt="{{ $teacher->name }}">
                    @endif
                    <h2>{{ $teacher->name }}</h2>
                    @if($teacher->work_experience)
                        <p class="text-muted">
                            Опыт работы: {{ $teacher->work_experience }} лет
                        </p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">О преподавателе</h3>
                    @if($teacher->bio)
                        <p class="card-text">{{ $teacher->bio }}</p>
                    @else
                        <p class="text-muted">Информация о преподавателе отсутствует</p>
                    @endif
                    
                    <hr>
                    
                    <h4>Курсы преподавателя</h4>
                    @if($teacher->taughtCourses->count())
                        <div class="list-group">
                            @foreach($teacher->taughtCourses as $course)
                                <a href="{{ route('courses.show', $course->id) }}" 
                                   class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $course->title }}</h5>
                                        <small>{{ $course->start_date->format('d.m.Y') }} - {{ $course->end_date->format('d.m.Y') }}</small>
                                    </div>
                                    <p class="mb-1">{{ Str::limit($course->description, 100) }}</p>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">У преподавателя пока нет курсов</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection