<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;


class EventDynamicField extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'options',
    ];

    /**
     * Get the event that owns the dynamic field.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
