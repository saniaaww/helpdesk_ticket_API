<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketHistory;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Notification;

class TicketController extends Controller
{
    // =========================
    // GET /api/tickets
    // =========================
    public function index()
    {
        return response()->json(
            Ticket::with(['user', 'assignedUser'])
                ->latest()
                ->get()
        );
    }

    // =========================
    // POST /api/tickets
    // =========================
    public function store(Request $request)
    {
        $imageName = null;

        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $imageName = time().'_'.$image->getClientOriginalName();

            $image->storeAs(
                'tickets',
                $imageName,
                'public'
            );
        }

        $ticket = Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'assigned_to' => null,
            'status' => 'Open',
            'image' => $imageName,
        ]);

        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'activity' => 'Ticket dibuat oleh user',
        ]);

        $admins = User::where('role', 'admin')->get();

foreach ($admins as $admin) {

    Notification::create([
        'sender_id' => $ticket->user_id,
        'user_id' => $admin->id,
        'ticket_id' => $ticket->id,
        'title' => 'Ticket Baru',
        'message' => 'Ticket baru dibuat: '.$ticket->title,
        'is_read' => 0,
    ]);

}

        return response()->json([
            'success' => true,
            'data' => $ticket,
        ]);
    }

    // =========================
    // GET /api/tickets/{id}
    // =========================
    public function show($id)
    {
        return response()->json(
            Ticket::with(['user', 'assignedUser'])
                ->findOrFail($id)
        );
    }

    // =========================
    // POST /api/tickets/{id}/assign
    // =========================
    public function assign(Request $request, $id)
{
    $ticket = Ticket::findOrFail($id);

    if ($ticket->assigned_to != null) {
        return response()->json([
            'success' => false,
            'message' => 'Ticket sudah pernah diassign.'
        ], 400);
    }

    $helpdesk = User::findOrFail($request->assigned_to);

    $ticket->assigned_to = $helpdesk->id;
    $ticket->status = "In Progress";
    $ticket->save();

    TicketHistory::create([
        'ticket_id' => $ticket->id,
        'activity' => 'Ticket diassign ke '.$helpdesk->name,
    ]);

    Notification::create([
        'sender_id' => $request->sender_id,
        'user_id' => $helpdesk->id,
        'ticket_id' => $ticket->id,
        'title' => 'Ticket Baru',
        'message' => 'Anda mendapat ticket baru: '.$ticket->title,
        'is_read' => 0,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Ticket berhasil diassign',
    ], 200);
}

    // =========================
    // POST /api/tickets/{id}/status
    // =========================
    public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:Open,In Progress,Close'
    ]);

    $ticket = Ticket::findOrFail($id);

    $ticket->status = $request->status;
    $ticket->save();

    TicketHistory::create([
        'ticket_id' => $ticket->id,
        'activity' => 'Status berubah menjadi '.$request->status,
    ]);

    // ===========================================
    // KALAU TICKET SELESAI -> KIRIM KE USER
    // ===========================================

    if ($request->status == "Close") {

        Notification::create([
            'sender_id' => $request->sender_id,
            'user_id' => $ticket->user_id,
            'ticket_id' => $ticket->id,
            'title' => 'Status Ticket',
            'message' => 'Status ticket berubah menjadi '.$request->status,
            'is_read' => 0
        ]);

    }

    return response()->json([
        'success' => true,
        'message' => 'Status berhasil diperbarui',
    ]);
}

    // =========================
    // GET /api/helpdesk/tickets/{id}
    // =========================
    public function byHelpdesk($id)
    {
        return response()->json(

            Ticket::with(['user', 'assignedUser'])
                ->where('assigned_to', $id)
                ->latest()
                ->get()

        );
    }

    public function byUser($id)
    {
        return Ticket::with(['user','assignedUser'])
            ->where('user_id', $id)
            ->latest()
            ->get();
    }
}