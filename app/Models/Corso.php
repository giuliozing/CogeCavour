<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Corso extends Model
{
    use HasFactory;

    protected $fillable = ['email_responsabile', 'titolo', 'descrizione', 'nome_cognome_responsabile', 'ospiti', 'giorno', 'orai','oraf', 'materia_sessuale'];
}
