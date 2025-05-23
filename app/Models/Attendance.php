<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['user_id', 'attended_at', 'kehadiran'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
