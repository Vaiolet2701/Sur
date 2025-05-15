@extends('layouts.app')



<link href="{{ asset('css/pages.css') }}" rel="stylesheet">
@section('content')
<div class="about-page">

    <div class="bg-light py-5 border-bottom" style="background: linear-gradient(to right, #e1ffe1, #f0fff5);">
        <div class="container text-center">
            <h1 class="display-5 fw-bold text-success">О нашем проекте</h1>
            <p class="lead text-muted">Узнайте больше о наших курсах и правилах сообщества</p>
        </div>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">

                <!-- Курсы -->
                <section class="about-section mb-5">
                    <h2>Курсы выживания</h2>
                    <p>
                        Наш сайт предлагает уникальные курсы по выживанию в различных условиях:
                    </p>
                    <ul>
                        <li>Выживание в дикой природе</li>
                        <li>Подготовка к природным катаклизмам</li>
                        <li>Основы первой медицинской помощи</li>
                        <li>Психологическая подготовка</li>
                    </ul>
                    <p>
                        Все курсы разработаны профессиональными инструкторами с реальным опытом выживания в экстремальных условиях.
                    </p>
                </section>

                <!-- Правила -->
                <section class="about-section mb-5">
                    <h2>Правила сообщества</h2>
                    <ul class="list-group list-group-numbered">
                        <li>Уважайте других участников сообщества</li>
                        <li>Запрещено распространение опасной или ложной информации</li>
                        <li>Контент должен соответствовать тематике выживания</li>
                        <li>Запрещена коммерческая деятельность без согласования</li>
                        <li>Администрация оставляет за собой право удалять любой контент</li>
                    </ul>
                </section>

                <!-- Уровни -->
                <section class="about-section mb-5">
                    <h2>Уровни пользователей</h2>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle text-center">
                            <thead class="table-success">
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
                                    <td>Средний</td>
                                    <td>Модерация контента</td>
                                    <td>После 5 успешных мероприятий</td>
                                </tr>
                                <tr>
                                    <td>Продвинутый</td>
                                    <td>Создание походов, доступ к закрытым курсам</td>
                                    <td>После 8 успешных мероприятий</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-info mt-3">
                        <strong>Ваш текущий уровень:</strong>
                        {{ auth()->user()->laravel_level ?? 'Гость' }}
                    </div>
                </section>

                <!-- Контакты -->
                <section class="about-section">
                    <h2>Контакты</h2>
                    <p><i class="bi bi-envelope me-2"></i> <strong>Email:</strong> info@survival-courses.com</p>
                    <p><i class="bi bi-telephone me-2"></i> <strong>Телефон:</strong> +7 (XXX) XXX-XX-XX</p>
                    <p><i class="bi bi-geo-alt me-2"></i> <strong>Адрес:</strong> г. Москва, ул. Выживальщиков, 15</p>
                </section>

            </div>
        </div>
    </div>
</div>
@endsection
