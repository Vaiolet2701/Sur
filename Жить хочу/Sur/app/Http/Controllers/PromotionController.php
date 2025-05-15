<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Promotion;
use App\Models\CourseCategory;
use App\Models\AdminArticle;
use App\Models\Video;
use App\Models\UserArticle;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\Enrollment;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::all();
        $categories = CourseCategory::all();
        $userArticles = UserArticle::all();
        $reviews = Review::all();
        $courses = Course::inRandomOrder()->take(15)->get();
        $adminArticles = AdminArticle::inRandomOrder()->take(3)->get();
        $userArticles = UserArticle::inRandomOrder()->take(3)->get();
        $videos = Video::inRandomOrder()->take(1)->get(); // 1 видео

        return view('index', compact(
            'promotions',
            'categories',
            'courses', // Передаём переменную $courses
            'adminArticles',
            'videos',
            'userArticles',
            'reviews'
        ));
    }
    public function loadEnrollForm(Promotion $promotion)
    {
        $availableCourses = Course::where('is_active', true)->get(); // или другая логика фильтрации
    
        return view('promotions.enroll', [
            'promotion' => $promotion,
            'availableCourses' => $availableCourses
        ]);
    }
    
  

public function applyDiscount(Request $request, $promotionId)
{
    // Находим акцию
    $promotion = Promotion::findOrFail($promotionId);

    // Применяем скидку к текущему заказу или курсу
    $course = Course::find($request->input('course_id'));
    $discountedPrice = $course->price * (1 - $promotion->discount / 100);

    // Сохраняем скидку в сессии
    session([
        'applied_discount' => $promotion->discount,
        'discounted_price' => $discountedPrice,
    ]);

    return response()->json([
        'success' => true,
        'discount' => $promotion->discount,
        'discounted_price' => $discountedPrice,
    ]);
}
public function enroll(Request $request, Promotion $promotion)
{
    // Валидация данных
    $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'promotion_id' => 'required|exists:promotions,id',
            'course_id' => 'required|exists:courses,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'age' => 'required|integer|min:12|max:100',
            'attended_previous_courses' => 'required|boolean',
            'message' => 'nullable|string|max:1000',
        ]);

    // Находим курс
    $course = Course::findOrFail($data['course_id']);

    // Устанавливаем цену курса
    $data['price'] = $course->price;

    // Если есть акция, применяем скидку
    if ($promotion) {
        $data['price'] = $course->price * (1 - $promotion->discount / 100);
        $data['promotion_id'] = $promotion->id;
    }

    // Создаем запись в таблице enrollments
    Enrollment::create($data);

    return response()->json(['success' => true]);
}
}