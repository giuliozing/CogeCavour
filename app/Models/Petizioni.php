<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petizioni extends Model
{
    use HasFactory;
    protected $fillable = ['autore', 'titolo', 'data', 'testo', 'idautore'];
    protected $casts = ['visualizzazioni' => 'integer', 'pro'=>'integer', 'contro'=>'integer'];
}
