<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Ticket;

class CommentController extends Controller
{
    public function index($ticketId)
    {
        return response()->json(

            Comment::with('user')

                ->where('ticket_id', $ticketId)

                ->latest()

                ->get()

        );
    }

    public function store(Request $request, $ticketId)
{
    $comment = Comment::create([

        'ticket_id' => $ticketId,

        'user_id' => $request->user_id,

        'comment' => $request->comment,

    ]);

    $ticket = Ticket::findOrFail($ticketId);

    // ===================================
    // Tentukan penerima notifikasi
    // ===================================

    if ($request->user_id == $ticket->user_id) {

        // USER membalas -> kirim ke Helpdesk

        if ($ticket->assigned_to != null) {

            Notification::create([

                'sender_id' => $request->user_id,

                'user_id' => $ticket->assigned_to,

                'ticket_id' => $ticket->id,

                'title' => 'Balasan Ticket',

                'message' => 'User membalas ticket : '.$ticket->title,

                'is_read' => 0,

            ]);

        }

    } else {

        // Helpdesk/Admin membalas -> kirim ke User

        Notification::create([

            'sender_id' => $request->user_id,

            'user_id' => $ticket->user_id,

            'ticket_id' => $ticket->id,

            'title' => 'Balasan Ticket',

            'message' => 'Ada balasan pada ticket : '.$ticket->title,

            'is_read' => 0,

        ]);

    }

    return response()->json([

        'success' => true,

        'data' => $comment->load('user'),

    ]);
}
}