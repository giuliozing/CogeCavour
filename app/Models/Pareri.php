<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pareri extends Model
{
    use HasFactory;
    protected $fillable = ['idautore', 'idpetizione', 'pro'];
}

