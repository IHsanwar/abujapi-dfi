<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;


class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nik',
        'phone_number',
        'status',
        'address',
        'gender',
        'age',
        'height',
        'weight',
        'education',
        'bank_account',
        'employee_status',
        'position',
        'work_duration',
        'placement_location',
        'portfolio_link',
        'work_experience',
        'skills',
        'grade',
    ];

    /**
     * Relasi ke model User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
