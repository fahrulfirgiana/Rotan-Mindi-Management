<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Orders extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name', 'size', 'quantity', 'price', 'total_price', 
        'deadline', 'progress', 'subkontraktor_name', 'image', 'status'
    ];

    public function progressHistories()
    {
        return $this->hasMany(OrderProgressHistory::class, 'order_id'); // Menyebutkan kolom kunci asing secara eksplisit
    }
}
