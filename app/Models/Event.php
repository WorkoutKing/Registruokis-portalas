<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EventRegistration;
use App\Models\DynamicField;
use App\Models\EventDynamicField;
use App\Models\EventFile;



class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'registration_deadline',
        'start_datetime',
        'end_datetime',
        'max_participants',
    ];
    public function dynamicFields()
    {
        return $this->hasMany(EventDynamicField::class);
    }
    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }
    public function dynamicFieldsreg()
    {
        return $this->hasMany(DynamicField::class);
    }
    public function files()
    {
        return $this->hasMany(EventFile::class);
    }
}
