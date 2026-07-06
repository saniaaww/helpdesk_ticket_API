<?php

namespace App\Http\Controllers;

use App\Models\Ticket;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'total' =>
                Ticket::count(),

            'open' =>
                Ticket::where(
                    'status',
                    'Open'
                )->count(),

            'process' =>
                Ticket::where(
                    'status',
                    'Process'
                )->count(),

            'done' =>
                Ticket::where(
                    'status',
                    'Done'
                )->count(),
        ]);
    }
}