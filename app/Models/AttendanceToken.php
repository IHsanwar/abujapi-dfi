<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class AttendanceToken extends Model
{
    protected $fillable = ['token', 'expires_at'];

    public $timestamps = true;

    public function isExpired()
    {
        return $this->expires_at < Carbon::now();
    }
}
