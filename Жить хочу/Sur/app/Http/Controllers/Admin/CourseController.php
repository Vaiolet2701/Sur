<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseInvitation;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User; 

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        $categories = CourseCategory::all();
        $teachers = User::where('role', 'teacher')->get(); // Получаем всех преподавателей
        
        return view('admin.courses.create', compact('categories', 'teachers'));
    }
    public function show(Course $course)
    {
        return view('admin.courses.show', compact('course'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'course_category_id' => 'required|exists:course_categories,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'min_people' => 'nullable|integer|min:1',
            'max_people' => 'nullable|integer|min:1',
            'animals' => 'nullable|string',
            'price' => 'nullable|numeric|min:0', // Добавлено
        ]);
    
        $data = $request->except('image');
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('courses', 'public');
            $data['image_path'] = $imagePath;
        }
    
        Course::create($data);
    
        return redirect()->route('admin.courses.index')->with('success', 'Курс успешно создан.');
    }
    public function edit(Course $course)
{
    $categories = CourseCategory::all();
    $teachers = User::where('role', 'teacher')->get();
    
    return view('admin.courses.edit', compact('course', 'categories', 'teachers'));
}
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'course_category_id' => 'required|exists:course_categories,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'min_people' => 'nullable|integer|min:1',
            'max_people' => 'nullable|integer|min:1',
            'animals' => 'nullable|string',
            'price' => 'nullable|numeric|min:0', // Добавлено
           'teacher_id' => 'nullable|exists:users,id',
            ]);
            
                $data = $request->except('image');
                
                // Если указан новый преподаватель, обновляем связь
                if ($request->has('teacher_id')) {
                    $data['teacher_id'] = $request->teacher_id;
                }
            
                // Обработка изображения
                if ($request->hasFile('image')) {
                    if ($course->image_path) {
                        Storage::disk('public')->delete($course->image_path);
                    }
                    $imagePath = $request->file('image')->store('courses', 'public');
                    $data['image_path'] = $imagePath;
                }
            
                $course->update($data);
            
                return redirect()->route('admin.courses.index')->with('success', 'Курс успешно обновлен.');
            }
    public function destroy(Course $course)
    {
        // Удаляем изображение, если оно есть
        if ($course->image_path) {
            Storage::disk('public')->delete($course->image_path);
        }

        // Удаляем курс
        $course->delete();

        return redirect()->route('admin.courses.index')->with('success', 'Курс успешно удален.');
    }
    public function inviteTeacher(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
        'message' => 'required|string|max:1000',
        'course_id' => 'required|exists:courses,id'
    ]);

    $teacher = User::where('email', $request->email)->first();
    $course = Course::find($request->course_id);

    // Проверяем, не является ли пользователь уже преподавателем
    if (!$teacher->is_teacher) {
        return response()->json([
            'success' => false,
            'message' => 'Пользователь не является преподавателем'
        ]);
    }

    // Проверяем, не было ли уже приглашения
    if (CourseInvitation::where('course_id', $course->id)
                        ->where('teacher_id', $teacher->id)
                        ->exists()) {
        return response()->json([
            'success' => false,
            'message' => 'Приглашение уже отправлено этому преподавателю'
        ]);
    }

    // Создаем приглашение
    CourseInvitation::create([
        'course_id' => $course->id,
        'teacher_id' => $teacher->id,
        'inviter_id' => auth()->id(),
        'message' => $request->message,
        'status' => 'pending'
    ]);
}
}