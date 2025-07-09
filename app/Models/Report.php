<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;

   protected $fillable = [
        'user_id',
        'description',
        'area',
        'reported_at',
        'image_url',
        'location',
        'location_id',
    ];

    public function user()
{
    return $this->belongsTo(User::class);
}
    public function location()
    {
        return $this->belongsTo(Location::class);
    }


}
