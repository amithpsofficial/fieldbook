<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function lands()
    {
        return $this->hasMany(Land::class);
    }

    public function labourers()
    {
        return $this->hasMany(Labourer::class);
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

    public function crops()
    {
        return $this->hasMany(Crop::class);
    }

    public function cropStocks()
    {
        return $this->hasMany(CropStock::class);
    }

    public function cropSales()
    {
        return $this->hasMany(CropSale::class);
    }

    public function setting()
    {
        return $this->hasOne(Setting::class);
    }
}