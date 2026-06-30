<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appunti extends Model
{
    use HasFactory;
    protected $fillable = ['autore', 'titolo', 'data', 'testo', 'materia', 'insegnante', 'idautore'];
    protected $casts = ['visualizzazioni' => 'integer'];
}
