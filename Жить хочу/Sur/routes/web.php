<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\CourseCategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\UserArticleController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\EquipmentRentalController;
// Admin controllers
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\UserArticleController as AdminUserArticleController;
use App\Http\Controllers\Admin\VideoController as AdminVideoController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\CourseCategoryController as AdminCourseCategoryController;
use App\Http\Controllers\Admin\EnrollmentController as AdminEnrollmentController;
use App\Http\Controllers\Admin\TeacherController as AdminTeacherController;
use App\Http\Controllers\Admin\BanController as AdminBanController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\EquipmentController as AdminEquipmentController;

use App\Http\Controllers\Admin\EquipmentRentalController as AdminEquipmentRentalController;

// Teacher controllers
use App\Http\Controllers\Teacher\CourseSelectionController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\SurvivalTestController;

// Главная страница (доступна всем)
Route::get('/', [PromotionController::class, 'index'])->name('home');
Route::get('/about', function () {
    return view('about');
})->name('about');
Route::get('/courses/map', [CourseController::class, 'showCoursesOnMap'])->name('courses.map');

Auth::routes();
// ==================== АДМИН МАРШРУТЫ ====================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // Управление зачислениями
    Route::get('/enrollments', [AdminEnrollmentController::class, 'index'])->name('admin.enrollments.index');
    Route::post('/admin/enrollments/{enrollmentId}/approve', [AdminEnrollmentController::class, 'approve'])->name('admin.enrollments.approve');
    Route::get('/enrollments/{enrollment}/reject-form', [AdminEnrollmentController::class, 'rejectForm'])->name('admin.enrollments.reject-form');
    Route::post('/enrollments/reject', [AdminEnrollmentController::class, 'reject'])->name('admin.enrollments.reject');
 // Управление преподавателями
    Route::get('/teachers', [AdminTeacherController::class, 'index'])->name('admin.teachers.index');
    Route::get('/teachers/create', [AdminTeacherController::class, 'create'])->name('admin.teachers.create');
    Route::post('/teachers', [AdminTeacherController::class, 'store'])->name('admin.teachers.store');
    Route::delete('teachers/{id}', [AdminTeacherController::class, 'destroy'])->name('admin.teachers.destroy');

    // Управление статьями
    Route::resource('articles', AdminArticleController::class)->names([
        'index' => 'admin.articles.index',
        'create' => 'admin.articles.create',
        'store' => 'admin.articles.store',
        'show' => 'admin.articles.show',
        'edit' => 'admin.articles.edit',
        'update' => 'admin.articles.update',
        'destroy' => 'admin.articles.destroy',
    ]);

    // Управление пользовательскими статьями
    Route::get('user-articles', [AdminUserArticleController::class, 'index'])->name('admin.user-articles.index');
    Route::put('admin/user-articles/{article}/approve', [AdminUserArticleController::class, 'approve'])
    ->name('admin.user-articles.approve');

// Для удаления статьи (используем DELETE)
Route::delete('admin/user-articles/{article}', [AdminUserArticleController::class, 'destroy'])
    ->name('admin.user-articles.destroy');

    // Управление отзывами
    Route::get('reviews', [AdminReviewController::class, 'index'])->name('admin.reviews.index');
    Route::delete('reviews/{id}', [AdminReviewController::class, 'destroy'])->name('admin.reviews.destroy');

    // Управление видео
    Route::resource('videos', AdminVideoController::class)->names([
        'index' => 'admin.videos.index',
        'create' => 'admin.videos.create',
        'store' => 'admin.videos.store',
        'show' => 'admin.videos.show',
        'edit' => 'admin.videos.edit',
        'update' => 'admin.videos.update',
        'destroy' => 'admin.videos.destroy',
    ]);

    // Управление курсами
    Route::resource('courses', AdminCourseController::class)->names([
        'index' => 'admin.courses.index',
        'create' => 'admin.courses.create',
        'store' => 'admin.courses.store',
        'show' => 'admin.courses.show',
        'edit' => 'admin.courses.edit',
        'update' => 'admin.courses.update',
        'destroy' => 'admin.courses.destroy',
    ]);
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::resource('bans', AdminBanController::class)
    ->except(['edit', 'update'])
    ->names('admin.bans');
    Route::delete('/bans/{ban}', [AdminBanController::class, 'destroy'])->name('admin.bans.destroy');
    Route::prefix('admin')->group(function() {
        // Маршрут для формы создания бана
        Route::get('/users/{user}/ban/create', [AdminBanController::class, 'create'])
            ->name('admin.bans.create');
        
        // Маршрут для сохранения бана
        Route::post('/users/{user}/ban', [AdminBanController::class, 'store'])
            ->name('admin.bans.store');
    });
    Route::get('/admin/equipment', [adminEquipmentController::class, 'index'])->name('admin.equipment.index');
    Route::get('/admin/equipment/create', [AdminEquipmentController::class, 'create'])->name('admin.equipment.create');
    Route::post('/admin/equipment', [AdminEquipmentController::class, 'store'])->name('admin.equipment.store');

    Route::get('/admin/rentals', [AdminEquipmentRentalController::class, 'index'])->name('admin.rentals.index');
    Route::post('/admin/rentals/{id}/approve', [AdminEquipmentRentalController::class, 'approve'])->name('admin.rentals.approve');

    // Управление категориями курсов
    Route::get('/course-categories/create', [AdminCourseCategoryController::class, 'create'])->name('admin.course-categories.create');
    Route::post('/course-categories', [AdminCourseCategoryController::class, 'store'])->name('admin.course-categories.store');
});


