<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - Школа выживания</title>
    
    <!-- Подключение CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Roboto+Condensed:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/cources.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <!-- Главная навигация -->
    <nav class="nature__nav">
        <div class="nature__container">
            <div class="nav-links">
                <a href="{{ url('/') }}" class="nav-link">
                    <i class="fas fa-home"></i>
                    Главная
                </a>
                <a href="{{ route('courses.index') }}" class="nav-link">
                    <i class="fas fa-book-open"></i>
                    Курсы
                </a>
                    <a  href="{{ route('about') }}" class="nav-link">
                    <i class="fas fa-about"></i>
                    О нас
                </a>
                @auth
                @if(Auth::user()->role === 'admin')
                    <div class="dropdown">
                        <button class="dropdown-toggle nav-link" type="button" id="adminDropdown" 
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-tools"></i>
                            Администрирование
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                            <li><a class="dropdown-item" href="{{ route('admin.enrollments.index') }}">Заявки</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.courses.index') }}">Курсы</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.articles.index') }}">Статьи</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.videos.index') }}">Видео</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.user-articles.index') }}">Статьи пользователей</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.reviews.index') }}">Отзывы</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.teachers.index') }}">Преподаватели</a></li>
                        </ul>
                    </div>
                    @else
                    <a href="{{ route('profile.show') }}" class="nav-link">
                        <i class="fas fa-user"></i>
                        Профиль
                    </a>
                @endif
                @if(auth()->user()->laravel_level === 'Продвинутый')
             
                    <a class="nav-link" href="{{ route('trips.create') }}">Создать поход</a>
               
            @endif
                @if(auth()->user()->is_teacher)
                    <a href="{{ route('teachers.courses.index') }}" class="nav-link">
                        <i class="fas fa-book"></i>
                        Мои курсы
                    </a>
                @endif
            @endauth
        </div>
                

            <!-- Правая часть навигации (авторизация) -->
            <div class="auth-nav">
                @guest
                    <a href="{{ route('login') }}" class="auth-link">
                        <i class="fas fa-sign-in-alt"></i>
                        Вход
                    </a>
                    <a href="{{ route('register') }}" class="auth-link auth-link--primary">
                        <i class="fas fa-user-plus"></i>
                        Регистрация
                    </a>
                @else
                    <div class="dropdown">
                        <button class="dropdown-toggle auth-link" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle"></i>
                            {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user"></i> Профиль</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}" 
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i> Выйти
                                </a>
                            </li>
                        </ul>
                    </div>
                @endguest
            </div>
        </div>
    </nav>

    <!-- Контент для главной страницы -->
    @if (request()->is('/'))
        <div class="nature">
            <div class="nature__image">
                <img src="{{ asset('img/image 39.png') }}" alt="Курсы выживания" class="nature__img">
            </div>
            
            <div class="nature__container">
                <div class="nature__text">
                    <h1 class="nature__title">Научись выживать с нами!</h1>
                    
                    <div class="glowing-images-row">
                        <div class="glowing-image-container">
                            <div class="glowing-image">
                                <img src="{{ asset('img/вода.svg') }}" alt="Добыча воды" class="glowing-image__img">
                            </div>
                            <p class="glowing-image__text">Добыча воды и пищи</p>
                        </div>
                        
                        <div class="glowing-image-container">
                            <div class="glowing-image">
                                <img src="{{ asset('img/укрытия.svg') }}" alt="Постройка укрытий" class="glowing-image__img">
                            </div>
                            <p class="glowing-image__text">Постройка укрытий</p>
                        </div>
                        
                        <div class="glowing-image-container">
                            <div class="glowing-image">
                                <img src="{{ asset('img/помощь.svg') }}" alt="Первая помощь" class="glowing-image__img">
                            </div>
                            <p class="glowing-image__text">Оказание первой помощи</p>
                        </div>
                        
                        <div class="glowing-image-container">
                            <div class="glowing-image">
                                <img src="{{ asset('img/ориентировка.svg') }}" alt="Ориентирование" class="glowing-image__img">
                            </div>
                            <p class="glowing-image__text">Ориентирование без гаджетов</p>
                        </div>
                    </div>
                    
                    <div class="nature__notice">
                        <h3>Снаряжение включено!</h3>
                        <p>Аварийные наборы, GPS-трекеры, спецодежда</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Основное содержимое страницы -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Форма выхода -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Подключение JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    @stack('scripts')
</body>
</html>