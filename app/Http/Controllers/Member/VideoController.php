<?php

namespace App\Http\Controllers\Member;

use App\Models\Video;
use App\Models\Course;
use Illuminate\Support\Str;
use App\Http\Requests\VideoRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index($slug)
    {
        // tampung data course kedalam varialbe $course, yang dimana "slug"nya sama dengan variable $slug
        $course = Course::where('slug', $slug)->first();

        // tampung seluruh data video kedalam variable $videos, yang dimana "course_id"nya sama dengan variable $course->id
        $videos = Video::where('course_id', $course->id)->get();

        // passing variable $videos dan $course kedalam view
        return view('member.video.index', compact('videos', 'course'));
    }

    public function create($slug)
    {
        // tampung data course kedalam variable $course, yang dimana "slug"nya sama dengan variable $slug
        $course = Course::where('slug', $slug)->first();

        // passing variable $course kedalam view
        return view('member.video.create', compact('course'));
    }

    public function store($slug, VideoRequest $request)
    {
        // tampung data course kedalam variable $course, yang dimana "slug"nya sama dengan variable $slug
        $course = Course::where('slug', $slug)->first();

        // masukkan data baru video dengan "course_id" sesuai dengan variable $course
        $course->videos()->create([
            'name' => $request->name,
            'episode' => $request->episode,
            'intro' => $request->intro,
            'video_code' => $request->video_code,
        ]);

        // kembali kehalaman sebelumnya dengan membawa toastr
        return redirect(route('member.course.index'))->with('toast_success', 'Video Created');
    }

    public function edit($slug, Video $video)
    {
        // tampung data course kedalam variable $course, yang dimana "slug"nya sama dengan variable $slug
        $course = Course::where('slug', $slug)->first();

        // passing variable $course dan $video kedalam view
        return view('member.video.edit', compact('course', 'video'));
    }

    public function update(VideoRequest $request, $slug, Video $video)
    {
        // tampung data course kedalam variabel $course, yang dimana "slug"nya sama dengan variabel $slug.
        $course = Course::where('slug', $slug)->first();

        // update data video berdasarkan id
        $video->update([
            'name' => $request->name,
            'episode' => $request->episode,
            'intro' => $request->intro,
            'video_code' => $request->video_code,
        ]);

        // kembali kehalaman member/video/index dengan variable $course dan toastr
        return redirect(route('member.video.index', $course))->with('toast_success', 'Video Updated');
    }

    public function destroy(Video $video)
    {
        // hapus data video berdasarkan id
        $video->delete();

        // kembali kehalaman sebelumnya dengan membawa toastr
        return back()->with('toast_success', 'Video Deleted');
    }
}
