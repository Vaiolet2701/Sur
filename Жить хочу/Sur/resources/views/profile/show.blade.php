@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@section('content')

<div class="container profile-page">
    <div class="profile-section">
        <h1>Профиль пользователя</h1>

        <!-- Форма для обновления данных -->
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Имя:</label>
                        <input type="text" name="name" id="name" class="form-control" 
                               value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" class="form-control" 
                               value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Телефон:</label>
                        <input type="text" name="phone" id="phone" class="form-control" 
                               value="{{ old('phone', $user->phone) }}">
                    </div>

                    <div class="form-group">
                        <label for="address">Адрес:</label>
                        <input type="text" name="address" id="address" class="form-control" 
                               value="{{ old('address', $user->address) }}">
                    </div>
                
                    <div class="form-group">
                        <label>Уровень:</label>
                        <input type="text" class="form-control" 
                               value="{{ ucfirst($user->laravel_level) }}">
                    </div>
               
                    @if($user->canHaveProfileFields())
                        <div class="form-group">
                            <label>Возраст</label>
                            <input type="number" name="age" class="form-control"
                                   value="{{ old('age', $user->age) }}">
                        </div>
                        
                        <div class="form-group">
                            <label>Опыт работы (лет)</label>
                            <input type="number" name="work_experience" class="form-control"
                                   value="{{ old('work_experience', $user->work_experience) }}">
                        </div>
                        
                        <div class="form-group">
                            <label>Биография</label>
                            <textarea name="bio" class="form-control">{{ old('bio', $user->bio) }}</textarea>
                        </div>
                    @endif
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Обновить данные</button>
        </form>

        <!-- Блоки для разных ролей -->
        @if($user->role === 'user')
            <!-- Курсы обычного пользователя -->
            <div class="mt-5">
                <h2>Мои курсы</h2>
                
                <h3>Завершенные курсы</h3>
                @forelse($completedCourses ?? [] as $course)
                    <div class="course-entry mb-3">
                        <h5>{{ $course->title }}</h5>
                        <p>{{ Str::limit($course->description, 100) }}</p>
                        <p class="text-muted">
                            Завершен: {{ \Carbon\Carbon::parse($course->pivot->completed_at)->format('d.m.Y') }}
                        </p>
                    </div>
                @empty
                    <p>Вы еще не завершили ни одного курса.</p>
                @endforelse

                <h3>Курсы в процессе</h3>
                @forelse($coursesInProgress ?? [] as $course)
                    <div class="course-entry mb-3">
                        <h5>{{ $course->title }}</h5>
                        <p>Прогресс: {{ $course->pivot->progress }}%</p>
                        <p class="text-muted">
                            Дата окончания: {{ \Carbon\Carbon::parse($course->end_date)->format('d.m.Y') }}
                        </p>
                    </div>
                @empty
                    <p>У вас нет активных курсов.</p>
                @endforelse

                <!-- Отклоненные заявки на курсы -->
                <div class="mt-4">
                    <h3>Отклоненные заявки на курсы</h3>
                    @forelse($rejectedCourses ?? [] as $course)
                        <div class="rejected-course-entry mb-3">
                            <h5 class="text-danger">{{ $course->title }} (Отклонено)</h5>
                            <p>{{ Str::limit($course->description, 100) }}</p>
                            <p class="text-danger">
                                <strong>Причина отказа:</strong> 
                                {{ $course->pivot->rejection_reason ?? 'Причина не указана' }}
                            </p>
                            <p class="text-muted">
                                Дата подачи: {{ \Carbon\Carbon::parse($course->pivot->created_at)->format('d.m.Y') }}
                            </p>
                            <p class="text-muted">
                                Дата отказа: {{ \Carbon\Carbon::parse($course->pivot->updated_at)->format('d.m.Y') }}
                            </p>
                        </div>
                    @empty
                        <p>У вас нет отклоненных заявок на курсы</p>
                    @endforelse
                </div>
            </div>

            @if($survivalTestResults->isNotEmpty())
            <div class="test-results mt-4">
                <h3>Последние результаты теста на выживание</h3>
                <ul class="list-group">
                    @foreach($survivalTestResults as $result)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">Тест от {{ $result->created_at->format('d.m.Y H:i') }}</h5>
                                <span class="badge bg-{{ $result->percentage >= 70 ? 'success' : ($result->percentage >= 40 ? 'warning' : 'danger') }}">
                                    {{ $result->score }}/{{ $result->total_questions }} ({{ number_format($result->percentage, 1) }}%)
                                </span>
                            </div>
                            <a href="{{ route('survival.result', $result->id) }}" class="btn btn-link">Просмотреть</a>
                        </li>
                    @endforeach
                </ul>
                <div class="mt-3">
                    <a href="{{ route('survival.results') }}" class="btn btn-primary">Все результаты</a>
                    <a href="{{ route('survival.test') }}" class="btn btn-success">Пройти тест снова</a>
                </div>
            </div>
            @endif

            <!-- Мои отзывы -->
            <div class="mt-5">
                <h2>Мои отзывы</h2>
                @forelse($reviews ?? [] as $review)
                    <div class="review-entry mb-3">
                        <h4>{{ $review->title }}</h4>
                        <p>{{ Str::limit($review->content, 150) }}</p>
                        <div class="rating">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= $review->rating ? 'filled' : '' }}">★</span>
                            @endfor
                        </div>
                    </div>
                @empty
                    <p>Вы еще не оставляли отзывов.</p>
                @endforelse
            </div>

            <!-- Мои статьи -->
            <div class="mt-5">
                <h2>Мои статьи</h2>
                @forelse($articles ?? [] as $article)
                    <div class="article-entry mb-3">
                        <h4>{{ $article->title }}</h4>
                        <p>{{ Str::limit($article->content, 150) }}</p>
                        <span class="badge badge-{{ $article->is_approved ? 'success' : 'warning' }}">
                            {{ $article->is_approved ? 'Одобрено' : 'На модерации' }}
                        </span>
                    </div>
                @empty
                    <p>Вы еще не создавали статей.</p>
                @endforelse
            </div>

        @elseif($user->role === 'teacher')
            <!-- Курсы преподавателя -->
            <div class="mt-5">
                <h2>Мои курсы</h2>
                
                <h3>Активные курсы</h3>
                @forelse($activeCourses ?? [] as $course)
                    <div class="course-entry mb-3">
                        <h5>{{ $course->title }}</h5>
                        <p>Студентов: {{ $course->users->count() }}</p>
                        <p class="text-muted">
                            До {{ \Carbon\Carbon::parse($course->end_date)->format('d.m.Y') }}
                        </p>
                        <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-primary">
                           Страница курса
                        </a>
                    </div>
                @empty
                    <p>У вас нет активных курсов.</p>
                @endforelse
            </div>
        @endif
    </div>
</div>

@endsection
