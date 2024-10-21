<?php

use App\Models\Classes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

if (!function_exists('classGet')) {
    function classGet()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $classes = collect();

            if (!Gate::denies('view', $user)) {
                $classes = Classes::all();
            }

            if ($user->role === 'teacher') {
                $classes = Classes::where('id', $user->teacher->class_id)->get();
            } elseif ($user->role === 'student') {
                $classes = Classes::where('id', $user->student->class_id)->get();
            } elseif ($user->role === 'parent') {
                $students = $user->parent->students;
                $classIds = $students->pluck('class_id');

                $classes = Classes::whereIn('id', $classIds)->get();
            }
        }

        return $classes;
    }
}


