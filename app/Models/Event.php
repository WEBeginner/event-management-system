<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

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

    /**
     * Scope for upcoming events (start_time is in the future)
     * Usage: Event::upcoming()->get()
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('start_time', '>', now());
    }

    /**
     * Scope for past events (start_time is in the past)
     * Usage: Event::past()->get() 
     */
    public function scopePast(Builder $query): Builder
    {
        return $query->where('start_time', '<', now());
    }

    /**
     * Scope for published events
     * Usage: Event::published()->get()
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }
}