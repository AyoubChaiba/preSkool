<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function getNotifications()
    {
        $notifications = Auth::user()->notifications()->latest()->get();

        return response()->json($notifications);
    }


}
