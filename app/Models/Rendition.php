<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rendition extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $casts = [
        'aspect' => 'float',
        'coords' => 'json'
    ];
}
