<form action="{{ route('courses.enroll', $course) }}" method="POST" id="enrollmentForm">
    @csrf
    <input type="hidden" name="user_id" value="{{ auth()->id() }}">

    <div class="form-header">
        <h3>Запись на курс</h3>
    </div>
    
    <div class="form-group">
        <label for="course_id">Выберите курс:</label>
        <select name="course_id" id="course_id" class="form-control" required>
            @foreach($availableCourses as $availableCourse)
                <option value="{{ $availableCourse->id }}" 
                    {{ $availableCourse->id == $course->id ? 'selected' : '' }}>
                    {{ $availableCourse->title }}
                    ({{ $availableCourse->start_date->format('d.m.Y') }} - {{ $availableCourse->end_date->format('d.m.Y') }})
                </option>
            @endforeach
        </select>
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