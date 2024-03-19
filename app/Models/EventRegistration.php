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

    public function approve()
    {
        if ($this->on_waiting_list == 0) {
            return false;
        }

        $this->on_waiting_list = 0;
        $this->save();

        return true;
    }

    public function getDynamicFieldOptions()
    {
        $options = [];

        if ($this->dynamicFields) {
            foreach ($this->dynamicFields as $dynamicField) {
                $options[$dynamicField->title] = $dynamicField->options;
            }
        }

        return $options;
    }
    public function updateDynamicField($fieldName, $fieldValue)
    {
        $dynamicField = $this->dynamicFieldsreg()->where('title', $fieldName)->first();

        if ($dynamicField) {
            $dynamicField->update(['options' => $fieldValue]);
        } else {
            $this->dynamicFieldsreg()->create([
                'title' => $fieldName,
                'options' => $fieldValue,
            ]);
        }
    }
}
