<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        /*
            tampung semua data transaction yang dimiliki oleh user yang sedang login kedalam variable $transactions, kemudian kita memanggil relasi menggunakan with, selanjutnya kita pecah data transaction yang kita tampilkan hanya 10 per halaman dengan urutan terbaru
        */
        $transactions = Transaction::with('details')
                        ->where('user_id', $request->user()->id)
                        ->latest()
                        ->paginate(10);

        // passing variable $transactions kedalam view
        return view('member.transaction.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        // tampung data transaction detail kedalam variable $orders
        $orders = TransactionDetail::with('transaction', 'course')
                    ->where('transaction_id', $transaction->id)
                    ->get();

        // ambil data "snap_token" dari variable $transaction kemudian tampung data tersebut kedalam variable $snapToken
        $snapToken = $transaction->snap_token;

        // jumlahkan "price" dari variable $orders kemudian tampung data tersebut kedalam variable $grandTotal
        $grandTotal = $orders->sum('price');

        // passing variable $orders, $grandTotal, $transaction, dan $snapToken kedalam view
        return view('member.transaction.show', compact('orders', 'snapToken', 'grandTotal', 'transaction'));
    }
}
