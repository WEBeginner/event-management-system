<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    // Category belongs to many Events
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'category_event');
    }
}