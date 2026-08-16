<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabourExpense extends Model
{
    protected $fillable = [
        'user_id', 'land_id', 'expense_date', 'period_end_date',
        'entry_type', 'total_amount', 'notes'
    ];

    protected $casts = [
        'expense_date' => 'date',
        'period_end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function land()
    {
        return $this->belongsTo(Land::class);
    }

    public function details()
    {
        return $this->hasMany(LabourExpenseDetail::class);
    }
}