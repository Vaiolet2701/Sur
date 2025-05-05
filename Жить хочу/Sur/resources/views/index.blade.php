@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Секция "Почему наши курсы" -->
    <section class="why-us">
        <h2 class="why-us__title">Почему наши курсы лучший выбор</h2>
        <div class="why-us__cards">
            <div class="why-us__card">
                <h3 class="why-us__card-title">Реальные условия</h3>
                <p class="why-us__card-text">Тренировки в 6 климатических зонах</p>
                <div class="why-us__card-info">120+ локаций</div>
            </div>
            <div class="why-us__card">
                <h3 class="why-us__card-title">Безопасность</h3>
                <p class="why-us__card-text">Каждый инструктор имеет:</p>
                <div class="why-us__card-info">Медицинскую лицензию</div>
                <div class="why-us__card-info">10+ лет опыта</div>
            </div>
            <div class="why-us__card">
                <h3 class="why-us__card-title">Форматы обучения</h3>
                <div class="why-us__card-info">3 дня - Экспресс-курс</div>
                <div class="why-us__card-info">14 дней - Проф подготовка</div>
            </div>
        </div>
    </section>

    <section class="promotions">
        <h2 class="promotions__title">Горящие предложения</h2>
        <div class="promotions__cards">
            @foreach($promotions as $promotion)
                <div class="promotions__card">
                    @if($promotion->image_path)
                        <img src="{{ asset($promotion->image_path) }}" class="promotions__card-img" alt="{{ $promotion->title }}">
                    @endif
                    <div class="promotions__card-body">
                        <h5 class="promotions__card-title">{{ $promotion->title }}</h5>
                        <p class="promotions__card-text">{{ $promotion->description }}</p>
                        <div class="promotions__card-footer">
                            <span class="promotions__card-discount">-{{ $promotion->discount }}%</span>
                            <button class="promotions__card-btn" 
                            data-promotion-id="{{ $promotion->id }}" 
                            onclick="loadEnrollForm('{{ $promotion->id }}', 'promotion')">
                        Записаться
                    </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    <div id="enrollModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div id="modalContent"></div> <!-- Сюда будет загружена форма -->
        </div>
    </div>


    <!-- Секция "Программы обучения" -->
    <section class="programs">
        <h2 class="programs__title">Программы обучения</h2>
        <div class="programs__cards">
            <div class="programs__card">
                <h3 class="programs__card-title">Базовый курс</h3>
                <p class="programs__card-text">Тренировки в различных условиях:</p>
                <div class="programs__card-info">Горная местность</div>
                <div class="programs__card-info">Лесная зона</div>
                <div class="programs__card-info">Огневые техники</div>
            </div>
            <div class="programs__card">
                <h3 class="programs__card-title">Экстремальный</h3>
                <p class="programs__card-text">Выживание в экстремальных условиях:</p>
                <div class="programs__card-info">Выживание в снегах</div>
                <div class="programs__card-info">Пустынная адаптация</div>
                <div class="programs__card-info">Водные переправы</div>
            </div>
            <div class="programs__card">
                <h3 class="programs__card-title">Профессиональный</h3>
                <p class="programs__card-text">Продвинутые навыки выживания:</p>
                <div class="programs__card-info">Тактическая медицина</div>
                <div class="programs__card-info">Психология выживания</div>
                <div class="programs__card-info">Навигация Advanced</div>
            </div>
        </div>
    </section>
