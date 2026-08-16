<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtherExpense extends Model
{
    protected $fillable = [
        'user_id', 'land_id', 'expense_type', 'amount', 'expense_date', 'notes'
    ];

    protected $casts = [
        'expense_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function land()
    {
        return $this->belongsTo(Land::class);
    }
}