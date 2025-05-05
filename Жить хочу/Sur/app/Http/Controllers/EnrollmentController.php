<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Promotion;


class EnrollmentController extends Controller
{
    // Список заявок текущего пользователя
    public function index()
    {
        $enrollments = Enrollment::where('user_id', Auth::id())
            ->with(['course' => function($query) {
                $query->select('id', 'title', 'price'); // Добавляем выборку цены
            }])
            ->get();
            
        return view('enrollments.index', compact('enrollments'));
    }

// Для обычной записи на курс
public function showEnrollForm(Course $course)
{
    $courses = Course::all(); // Все курсы для выпадающего списка
    return view('enrollments.index', [
        'course' => $course,
        'courses' => $courses
    ]);
}

// Для записи по акции
public function showPromotionEnrollForm(Promotion $promotion)
{
    $courses = Course::all();
    return view('enrollments.index', [
        'promotion' => $promotion,
        'courses' => $courses
    ]);
}
  
      // Обработка отправки формы
      public function enroll(Request $request)
      {
          $validated = $request->validate([
              'user_id' => 'required|exists:users,id',
              'course_id' => 'required|exists:courses,id',
              'promotion_id' => 'nullable|exists:promotions,id',
              'name' => 'required|string|max:255',
              'email' => 'required|email|max:255',
              'phone' => 'required|string|max:20',
              'age' => 'required|integer|min:12|max:100',
              'attended_previous_courses' => 'required|boolean',
              'message' => 'nullable|string|max:1000',
          ]);
  
          // Проверка дублирования записи
          $existing = Enrollment::where('user_id', $validated['user_id'])
              ->where('course_id', $validated['course_id'])
              ->exists();
  
          if ($existing) {
              return response()->json([
                  'success' => false,
                  'message' => 'Вы уже записаны на этот курс'
              ], 422);
          }
  
          // Создание записи
          $enrollment = Enrollment::create($validated);
  

  
          return response()->json([
              'success' => true,
              'message' => 'Вы успешно записаны!'
          ]);
      }
}