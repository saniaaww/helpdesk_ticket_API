<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index($userId)
    {
        return response()->json(

            Notification::with('sender')

                ->where('user_id', $userId)

                ->orderBy('created_at','desc')

                ->get()

        );
    }

    public function read($id)
    {
        $notif = Notification::findOrFail($id);

        $notif->update([
            'is_read' => true
        ]);

        return response()->json([
            'success' => true
        ]);
    }
}