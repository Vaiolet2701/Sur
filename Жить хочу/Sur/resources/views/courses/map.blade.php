@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map {
        width: 100%;
        height: 500px;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        background: #f0f0f0;
    }
    .leaflet-popup-content-wrapper {
        border-radius: 8px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.15);
    }
    .course-popup {
        font-size: 14px;
        line-height: 1.4;
    }
    .course-popup h5 {
        margin: 0 0 5px;
        font-size: 16px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <h2 class="text-center mb-4">Наши курсы на карте</h2>

    @if($courses->isEmpty())
        <div class="alert alert-warning">
            Нет доступных курсов для отображения
        </div>
    @else
        <div id="map"></div>
    @endif
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof L === 'undefined') {
        console.error('Leaflet не загружен!');
        return;
    }

    // Создаем карту с центром на России
    const map = L.map('map').setView([55.751244, 37.618423], 4);

    // Добавляем слой карты
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    // Массив с курсами из Blade-шаблона
    const courses = @json($courses);

    // Проходим по курсам и добавляем маркеры
    courses.forEach(course => {
        if (!course.latitude || !course.longitude) return;

        const marker = L.marker([parseFloat(course.latitude), parseFloat(course.longitude)]).addTo(map);

        const popupContent = `
            <div class="course-popup">
                <h5>${course.title}</h5>
                <p><strong>Место:</strong> ${course.location_name || 'Не указано'}</p>
                <a href="/courses/${course.id}" class="btn btn-sm btn-primary mt-2">Подробнее</a>
            </div>
        `;

        marker.bindPopup(popupContent);
    });
});
</script>
@endsection
