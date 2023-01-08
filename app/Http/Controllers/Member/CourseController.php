<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Http\Requests\CourseRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // tampung data user yang sedang login kedalam variablr $user
        $user = Auth::user();

        /*
            tampung semua data course kedalam variable $courses, kemudian kita memanggil relasi menggunakan withcount, selanjutnya pada saat melakukan pemanggilan relasi details yang kita ubah namanya menjadi enrolled, disini kita melakukan sebuah query untuk memanggil data transaksi yang memiliki status success, selanjutnya kita pecah data course yang kita tampilkan hanya 12 per halaman dengan urutan terbaru dan juga kita hanya menampilkan data course yang dimiliki oleh user yang sedang login
        */

        $courses = Course::withCount(['videos as video', 'details as enrolled' => function($query) {
            $query->whereHas('transaction', function($query){
                $query->where('status', 'success');
            });
        }])->where('user_id', $user->id)->latest()->paginate(12);

        // passing variable $courses kedalam view
        return view('member.course.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // tampung seluruh data category kedalam variable $categories
        $categories = Category::all();

        // passing variable $categories kedalam view
        return view('member.course.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CourseRequest $request)
    {
        // tampung request file image kedalam variable $image
        $image = $request->file('image');

        // request yang telah kita tampung kedalam variable, kita masukkan kedalam folder public/course
        $image->storeAs('public/course', $image->hashName());

        // masukkan data baru course dengan user_id sesuai dengan user yang sedang memberikan request
        $request->user()->courses()->create([
            'name' => $request->name,
            'image' => $request->file('image') ? $image->hashName() : null,
            'price' => $request->price,
            'description' => $request->description,
            'demo' => $request->demo,
            'category_id' => $request->category_id,
            'discount' => $request->discount,
        ]);

        // kembali kehalaman member/course/index dengan membawa toastr
        return redirect(route('member.course.index'))->with('toast_success', 'Course Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        // tampung seluruh data category kedalam variable $categories
        $categories = Category::all();

        // passing variable $categories dan $course kedalam view
        return view('member.course.edit', compact('categories', 'course'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        // update data course berdasarkan id
        $course->update([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'demo' => $request->demo,
            'category_id' => $request->category_id,
            'discount' => $request->discount,
        ]);

        // cek apakah user mengirimkan request file image
        if($request->file('image')) {
            // hapus image course yang sebelumnya
            Storage::disk('local')->delete('public/course'.basename($course->image));
            // tampung request file image kedalam variable $image
            $image = $request->file('image');
            // request yang telah kita tampung kedalam variable kita masukkan kedalam folder public/course
            $image->storeAs('public/course', $image->hashName());
            // update data course image berdasarkan id
            $course->update([
                'image' => $image->hashName()
            ]);
        }

        // kembali kehalaman member/course/index dengan membawa toastr
        return redirect(route('member.course.index'))->with('toast_success', 'Course Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        // hapus image course berdasarkan id
        Storage::disk('local')->delete('public/course/'.basename($course->image));

        // hapus data course berdasarkan id
        $course->delete();

        // kembali kehalaman sebelumnya dengan membawa toast
        return back()->with('toast_success', 'Course Deleted');
    }
}
