<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::query();
    
        if ($request->has('start_date') && $request->input('start_date')) {
            $courses->where('start_date', '>=', $request->input('start_date'));
        }
        if ($request->has('end_date') && $request->input('end_date')) {
            $courses->where('end_date', '<=', $request->input('end_date'));
        }
    
        if ($request->has('min_people') && $request->input('min_people')) {
            $courses->where('min_people', '>=', $request->input('min_people'));
        }
        if ($request->has('max_people') && $request->input('max_people')) {
            $courses->where('max_people', '<=', $request->input('max_people'));
        }
    
        if ($request->has('category_id') && $request->input('category_id')) {
            $courses->where('course_category_id', $request->input('category_id'));
        }
    
        if ($request->has('min_price') && $request->input('min_price')) {
            $courses->where('price', '>=', (float)$request->input('min_price'));
        }
        if ($request->has('max_price') && $request->input('max_price')) {
            $courses->where('price', '<=', (float)$request->input('max_price'));
        }
    
        $courses = $courses->get();
        $categories = CourseCategory::all();
    
        return view('courses.index', compact('courses', 'categories'));
    }
    
    public function show($id)
    {
        $course = Course::with(['teacher', 'category'])->findOrFail($id);
        return view('courses.show', compact('course'));
    }

    public function showEnrollForm(Course $course)
    {
        $availableCourses = Course::where('id', '!=', $course->id)->get();
        return view('courses.enroll', compact('course', 'availableCourses'));
    }
    
    public function enroll(Request $request, Course $course)
{
    $user = Auth::user();
    
    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Требуется авторизация'
        ], 401);
    }
    
    $validated = $request->validate([
        'course_id' => 'required|exists:courses,id',
        'phone' => 'required|string|max:20',
        'age' => 'required|integer|min:12|max:100',
        'attended_previous_courses' => 'required|boolean',
        'message' => 'nullable|string|max:1000',
    ]);

    $selectedCourseId = $validated['course_id'];
    
    // Проверка на дублирование записи в course_user
    if ($user->courses()->where('course_id', $selectedCourseId)->exists()) {
        return response()->json([
            'success' => false,
            'message' => 'Вы уже записаны на этот курс'
        ], 422);
    }

    // Запись в таблицу course_user
    $user->courses()->attach($selectedCourseId, [
        'status' => 'pending',
        'phone' => $validated['phone'],
        'age' => $validated['age'],
        'attended_previous_courses' => $validated['attended_previous_courses'],
        'message' => $validated['message'] ?? null,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Вы успешно записаны на курс! Ожидайте подтверждения.'
    ]);
}
    }
    