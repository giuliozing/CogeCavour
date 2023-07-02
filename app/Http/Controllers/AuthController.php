<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /*public function register() {

        return view('register');
    }
    Non ha senso: un api non ha bisogno di views
    */

    public function completeRegistration(Request $request, $id) {
        $user = User::find($id);
        if ($user) {
            $request->validate([
                'nome' => ['required', 'string', 'max:255'],
                'cognome' => ['required', 'string', 'max:255'],
                'slug' => ['required', 'string', 'max:255', 'unique:users,slug,'.$id],
                'mail' => ['required', 'string', 'max:255'],
                'password' => ['required', 'string', 'max:255']
            ]);
            $user->update($request->all());
            return  ['success'=> true, "message"=>'L\'utente è stato creato con successo.'];;
        }

        $user = new User();
        $user->user_name = $request->user_name;
        $user->email = $request->email;
        $user->password = hash('sha256', $request->password);
        $user->save();

        return redirect('/');
    }

    public function login() {

        return view('login');
    }

    public function completeLogin(Request $request) {

        $validator = Validator::make($request->all(), [

            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {

            return ['error'=> true, "message"=>'I dati forniti per l\'autenticazione non sono corretti; per favore riprovare.'];
        }

        $user = User::where('email', '=', $request->email)->where('password', '=', hash('sha256', $request->password))->first();
        if ($user === null)
            return ['error'=> true, "message"=>'Utente non trovato'];

        session(['user_name' => $user->mail]);

        return redirect('/');
    }

    public function logout(Request $request) {

        $request->session()->flush();

        return redirect('/');
    }
}
