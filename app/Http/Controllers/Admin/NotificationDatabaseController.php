<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationDatabaseController extends Controller
{
    public function readNotification($id)
    {
        // ambil data notification dari user yang sedang login kemudian "mark as read" yang dimana "id"nya sama dengan variable $id
        Auth::user()->readNotifications->where('id', $id)->markAsRead();

        // kembali kehalaman sebelumnya
        return back();
    }

    public function readlAllNotification()
    {
        // ambil seluruh data notifikasi yang belum dibaca kemudian "mark as read"
        Auth::user()->unreadNotifications->markAsRead();

        // kembali ke halaman sebelumnya
        return back();
    }
}
