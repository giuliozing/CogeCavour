<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'slug', 'likes', 'content'];
    protected $casts = ['likes' => 'integer'];
    // protected $hidden = ['created_at'];

    use HasFactory;
}
