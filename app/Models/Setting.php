<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'user_id', 'season_start_month', 'default_day_rate', 'default_per_person_rate'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}