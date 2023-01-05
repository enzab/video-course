<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        /* 
            tampung semua data transaction kedalam variable $transactions, kemudian kita memanggil relasi menggunakan with, disini kita juga menambahkan method search yang kita dapatkan dari sebuah trait hasScope,
            selanjutnya kita pecah data transaction yang kita tampilkan hanya 10 per halaman dengan urutan terbaru
        */
        $transactions = Transaction::with('details.course', 'user')
                        ->latest()
                        ->search('status')
                        ->paginate(10)
                        ->withQueryString();

        // jumlahkan "grand_total" dari variable $transactions kemudian tampung data tersebut kedalam variable $grandTotal
        $grandTotal = $transactions->sum('grand_total');

        // passing variable $transactions dan $grandTotal kedalam view
        return view('admin.transaction.index', compact('transactions', 'grandTotal'));
    }

    public function show(Transaction $transaction)
    {
        // tampung data transaction detail kedalam variable $orders, yang dimana "transaction_id"nya sama dengan variable $transaction->id
        $orders = TransactionDetail::with('transaction', 'course')
                    ->where('transaction_id', $transaction->id)
                    ->get();

        // ambil data "snap_token" dari variable $transactions kemudian tampung data tersebut kedalam variable $snapToken
        $snapToken = $transaction->snap_token;

        // jumlahkan "price" dari varaible $orders kemudian tampung data tersebut kedalam varaible $grandTotal
        $grandTotal = $orders->sum('price');

        // passing variable $orders, $grandTotal, $transaction, dan $snapToken kedalam view
        return view('admin.transaction.show', compact('orders', 'grandTotal', 'transaction', 'snapToken'));
    }
}
