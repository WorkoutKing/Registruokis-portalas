<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventFile extends Model
{
    protected $fillable = ['file_path', 'file_type'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
