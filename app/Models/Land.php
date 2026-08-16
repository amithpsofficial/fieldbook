<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Land extends Model
{
    protected $fillable = ['user_id', 'name', 'plantation_year', 'location'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function labourExpenses()
    {
        return $this->hasMany(LabourExpense::class);
    }

    public function fertilizerApplications()
    {
        return $this->hasMany(FertilizerApplication::class);
    }

    public function otherExpenses()
    {
        return $this->hasMany(OtherExpense::class);
    }
}