<!-- Секция "Наши программы" -->
<section class="courses">
    <h2 class="courses__title">Наши программы</h2>
    <div class="courses__slider">
        <div class="swiper-container swiper-container-1">
            <div class="swiper-wrapper">
                @foreach($courses as $course)
                    <div class="swiper-slide">
                        <div class="courses__card">
                            <div class="courses__card-header">
                                <h3 class="courses__card-title">{{ $course->title }}</h3>
                            </div>
                            @if($course->image_path)
                                <img src="{{ asset($course->image_path) }}" 
                                     class="courses__card-img" 
                                     alt="{{ $course->title }}"
                                     style="height: 200px; object-fit: cover;">
                            @endif
                            <div class="courses__card-body">
                                <div class="courses__card-meta">
                                    <span class="courses__card-date">
                                        🗓️ {{ \Carbon\Carbon::parse($course->start_date)->format('d.m') }}-{{ \Carbon\Carbon::parse($course->end_date)->format('d.m') }}
                                    </span>
                                    <span class="courses__card-people">
                                        👥 {{ $course->min_people }}-{{ $course->max_people }} чел
                                    </span>
                                </div>
                               
                                <div class="courses__card-details">
                                    <div class="courses__card-detail">
                                        <span>🐾 Животные:</span>
                                        <strong>{{ $course->animals }}</strong>
                                    </div>
                                    <hr class="courses__card-divider">
                                    <div class="courses__card-detail">
                                        <span>⚡ Сложность:</span>
                                        <div class="courses__card-stars">
                                            @for($i = 0; $i < 5; $i++)
                                                <i class="fas fa-star{{ $i < $course->difficulty_level ? ' text-warning' : ' text-secondary' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="courses__card-detail">
                                          <span>💰 Цена:</span>
                                      <strong>{{ $course->price ? number_format($course->price, 2, '.', ' ') . ' руб.' : 'Бесплатно' }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="courses__card-footer">
                                @auth
                                    <button class="courses__card-btn" 
                                            data-course-id="{{ $course->id }}"
                                            onclick="openEnrollModal('{{ $course->id }}')">
                                        Записаться
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" class="courses__card-btn">
                                        Войти для записи
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Модальное окно -->
<div id="enrollModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="modal-close" onclick="closeModal()">&times;</span>
            <h2>Запись на курс</h2>
        </div>
        <div class="modal-body" id="enrollModalBody">
            Загрузка формы...
        </div>
    </div>
</div>





<!-- Секция "Статьи от админа" (объединенная) -->
<section class="admin-articles">
    <h2 class="admin-articles__title">Статьи</h2>

    <!-- Кнопки создания статей -->
    <div class="admin-articles__actions">
        @auth
            <!-- Кнопка для обычных пользователей -->
            <a href="{{ route('articles.create') }}" class="btn btn-primary">Написать статью</a>
            
            <!-- Кнопка для администратора -->
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">Создать статью (админ)</a>
                <a href="{{ route('admin.user-articles.index') }}" class="btn btn-secondary">Модерация статей</a>
            @endif
        @endauth
    </div>

    <!-- Слайдер -->
    <div class="swiper-container swiper-container-2">
        <div class="swiper-wrapper">
            <!-- Статьи администратора -->
            @forelse($adminArticles as $article)
                <div class="swiper-slide">
                    <div class="admin-articles__item">
                        <div class="article-author">Администратор</div>
                        <h3 class="admin-articles__item-title">{{ $article->title }}</h3>
                        @if($article->image_path)
                            <img src="{{ asset($article->image_path) }}" alt="{{ $article->title }}" class="admin-articles__item-img">
                        @endif
                        <p class="admin-articles__item-content">{{ $article->content }}</p>
                    </div>
                </div>
            @empty
                <div class="swiper-slide">
                    <div class="admin-articles__item">
                        <p>Нет статей от администратора</p>
                    </div>
                </div>
            @endforelse
            
            <!-- Одобренные статьи пользователей -->
            @forelse($userArticles as $article)
                <div class="swiper-slide">
                    <div class="admin-articles__item">
                        <div class="article-author">Пользователь: {{ $article->user->name }}</div>
                        <h3 class="admin-articles__item-title">{{ $article->title }}</h3>
                        @if($article->image_path)
                            <img src="{{ asset($article->image_path) }}" alt="{{ $article->title }}" class="admin-articles__item-img">
                        @endif
                        <p class="admin-articles__item-content">{{ $article->content }}</p>
                    </div>
                </div>
            @empty
                <div class="swiper-slide">
                    <div class="admin-articles__item">
                        <p>Нет одобренных статей от пользователей</p>
                    </div>
                </div>
            @endforelse
        </div>
        <!-- Пагинация -->
        <div class="swiper-pagination-2"></div>
    </div>
</section>

    <!-- Секция "Видео" -->
    <section class="videos">
        <h2 class="videos__title">Видео</h2>
        <div class="videos__list">
            @foreach($videos as $video)
                <div class="videos__item">
                    <h3 class="videos__item-title">{{ $video->title }}</h3>
                    <iframe src="{{ $video->url }}" class="videos__item-iframe" frameborder="0"></iframe>
                    <p class="videos__item-description">{{ $video->description }}</p>
                    <p class="videos__item-duration">Длительность: {{ $video->duration }} минут</p>
                </div>
            @endforeach
        </div>
    </section>

 <!-- Секция "Частые вопросы" -->
 <section class="faq">
    <h2 class="faq__title">Частые вопросы</h2>
    <div class="faq__accordion">
        <div class="faq__item">
            <h3 class="faq__item-title">
                <button class="faq__item-btn" type="button">
                    Что взять с собой?
                </button>
            </h3>
            <div class="faq__item-content">
                Только личные вещи. Все снаряжение предоставляем!
            </div>
        </div>
        <div class="faq__item">
            <h3 class="faq__item-title">
                <button class="faq__item-btn" type="button">
                    Есть возрастные ограничения?
                </button>
            </h3>
            <div class="faq__item-content">
                Участие с 14 лет в сопровождении взрослых
            </div>
        </div>
    </div>
</section>

 <!-- Секция "Карта с курсами" -->
<section class="map py-5">
    <div class="container">
        <h2 class="text-center mb-4">Наши курсы на карте</h2>
        <div id="map" style="width: 100%; height: 500px;"></div>
    </div>
</section>

    <!-- Секция "Оставить отзыв" -->
    <section class="reviews-form">
        <h2 class="reviews-form__title">Оставить отзыв</h2>
        <form action="{{ route('reviews.store') }}" method="POST" class="reviews-form__form">
            @csrf
            <div class="form-group">
                <label for="author_name">Ваше имя:</label>
                <input type="text" name="author_name" id="author_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="content">Текст отзыва:</label>
                <textarea name="content" id="content" class="form-control" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="rating">Рейтинг (от 1 до 5):</label>
                <input type="number" name="rating" id="rating" class="form-control" min="1" max="5" required>
            </div>
            <button type="submit" class="btn btn-primary">Отправить отзыв</button>
        </form>
    </section>

    <!-- Секция "Отзывы" -->
    <section class="reviews">
        <h2 class="reviews__title">Отзывы</h2>
        <div class="reviews__slider">
            <div class="swiper-container swiper-container-1">
                <div class="swiper-wrapper">
                    @foreach($reviews as $review)
                        <div class="swiper-slide">
                            <div class="reviews__card">
                                <div class="reviews__item">
                                    <h3 class="reviews__item-author">{{ $review->author_name }}</h3>
                                    <p class="reviews__item-content">{{ $review->content }}</p>
                                    <p class="reviews__item-rating">Рейтинг: {{ $review->rating }}/5</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    
</div>
@endsection
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Первый слайдер
    const swiper1 = new Swiper('.swiper-container-1', {
        loop: true, // Бесконечный слайдер
        slidesPerView: 2.5, // Видимость слайдов
        centeredSlides: true, // Центрирование активного слайда
        spaceBetween: 20, // Отступ между слайдами
        on: {
            click: function (swiper, event) {
                // Переход к слайду по клику
                const clickedSlide = event.target.closest('.swiper-slide');
                if (clickedSlide) {
                    const slideIndex = Array.from(swiper.slides).indexOf(clickedSlide);
                    swiper.slideTo(slideIndex); // Переход к слайду
                }
            },
        },
    });

    // Второй слайдер
    const swiper2 = new Swiper('.swiper-container-2', {
        loop: true, // Бесконечный слайдер
        slidesPerView: 1, // Видимость одного слайда
        spaceBetween: 20, // Отступ между слайдами
        navigation: false,
        pagination: {
            el: '.swiper-pagination-2', // Пагинация
            clickable: true, // Возможность перехода по клику на пагинацию
        },
    });
});
// Третий слайдер (для статей от пользователей)
const swiper3 = new Swiper('.swiper-container-3', {
    loop: true, // Бесконечный слайдер
    slidesPerView: 1, // Видимость одного слайда
    spaceBetween: 20, // Отступ между слайдами
    navigation: false,
    pagination: {
        el: '.swiper-pagination-3', // Пагинация
        clickable: true, // Возможность перехода по клику на пагинацию
    },
});

