<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Labourer extends Model
{
    protected $fillable = ['user_id', 'name', 'default_rate'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expenseDetails()
    {
        return $this->hasMany(LabourExpenseDetail::class);
    }
}