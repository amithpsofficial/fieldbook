<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CropSale extends Model
{
    protected $fillable = [
        'user_id', 'crop_id', 'land_id', 'buyer_name', 'price_per_kg',
        'weight_sold_kg', 'total_income', 'deduct_from_stock', 'sale_date'
    ];
    protected $casts = [
        'sale_date' => 'date',
        'deduct_from_stock' => 'boolean',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }
    public function land()
    {
        return $this->belongsTo(Land::class);
    }
}