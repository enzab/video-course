<?php

namespace App\Http\Controllers\Member;

use App\Models\Review;
use App\Models\Showcase;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
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
            tampung data transaction detail kedalam varaible $course, kemudian kita memanggil relasi menggunakan with, selanjutnya kita melakukan sebuah query untuk mengambil data transaction yang memiliki status success dan seusai dengan user yang sedang login, selanjutnya kita jumlahkan data tersebut.
        */
        $course = TransactionDetail::with('transaction', 'course.reviews')
                    ->whereHas('transaction', function($query) use($user) {
                        $query->where('user_id', $user->id)->where('status','success');
                    })->count();

        // tampung jumlah data review user yang sedang login kedalam variable $review
        $review = Review::where('user_id', $user->id)->count();

        // tampung jumlah data transaction user yang sedang login dan memilih status "success" kedalam variable $transaction
        $transaction = Transaction::where('user_id', $user->id)
                        ->where('status', 'success')->count();

        // tampung jumlah data showcase user yang sedang login kedalam variable $showcase
        $showcase = Showcase::where('user_id', $user->id)->count();

        // passing variable $course, $review, $transaction, dan $showcase
        return view('member.dashboard', compact('course', 'review', 'transaction', 'showcase'));
    }
}
