<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ImageUpload;

class Article extends Model
{
    use HasFactory;
    use ImageUpload;

    protected $fillable = [
        'body',
    ];
}
