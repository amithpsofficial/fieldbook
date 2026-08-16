<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabourExpenseDetail extends Model
{
    protected $fillable = ['labour_expense_id', 'labourer_id', 'amount'];

    public function labourExpense()
    {
        return $this->belongsTo(LabourExpense::class);
    }

    public function labourer()
    {
        return $this->belongsTo(Labourer::class);
    }
}