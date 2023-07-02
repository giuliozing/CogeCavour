<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;
    protected $fillable = ['classe', 'codice', '1A', '1B', '1C', '4A', '2A', '2B','2C', '4B', '3A', '3B', '3C', '4C'];
}
