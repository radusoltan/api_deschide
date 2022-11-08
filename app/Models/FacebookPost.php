<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacebookPost extends Model
{
    use HasFactory;

    protected $fillable = ['old_num', 'fb_id', 'title'];
}
