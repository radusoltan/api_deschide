<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['title', 'author', 'description'];

    public function toSearchArray(): array
    {
        return [
            'author' => $this->author_id
        ];
    }
}
