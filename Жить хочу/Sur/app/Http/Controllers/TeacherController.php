<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = User::where('is_teacher', true)->get();
        return view('teachers.index', compact('teachers'));
    }

    public function show($id)
    {
        $teacher = User::with('taughtCourses')->findOrFail($id);
        return view('teachers.show', compact('teacher'));
    }

    public function create()
    {
        return view('teachers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'full_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'age' => 'required|integer|min:18',
            'work_experience' => 'required|integer',
            'bio' => 'nullable|string'
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        $validated['is_teacher'] = true;
        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);

        return redirect()->route('teachers.index')->with('success', 'Преподаватель добавлен');
    }
}