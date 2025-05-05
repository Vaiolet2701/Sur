@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $course->title }}</h1>
    <div class="row">
        <div class="col-md-8">
            @if($course->image_path)
                <img src="{{ asset($course->image_path) }}" class="img-fluid mb-4" alt="{{ $course->title }}">
            @endif
            <p>{{ $course->description }}</p>
            <ul class="list-group mb-4">
                <li class="list-group-item">
                    <strong>Даты:</strong> 
                    {{ \Carbon\Carbon::parse($course->start_date)->format('d.m.Y') }} - {{ \Carbon\Carbon::parse($course->end_date)->format('d.m.Y') }}
                </li>
                <li class="list-group-item">
                    <strong>Количество участников:</strong> 
                    {{ $course->min_people }} - {{ $course->max_people }} человек
                </li>
                <li class="list-group-item">
                    <strong>Преподаватель:</strong>
                    @if($course->teacher)
                        <div class="d-flex align-items-center mt-2">
                            @if($course->teacher->image_path)
                                <img src="{{ asset($course->teacher->image_path) }}" 
                                    class="rounded-circle me-3" width="50" height="50" 
                                    alt="{{ $course->teacher->name }}">
                            @endif
                            <div>
                                <h5 class="mb-1">{{ $course->teacher->name }}</h5>
                                @if($course->teacher->work_experience)
                                    <small class="text-muted">
                                        Опыт работы: {{ $course->teacher->work_experience }} лет
                                    </small>
                                @endif
                                @if($course->teacher->bio)
                                    <p class="mb-0 mt-2">{{ $course->teacher->bio }}</p>
                                @endif
                            </div>
                        </div>
                    @else
                        <span class="text-muted">Не назначен</span>
                    @endif
                </li>
                <li class="list-group-item">
                    <strong>Животные:</strong> 
                    {{ $course->animals }}
                </li>
                <li class="list-group-item">
                    <strong>Цена:</strong> 
                    {{ $course->price ? number_format($course->price, 2, '.', ' ') . ' руб.' : 'Бесплатно' }}
                </li>
            </ul>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Записаться на курс</h5>
                    <form id="enrollmentForm" action="{{ route('courses.enroll', $course) }}" method="POST">
                        @csrf
                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                        
                        @auth
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                        @endauth

                        <!-- Имя -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Ваше имя *</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="{{ auth()->check() ? auth()->user()->name : '' }}" required>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Ваш email *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ auth()->check() ? auth()->user()->email : '' }}" required>
                        </div>

                        <!-- Телефон -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">Ваш телефон *</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="{{ auth()->check() ? (auth()->user()->phone ?? '') : '' }}" required>
                        </div>

                        <!-- Возраст -->
                        <div class="mb-3">
                            <label for="age" class="form-label">Ваш возраст *</label>
                            <input type="number" class="form-control" id="age" name="age" 
                                   value="{{ auth()->check() ? (auth()->user()->age ?? '') : '' }}" 
                                   min="12" max="100" required>
                        </div>

                        <!-- Посещали ли курсы ранее -->
                        <div class="mb-3">
                            <label class="form-label">Посещали ли вы наши курсы ранее? *</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="attended_previous_courses" id="attended_yes" value="1">
                                    <label class="form-check-label" for="attended_yes">Да</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="attended_previous_courses" id="attended_no" value="0" checked>
                                    <label class="form-check-label" for="attended_no">Нет</label>
                                </div>
                            </div>
                        </div>

                        <!-- Сообщение -->
                        <div class="mb-3">
                            <label for="message" class="form-label">Дополнительная информация</label>
                            <textarea class="form-control" id="message" name="message" rows="3"></textarea>
                        </div>

                        <!-- Кнопка отправки -->
                        <button type="submit" class="btn btn-primary w-100">Записаться</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('enrollmentForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    
    try {
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            form.reset();
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Произошла ошибка при отправке формы');
    }
});
</script>
@endsection