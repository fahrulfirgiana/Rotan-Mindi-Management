<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'subcontractor_id', 'bahan', 'kuintal',
    ];

    public function subcontractor()
    {
        return $this->belongsTo(Subcontractors::class);
    }
}
