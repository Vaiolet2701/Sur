<!-- Форма для записи по акциям (resources/views/promotions/enroll.blade.php) -->
<form action="{{ route('promotions.enroll', $promotion) }}" method="POST" id="promotionEnrollmentForm">
    @csrf
    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
    <input type="hidden" name="promotion_id" value="{{ $promotion->id }}">

    <div class="form-header">
        <h3>Запись по акции: <strong>{{ $promotion->title }}</strong></h3>
        <p class="text-muted">Скидка: {{ $promotion->discount }}%</p>
    </div>
    
    <div class="form-group">
        <label for="course_id">Выберите курс:</label>
        <select name="course_id" id="course_id" class="form-control" required>
            <option value="">-- Выберите курс --</option>
            @forelse($availableCourses as $availableCourse)
                <option value="{{ $availableCourse->id }}" 
                        data-price="{{ $availableCourse->price }}"
                        data-discount="{{ $promotion->discount }}">
                    {{ $availableCourse->title }}
                    ({{ $availableCourse->start_date->format('d.m.Y') }} - {{ $availableCourse->end_date->format('d.m.Y') }})
                    - 
                    @if($availableCourse->price)
                        {{ number_format($availableCourse->price * (1 - $promotion->discount/100), 2, '.', ' ') }} руб.
                        <small>(скидка {{ $promotion->discount }}%)</small>
                    @else
                        Бесплатно
                    @endif
                </option>
            @empty
                <option value="" disabled>Нет доступных курсов</option>
            @endforelse
        </select>
        @if($availableCourses->isEmpty())
            <div class="alert alert-warning mt-2">В данный момент нет доступных курсов для записи.</div>
        @endif
    </div>
    
    <div class="form-group">
        <label for="name">Ваше имя:</label>
        <input type="text" name="name" id="name" class="form-control" 
               value="{{ auth()->user()->name }}" required>
    </div>
    
    <div class="form-group">
        <label for="email">Ваш email:</label>
        <input type="email" name="email" id="email" class="form-control" 
               value="{{ auth()->user()->email }}" required>
    </div>
    
    <div class="form-group">
        <label for="phone">Ваш номер телефона:</label>
        <input type="tel" name="phone" id="phone" class="form-control" 
               value="{{ auth()->user()->phone ?? '' }}" required>
    </div>
    
    <div class="form-group">
        <label for="age">Ваш возраст:</label>
        <input type="number" name="age" id="age" class="form-control" 
               value="{{ auth()->user()->age ?? '' }}" required min="12" max="100">
    </div>
    
    <div class="form-group">
        <label for="attended_previous_courses">Посещали ли вы подобные курсы?</label>
        <select name="attended_previous_courses" id="attended_previous_courses" class="form-control" required>
            <option value="1">Да</option>
            <option value="0" selected>Нет</option>
        </select>
    </div>
    
    <div class="form-group">
        <label for="message">Дополнительное сообщение:</label>
        <textarea name="message" id="message" class="form-control" rows="3"></textarea>
    </div>
    
    <button type="submit" class="btn btn-primary">Отправить заявку</button>
</form>