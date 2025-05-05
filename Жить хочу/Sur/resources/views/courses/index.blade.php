@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Форма фильтров -->
    <form action="{{ route('courses.index') }}" method="GET" class="mb-4 filter-form">
        <div class="row">
            <!-- Поля для фильтрации по датам -->
            <div class="col-md-3">
                <label for="start_date">Период с:</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label for="end_date">Период по:</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
    
            <!-- Поля для фильтрации по количеству людей -->
            <div class="col-md-2">
                <label for="min_people">Минимальное количество людей:</label>
                <input type="number" name="min_people" id="min_people" class="form-control" value="{{ request('min_people') }}">
            </div>
            <div class="col-md-2">
                <label for="max_people">Максимальное количество людей:</label>
                <input type="number" name="max_people" id="max_people" class="form-control" value="{{ request('max_people') }}">
            </div>
    
            <!-- Поле для фильтрации по категории -->
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
    
            <!-- Поля для фильтрации по цене -->
            <div class="col-md-3">
                <label for="min_price">Минимальная цена:</label>
                <input type="number" name="min_price" id="min_price" class="form-control" value="{{ request('min_price') }}">
            </div>
            <div class="col-md-3">
                <label for="max_price">Максимальная цена:</label>
                <input type="number" name="max_price" id="max_price" class="form-control" value="{{ request('max_price') }}">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Применить фильтр</button>
                <a href="{{ route('courses.index') }}" class="btn btn-secondary">Сбросить фильтры</a>
            </div>
        </div>
    </form>

    <h1>Курсы</h1>

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