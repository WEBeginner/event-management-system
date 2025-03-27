<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendee extends Model
{
    protected $fillable = ['user_id', 'event_id', 'status', 'notes'];

    // Attendee belongs to a User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Attendee belongs to an Event
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}