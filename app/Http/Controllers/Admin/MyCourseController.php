<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;

class MyCourseController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // tampung data user yang sedang login kedalam variable $user
        $user = $request->user();

        /*
            tampung data transaction detail kedalam variable $courses, kemudian kita memanggil relasi menggunakan with,
            selanjutnya kita melakukan sebuah query untuk mengambil data transaction yang memiliki status success dan seusai dengan user yang sedang login, selanjutnya kita juga melakukan query untuk pencarian data berdasarkan course name, kemudiang kita pecah data transaction detail yang kita tampilkan hanya 3 per halaman dengan urutan terbaru.
        */

        $courses = TransactionDetail::with('transaction', 'course.review')
                    ->whereHas('transaction', function($query) use($user) {
                        $query->where('user_id', $user->id)->where('status', 'success');
                    })->whereHas('course', function($query) {
                        $query->where('name', 'like', '%'. request()->search . '%');
                    })->latest()->paginate(3);

        // passing variable $courses kedalam view
        return view('admin.course.mycourse', compact('courses'));
    }
}