// Функция для загрузки формы через AJAX
function loadEnrollForm(id, type) {
    const url = `/courses/${id}/enroll-form`;
    const modalContent = document.getElementById('modalContent');
    
    // Показываем загрузку
    modalContent.innerHTML = '<div class="text-center p-4">Загрузка формы...</div>';
    document.getElementById('enrollModal').style.display = 'block';
    
    fetch(url, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html'
        }
    })
    .then(response => {
        if (!response.ok) {
            if (response.status === 401) {
                window.location.href = '/login';
                throw new Error('Требуется авторизация');
            }
            throw new Error(`Ошибка сервера: ${response.status}`);
        }
        return response.text();
    })
    .then(data => {
        modalContent.innerHTML = data;
        initEnrollForm();
    })
    .catch(error => {
        console.error('Ошибка:', error);
        modalContent.innerHTML = `
            <div class="alert alert-danger">
                <h4>Ошибка!</h4>
                <p>${error.message}</p>
                <button onclick="closeModal()" class="btn btn-secondary">Закрыть</button>
            </div>
        `;
    });
}
</script>
<script>
    // Создаем функцию-обёртку
    function addEventListener2(element, event, handler) {
        element.addEventListener(event, handler);
    }

    // Используем нашу функцию для аккордеона
    document.addEventListener('DOMContentLoaded', function () {
        console.log('Скрипт аккордеона загружен'); // Проверка загрузки скрипта
        const faqButtons = document.querySelectorAll('.faq__item-btn');
        console.log(faqButtons); // Проверка, что кнопки найдены

        if (faqButtons.length === 0) {
            console.error('Элементы .faq__item-btn не найдены');
            return;
        }

        faqButtons.forEach(button => {
            addEventListener2(button, 'click', function () {
                console.log('Кнопка нажата'); // Проверка, что обработчик срабатывает
                const item = this.closest('.faq__item'); // Находим родительский элемент .faq__item
                const content = item.querySelector('.faq__item-content'); // Находим контент внутри .faq__item

                // Закрываем все открытые элементы
                faqButtons.forEach(btn => {
                    const otherItem = btn.closest('.faq__item');
                    const otherContent = otherItem.querySelector('.faq__item-content'); // Исправлено: .faq__item-content
                    if (otherItem !== item) {
                        btn.classList.remove('active');
                        otherContent.classList.remove('open');
                    }
                });

                // Открываем/закрываем текущий элемент
                this.classList.toggle('active');
                content.classList.toggle('open');
            });
        });
    });
</script>
