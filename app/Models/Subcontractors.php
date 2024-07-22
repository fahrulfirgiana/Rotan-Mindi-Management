<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcontractors extends Model
{
    protected $fillable = ['subkontraktor_name', 'contact', 'employee', ];

    public function materials()
    {
        return $this->hasMany(Material::class, 'subcontractor_id');
    }
}
