<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
        'user_id',
        'assigned_to',
        'image',
    ];

    /**
     * User yang membuat tiket
     */
    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    /**
     * Helpdesk yang menerima assignment
     */
    public function helpdesk()
    {
        return $this->belongsTo(
            User::class,
            'assigned_to'
        );
    }

    /**
     * Riwayat tiket
     */
    public function histories()
    {
        return $this->hasMany(
            TicketHistory::class,
            'ticket_id'
        );
    }

    /**
     * Komentar tiket
     */
    public function comments()
    {
        return $this->hasMany(
            Comment::class,
            'ticket_id'
        );
    }

public function assignedUser()
{
    return $this->belongsTo(User::class, 'assigned_to');
}
}   
