<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EventRegistration;


class DynamicField extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'event_registration_id',
        'options',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function registration()
    {
        return $this->belongsTo(EventRegistration::class);
    }
}
