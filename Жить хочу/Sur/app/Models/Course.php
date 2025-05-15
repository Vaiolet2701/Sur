<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // Добавьте этот импорт

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'course_category_id', 
        'start_date', 'end_date', 'image_path',
        'min_people', 'max_people', 'teacher_id',
        'animals', 'price', 'is_active', 'latitude', 'longitude', 'location_name'
    ];

    public function category()
    {
        return $this->belongsTo(CourseCategory::class, 'course_category_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function users(): BelongsToMany
{
    return $this->belongsToMany(User::class, 'course_user')
        ->withPivot([
            'status',
            'progress',
            'phone',
            'age',
            'attended_previous_courses',
            'message',
            'rejection_reason',
            'completed_at'
        ])
        ->withTimestamps();
}

// В модели Course
public static function checkAndCompleteExpiredCourses()
{
    $courses = self::where('end_date', '<', now())
        ->whereHas('users', function($q) {
            $q->where('status', 'in_progress');
        })
        ->with(['users' => function($q) {
            $q->where('status', 'in_progress');
        }])
        ->get();

    foreach ($courses as $course) {
        $course->users()->updateExistingPivot(
            $course->users->pluck('id'),
            ['status' => 'completed', 'completed_at' => now()]
        );
    }
}
    // Все преподаватели курса (через промежуточную таблицу)
    public function teachers()
    {
        return $this->belongsToMany(User::class, 'course_teacher', 'course_id', 'teacher_id')
            ->withPivot('is_active')
            ->withTimestamps();
    }
    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

}