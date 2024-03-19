<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Event;
use App\Models\DynamicField;


class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'event_id', 'name', 'phone', 'surname', 'email', 'comments', 'on_waiting_list'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    public function dynamicFieldsreg()
    {
        return $this->hasMany(DynamicField::class);
    }
}
