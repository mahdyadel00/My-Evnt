<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function deleteNotification($id)
    {
        dd($id);
        $notification = Notification::find($id);
        $notification->delete();
        return redirect()->back()->with('success', 'Notification deleted successfully');
    }
}