// ==================== МАРШРУТЫ ДЛЯ ПРЕПОДАВАТЕЛЕЙ ====================
Route::prefix('teachers')->name('teachers.')->middleware(['auth', 'teacher'])->group(function () {
    Route::get('/my-courses', [TeacherController::class, 'myCourses'])->name('my-courses');
    Route::delete('/courses/{course}/leave', [TeacherController::class, 'leaveCourse'])->name('leave-course');
});



Route::middleware(['auth', 'admin'])->prefix('admin')->group(function() {
    Route::post('/courses/invite-teacher', [CourseController::class, 'inviteTeacher'])->name('admin.courses.invite-teacher');
});
// ==================== ПОЛЬЗОВАТЕЛЬСКИЕ МАРШРУТЫ ====================
// Профиль пользователя
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/courses/{course}/enroll', [ProfileController::class, 'enroll'])->name('profile.courses.enroll');
    Route::post('/courses/{course}/complete', [ProfileController::class, 'complete'])->name('profile.courses.complete');
    Route::post('/courses/{course}/update-progress', [ProfileController::class, 'updateProgress'])->name('profile.courses.update-progress');
    Route::get('/courses/{course}/enroll', [CourseController::class, 'showEnrollForm'])->name('courses.enroll.form');
Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');
    // Заявки пользователя

    // Статьи пользователя
    Route::get('/articles/create', [UserArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [UserArticleController::class, 'store'])->name('articles.store');
    // Список снаряжения
Route::get('/equipment', [EquipmentController::class, 'index'])->name('equipment.index');

// Страница аренды
Route::get('/equipment/{id}/rent', [EquipmentRentalController::class, 'create'])->name('equipment.rent');
Route::post('/equipment/{id}/rent', [EquipmentRentalController::class, 'store'])->name('equipment.rent.store');
Route::get('/equipments', [EquipmentController::class, 'index'])->name('equipments.index');

});

// Публичные маршруты (доступны всем)
Route::get('/articles', [UserArticleController::class, 'index'])->name('articles.index');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

// Курсы
Route::resource('courses', CourseController::class)->only(['index', 'show']);
Route::post('/promotions/{promotion}/enroll', [PromotionController::class, 'enroll'])->name('promotions.enroll');

// Подгрузка формы в модальное окно
Route::get('/promotions/enroll-form/{promotion}', [PromotionController::class, 'loadEnrollForm'])->name('promotions.enroll-form');

// Категории курсов
Route::resource('course-categories', CourseCategoryController::class)->only(['index', 'show']);
Route::get('/course-categories/{category}/courses', [CourseCategoryController::class, 'showCourses'])->name('course-categories.show-courses');

// Видео
Route::resource('videos', VideoController::class)->only(['index']);

// Акции
// Для формы записи по акции
Route::get('/promotions/{promotion}/enroll-form', [PromotionController::class, 'showEnrollForm'])
    ->middleware('auth')
    ->name('promotions.enrollForm');


// FAQ
Route::get('/faq', function () {
    return view('faq');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Маршруты для походов
    Route::resource('trips', TripController::class);
    Route::post('/trips/{trip}/join', [TripController::class, 'join'])
    ->name('trips.join')
    ->middleware('auth');
});

Route::prefix('survival-test')->group(function () {
    Route::get('/', [SurvivalTestController::class, 'showTest'])->name('survival.test');
    Route::post('/submit', [SurvivalTestController::class, 'submitTest'])->name('survival.submit');
    Route::get('/results', [SurvivalTestController::class, 'showResults'])->name('survival.results');
    Route::get('/results/{id}', [SurvivalTestController::class, 'showResults'])->name('survival.result');
});
Route::get('/content', [ContentController::class, 'index'])->name('content.index');