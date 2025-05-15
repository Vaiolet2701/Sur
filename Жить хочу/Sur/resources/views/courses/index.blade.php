@extends('layouts.app')

@section('content')
<div class="container">
<!-- Верхний блок с фильтрами и сортировкой -->
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
    <!-- Кнопка открытия модального окна -->
    <button type="button" class="btn custom-filter-btn" data-bs-toggle="modal" data-bs-target="#filterModal">
        Открыть фильтры
    </button>

    <!-- Сортировка -->
    <form action="{{ route('courses.index') }}" method="GET" class="d-flex align-items-center gap-2">
        @foreach(request()->except('sort') as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
        <select name="sort" id="sort" class="form-select custom-filter-select" onchange="this.form.submit()">
            <option value="">Без сортировки</option>
            <option value="asc" {{ request('sort') === 'asc' ? 'selected' : '' }}>По названию (А-Я)</option>
            <option value="desc" {{ request('sort') === 'desc' ? 'selected' : '' }}>По названию (Я-А)</option>
        </select>
    </form>
</div>


    <!-- Модальное окно -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" style="background-color: #1c2a23; color: #f5f5e9;">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="filterModalLabel">Фильтры курсов</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('courses.index') }}" method="GET" class="filter-form">
                        <div class="row">
                            <!-- Дата -->
                            <div class="col-md-3">
                                <label for="start_date">Период с:</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="end_date">Период по:</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                            </div>

                            <!-- Кол-во людей -->
                            <div class="col-md-2">
                                <label for="min_people">Мин. людей:</label>
                                <input type="number" name="min_people" id="min_people" class="form-control" value="{{ request('min_people') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="max_people">Макс. людей:</label>
                                <input type="number" name="max_people" id="max_people" class="form-control" value="{{ request('max_people') }}">
                            </div>

                            <!-- Категория -->
                            <div class="col-md-2">
                                <label for="category_id">Категория:</label>
                                <select name="category_id" id="category_id" class="form-control">
                                    <option value="">Все категории</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Цена -->
                            <div class="col-md-3">
                                <label for="min_price">Мин. цена:</label>
                                <input type="number" name="min_price" id="min_price" class="form-control" value="{{ request('min_price') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="max_price">Макс. цена:</label>
                                <input type="number" name="max_price" id="max_price" class="form-control" value="{{ request('max_price') }}">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Применить</button>
                                <a href="{{ route('courses.index') }}" class="btn btn-secondary ms-2">Сбросить</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <h1 class="text-light">Курсы</h1>

    <!-- Список курсов -->
    <div class="courses-grid">
        @foreach($courses as $course)
        <a href="{{ route('courses.show', $course->id) }}" class="course-card-link">
            <div class="course-card">
                <h2>{{ $course->title }}</h2>
                <p>{{ $course->description }}</p>
                @if($course->image_path)
                    <img src="{{ asset($course->image_path) }}" alt="{{ $course->title }}">
                @endif
                <p>Период: {{ $course->start_date }} - {{ $course->end_date }}</p>
                <p>Количество людей: {{ $course->min_people }} - {{ $course->max_people }}</p>
                <p>Животные: {{ $course->animals }}</p>
                <p>Цена: {{ $course->price ? number_format($course->price, 2, '.', ' ') . ' ₽' : 'Не указано' }}</p>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endsection
