<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    protected $fillable = ['name', 'surname', 'email', 'password', 'status', 'classe', 'sezione', 'register_code', 'phonenumber', 'preferenza', 'sesso', 'id_partner', 'match', 'phonenumber'];

    protected $casts = ['views' => 'integer'];
    public function getJWTIdentifier(){
        return $this->getKey();
    }
    public function getJWTCustomClaims(){
        return[];
    }
}
