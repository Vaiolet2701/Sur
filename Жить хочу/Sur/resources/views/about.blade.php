@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="text-center mb-4">О нашем проекте</h1>
            
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="card-title">Курсы выживания</h2>
                    <p class="card-text">
                        Наш сайт предлагает уникальные курсы по выживанию в различных условиях:
                    </p>
                    <ul>
                        <li>Выживание в дикой природе</li>
                        <li>Городское выживание в экстремальных ситуациях</li>
                        <li>Подготовка к природным катаклизмам</li>
                        <li>Основы первой медицинской помощи</li>
                        <li>Психологическая подготовка</li>
                    </ul>
                    <p>
                        Все курсы разработаны профессиональными инструкторами с реальным опытом выживания в экстремальных условиях.
                    </p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="card-title">Правила сообщества</h2>
                    <ol class="list-group list-group-numbered">
                        <li class="list-group-item">Уважайте других участников сообщества</li>
                        <li class="list-group-item">Запрещено распространение опасной или ложной информации</li>
                        <li class="list-group-item">Контент должен соответствовать тематике выживания</li>
                        <li class="list-group-item">Запрещена коммерческая деятельность без согласования</li>
                        <li class="list-group-item">Администрация оставляет за собой право удалять любой контент</li>
                    </ol>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="card-title">Уровни пользователей Laravel</h2>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Уровень</th>
                                    <th>Возможности</th>
                                    <th>Как получить</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Новичок</td>
                                    <td>Просмотр материалов, комментирование</td>
                                    <td>Автоматически после регистрации</td>
                                </tr>
                                <tr>
                                    <td>Продвинутый</td>
                                    <td>Создание походов, доступ к закрытым курсам</td>
                                    <td>Активация после 5 успешных мероприятий</td>
                                </tr>
                                <tr>
                                    <td>Эксперт</td>
                                    <td>Создание курсов, модерация контента</td>
                                    <td>Назначение администрацией</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-info mt-3">
                        <strong>Ваш текущий уровень:</strong> 
                        {{ auth()->user()->laravel_level ?? 'Гость' }}
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">Контакты</h2>
                    <p>
                        <i class="bi bi-envelope me-2"></i> Email: info@survival-courses.com
                    </p>
                    <p>
                        <i class="bi bi-telephone me-2"></i> Телефон: +7 (XXX) XXX-XX-XX
                    </p>
                    <p>
                        <i class="bi bi-geo-alt me-2"></i> Адрес: г. Москва, ул. Выживальщиков, 15
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection