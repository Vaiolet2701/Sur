<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class TeacherMiddleware
{
    public function handle($request, Closure $next)
    {
        /** @var User|null $user */
        $user = Auth::user();
        
        if ($user === null || $user->isTeacher() === false) {
            Log::warning('Попытка доступа к разделу преподавателя', [
                'ip' => $request->ip(),
                'attempted_url' => $request->url()
            ]);
            
            return redirect()->route('home')
                ->with('error', 'Доступ разрешен только преподавателям');
        }

        return $next($request);
    }
}