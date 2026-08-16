<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CropStock extends Model
{
    protected $fillable = [
        'user_id', 'crop_id', 'land_id', 'type', 'quantity_kg', 'stock_date', 'notes'
    ];
    protected $casts = [
        'stock_date' => 'date',
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