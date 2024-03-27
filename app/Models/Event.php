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
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'start_datetime' => 'datetime', // Specify 'start_datetime' as a datetime type
        'end_datetime' => 'datetime',
        'duplicate_end_date' => 'datetime',
    ];

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'registration_deadline',
        'start_datetime',
        'end_datetime',
        'duplicate_interval',
        'duplicate_end_date',
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
