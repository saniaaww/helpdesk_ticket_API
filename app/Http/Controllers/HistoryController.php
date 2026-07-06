<?php

namespace App\Http\Controllers;

use App\Models\TicketHistory;

class HistoryController extends Controller
{
    public function index($ticketId)
    {
        return response()->json(
            TicketHistory::where(
                'ticket_id',
                $ticketId
            )->latest()->get()
        );
    }
}