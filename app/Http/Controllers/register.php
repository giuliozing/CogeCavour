<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class register extends Controller
{
    public function check_code(Request $request){

        $users = User::whereIn('register_code', [$request['code']])
            ->get();
        if(count($users)==1){
            $account=$users[0];
            $account->password = Hash::make($request->password);
            $account->status = $request['status'];
            $account->save();
            return ['success'=>true,'user'=>$account];
        }
        else if(count($users)>1){
            return ['success'=>false, 'message'=>'Più utenti corrispondono al codice che hai inserito. Questo è un errore molto grave, contatta subito il gestore del sito', 'numero occorrenze'=>count($users)];
        }
        return ['success'=>false, 'message'=>'Nessun utente corrisponde a questo link'];
    }
}