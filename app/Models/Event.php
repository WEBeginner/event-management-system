<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

// if you guys want to add image you need to add a image_path column in the events table and also add in the migration file by create a new migration file and update the event table
class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'description', 'start_time', 
        'end_time', 'location', 'capacity', 'image_path', 'is_published'
    ];

    protected $dates = ['start_time', 'end_time', 'deleted_at'];

    // Event belongs to a User (Organizer)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Event has many Attendees (Users)
    public function attendees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'attendees')
                   ->withPivot('status', 'notes')
                   ->withTimestamps();
    }

    // Event belongs to many Categories
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_event')
                   ->withTimestamps();
    }
}