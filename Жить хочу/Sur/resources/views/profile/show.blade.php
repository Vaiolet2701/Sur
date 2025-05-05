@extends('layouts.app')

@section('content')
    <div class="container">
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
                </div>
                <div class="col-md-6">
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
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">{{ $course->title }}</h5>
                            <p class="card-text">{{ Str::limit($course->description, 100) }}</p>
                            <p class="text-muted">
                                Завершен: {{ \Carbon\Carbon::parse($course->pivot->completed_at)->format('d.m.Y') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p>Вы еще не завершили ни одного курса.</p>
                @endforelse

                <h3>Курсы в процессе</h3>
                @forelse($coursesInProgress ?? [] as $course)
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">{{ $course->title }}</h5>
                            <p class="card-text">Прогресс: {{ $course->pivot->progress }}%</p>
                            <p class="text-muted">
                                Дата окончания: {{ \Carbon\Carbon::parse($course->end_date)->format('d.m.Y') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p>У вас нет активных курсов.</p>
                @endforelse

                <!-- Отклоненные заявки на курсы -->
                <div class="mt-4">
                    <h3>Отклоненные заявки на курсы</h3>
                    @forelse($rejectedCourses ?? [] as $course)
                        <div class="card mb-3 border-danger">
                            <div class="card-header bg-danger text-white">
                                {{ $course->title }} (Отклонено)
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p class="card-text">{{ Str::limit($course->description, 100) }}</p>
                                        <p class="text-danger">
                                            <strong>Причина отказа:</strong> 
                                            {{ $course->pivot->rejection_reason ?? 'Причина не указана' }}
                                        </p>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <p class="text-muted">
                                            Дата подачи: {{ \Carbon\Carbon::parse($course->pivot->created_at)->format('d.m.Y') }}
                                        </p>
                                        <p class="text-muted">
                                            Дата отказа: {{ \Carbon\Carbon::parse($course->pivot->updated_at)->format('d.m.Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>У вас нет отклоненных заявок на курсы</p>
                    @endforelse
                </div>
            </div>

            <!-- Отзывы и статьи -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <h3>Мои отзывы</h3>
                    @forelse($reviews ?? [] as $review)
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">{{ $review->title }}</h5>
                                <p class="card-text">{{ Str::limit($review->content, 100) }}</p>
                                <div class="rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="star {{ $i <= $review->rating ? 'filled' : '' }}">★</span>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>Вы еще не оставляли отзывов.</p>
                    @endforelse
                </div>

                <div class="col-md-6">
                    <h3>Мои статьи</h3>
                    @forelse($articles ?? [] as $article)
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">{{ $article->title }}</h5>
                                <p class="card-text">{{ Str::limit($article->content, 100) }}</p>
                                <span class="badge badge-{{ $article->is_approved ? 'success' : 'warning' }}">
                                    {{ $article->is_approved ? 'Одобрено' : 'На модерации' }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p>Вы еще не создавали статей.</p>
                    @endforelse
                </div>
            </div>

        @elseif($user->role === 'teacher')
            <!-- Курсы преподавателя -->
            <div class="mt-5">
                <h2>Мои курсы</h2>
                
                <h3>Активные курсы</h3>
                @forelse($activeCourses ?? [] as $course)
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">{{ $course->title }}</h5>
                            <p class="card-text">Студентов: {{ $course->users->count() }}</p>
                            <p class="text-muted">
                                До {{ \Carbon\Carbon::parse($course->end_date)->format('d.m.Y') }}
                            </p>
                            <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-primary">
                               Страница курса
                            </a>
                        </div>
                    </div>
                @empty
                    <p>У вас нет активных курсов.</p>
                @endforelse
            </div>
        @endif
    </div>
@endsection