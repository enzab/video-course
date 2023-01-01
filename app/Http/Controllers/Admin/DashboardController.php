<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Review;
use App\Models\Category;
use App\Models\Showcase;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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
        // tampung jumlah data category kedalam variabel $category
        $category = Category::count();

        // tampung jumlah data course kedalam variabel $course
        $course = Course::count();

        // tampung jumlah data transaction yang memilike status "success" kedalam variabel $transaction
        $transaction = Transaction::where('status', 'success')->count();

        // tampung jumlah data transaction yang memiliki status "success" kemudian jumlahkan "grand_total" dan masukkan kedalam variable $revenue.
        $revenue = Transaction::where('status', 'success')->sum('grand_total');

        // tampung jumlah data user yang memiliki role "author" ke dalam variable $author
        $author = User::role('author')->count();

        // tampung jumlah data showcase kedalam variable $showcase
        $showcase = Showcase::count();

        // tampung jumlah data review kedalam variable $review
        $review = Review::count();

        // tampung jumlah data user kedalam variabel $member.
        $member = User::count();

        // tampung data best course kedalam variabel $bestCourse, disini kita melakukan sebuah query builder untuk memanipulasi data yang akan kita ambil yaitu hanya berupa sebuah nama course dan total dari transaction course tersebut yang kita ubah namanya menjadi total, disini kita tetapkan limit data yang di ambil hanya berjumlah 5.
        $bestCourse = DB::table('transaction_details')
                            ->addSelect(DB::raw('courses.name as name, count(transaction_details.course_id) as total'))
                            ->join('courses', 'courses.id', 'transaction_details.course_id')
                            ->groupBy('transaction_details.course_id')
                            ->orderBy('total', 'DESC')
                            ->limit(5)
                            ->get();

        // tampung data array kosong kedalam variable $label
        $label = [];

        // tampung data array kosong ke dalam variable $total
        $total = [];

        // cek apakah variable $bestCourse memiliki nilai atau tidak
        if(count($bestCourse)) {
            // lakukan perulangan data $bestCourse yang kita ubah menjadi variable $data
            foreach($bestCourse as $data) {
                // tampung variable $data->name ke dalam variable $label[]
                $label[] = $data->name;
                // tampung variable $data->total kedalam variable $total[]
                $total[] = (int) $data->total;
            }
        // jika variable $bestCourse tidak memiliki nilai
        } else {
            // masukkan empty string ke dalam varaible $label[]
            $label[] = '';
            // masukkan empty string ke dalam variable $total[]
            $total[] = '';
        }

        return view('admin.dashboard', compact('category', 'course', 'transaction', 'revenue', 'author', 'showcase', 'review', 'member', 'label', 'total'));

    }
}
