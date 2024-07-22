<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProgressHistory extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'progress'];

    public function order()
    {
        return $this->belongsTo(Orders::class);
    }
}
