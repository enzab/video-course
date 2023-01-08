<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Http\Requests\ReviewRequest;

class ReviewController extends Controller
{
    public function store(ReviewRequest $request, Course $course)
    {
        /*
            masukkan data baru review dengan "course_id" sesuai dengan variable $course, karena disini kita menggunakan updateOrCreate maka jika user yang sedang login pernah memberikan review maka data hanya akan diupdate jika belum maka akan memasukkan data baru
        */
        $course->reviews()->updateOrCreate([
            'user_id' => $request->user()->id,
        ],[
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        // kembali kehalaman sebelumnya dengan membawa toastr
        return back()->with('toast_success', 'Review Created');
    }
}
