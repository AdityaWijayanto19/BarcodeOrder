<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'qrcode'
    ];
    protected $guarded = [];
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
