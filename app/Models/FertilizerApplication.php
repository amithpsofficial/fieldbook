<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FertilizerApplication extends Model
{
    protected $fillable = [
        'user_id', 'land_id', 'brand_name', 'chemical_content',
        'vendor_name', 'cost', 'dosage_amount', 'dosage_unit',
        'date_applied', 'climate', 'observation'
    ];

    protected $casts = [
        'date_applied' => 'date',
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