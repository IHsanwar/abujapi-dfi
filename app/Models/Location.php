<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    //
protected $fillable = [
    'name',
    'code',
];

    public function reports()
{
    return $this->hasMany(Report::class);
}

}
