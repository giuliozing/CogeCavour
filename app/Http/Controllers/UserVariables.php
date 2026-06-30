<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appunti;
use App\Models\Petizioni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserVariables extends Controller
{
    public function feed(){
        $utenti = User::orderBy('views', 'desc')->limit(5)->get();
        $appunti = Appunti::orderBy('created_at', 'desc')->limit(5)->get();
        $petizioni = Petizioni::orderBy('created_at', 'desc')->limit(5)->get();
        return ['success'=>true, 'utenti'=>$utenti, 'appunti'=>$appunti, 'petizioni'=>$petizioni];
    }
    public function register(Request $request)
    {
        User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => $request->status
        ]);
        return ['message' => 'User created successfully'];
    }

    public function login(Request $request) //attenzione, configurare sempre Postman senza application/json
    {
        if (strlen($request->password) > 3) {
            $credentials = request()->only(['email', 'password']); //this method allows you to select certain parametres in the request

            $token = auth('api')->attempt($credentials);

            return ['success' => true, 'token' => $token];
        }
        return ['success' => false, 'message' => 'La password è troppo breve'];
    }

    public function ricerca(Request $request)
    {
        $result = ['success' => true, 'risposta' => []];
        $rexpl = [];
        $go = false;
        $classi = ['Prim', 'Second', 'Terz', 'Quart', 'Quint'];
        $sezioni = $this->sezioni;
        if ((strlen($request->ricerca) == 1) && (in_array(strtoupper($request->ricerca), $sezioni))) { //se è stata digitata una sezione, aggiungila all'elenco
            $result['risposta'][] = ['tipo' => 'sezione', 'elem' => strtoupper($request->ricerca), 'nome' => 'Sezione ' . strtoupper($request->ricerca)];
        }
        if (
            (
                (strlen($request->ricerca) == 1) &&
                ($request->ricerca > 0) &&
                ($request->ricerca < 6)) ||
            (in_array(substr(ucfirst($request->ricerca), 0, -1), $classi))
        ) { //se è stata digitata una classe parallela, aggiungila all'elenco
            $nome = substr(ucfirst($request->ricerca), 0, -1) . 'i';
            $elem = array_search(substr(ucfirst($request->ricerca), 0, -1), $classi) + 1;
            if (strlen($request->ricerca) == 1) {
                $nome = $classi[$request->ricerca - 1] . 'i';
                $elem = $request->ricerca;
            }
            $result['risposta'][] = ['tipo' => 'classi_parallele', 'nome' => $nome, 'elem' => $elem];
        }
        $nomcogn = User::select("name", "surname", "id") //selezione per cognome
            ->where('name', 'LIKE', "%{$request->ricerca}%")
            ->orWhere('surname', 'LIKE', "%{$request->ricerca}%")
            //->orWhere('email', 'LIKE', "{$request->richiesta}%")
            ->limit($request->limite)
            ->get();
        foreach ($nomcogn as $i) {
            $result['risposta'][] = ['tipo' => 'studente', 'nome' => ucfirst($i['name']) . ' ' . ucfirst($i['surname']), 'elem' => $i['id']];
        }
        //selezione per classe
        if (strlen($request->ricerca) == 2) {
            $rexpl[0] = $request->ricerca[0];
            $rexpl[1] = $request->ricerca[1];
            $go = true;
        } else if (strlen($request->ricerca) == 3) {
            $rexpl = explode(" ", $request->ricerca);
            $go = true;
        }
        if (
            $go == true &&
            (strlen($rexpl[0] == 1) &&
                ($rexpl[0] > 0) &&
                ($rexpl[0] < 6))
            &&
            (strlen($rexpl[1]) == 1) && (in_array(strtoupper($rexpl[1]), $sezioni))
        ) {
            $result['risposta'][] = ['tipo' => 'classe', 'nome' => $rexpl[0] . ' ' . strtoupper($rexpl[1]), 'elem' => $rexpl[0] . '_' . strtoupper($rexpl[1])];
        }
        return $result;
    }

    public function mostra_classe($classe, $sezione)
    {
        $risposta = User::where([
            ['classe', '=', $classe],
            ['sezione', '=', $sezione]
        ])->orderBy('surname', 'asc')->get();
        return ['classe' => $classe . ' ' . $sezione, 'risposta' => $risposta];
    }

    public function singola_classe(Request $request)
    {

        if (strlen($request->ricerca) != 3) {
            return ['success' => false, 'message' => 'Invalid query'];
        }
        $rexpl = explode("_", $request->ricerca);
        $risposta = $this->mostra_classe($rexpl[0], $rexpl[1]);
        return ['success' => true, 'risposta' => $risposta];
    }

    public $sezioni = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];

    public function classe_parallela(Request $request)
    {
        $risposta = [];
        if (strlen($request->ricerca) != 1) {
            return ['success' => false, 'message' => 'Invalid query'];
        }
        for ($x = 0; $x < count($this->sezioni); $x++) {
            $risposta[] = $this->mostra_classe($request->ricerca, $this->sezioni[$x]);
        }

        return ['success' => true, 'risposta' => $risposta];
    }

    public function sezione(Request $request)
    {
        $risposta = [];
        if (strlen($request->ricerca) != 1) {
            return ['success' => false, 'message' => 'Invalid query'];
        }
        for ($x = 1; $x < 6; $x++) {
            $risposta[] = $this->mostra_classe($x, $request->ricerca);
        }

        return ['success' => true, 'risposta' => $risposta];
    }
    public function me()
    {
        $user = auth('api')->user();
        return $user;
    }

    public function visualizza(Request $request)
    {
        $studente = User::find($request->id);
        if ($studente) {
            $studente->views++;
            $studente->update();
            return ['success' => true, 'studente' => $studente];
        }
        return ['success' => false, 'message' => 'Utente non trovato'];
    }
    public function search_users(Request $request)
    {
        $r = [];
        $result = User::select("*")
            ->where('name', 'LIKE', "{$request->richiesta}%")
            ->orWhere('surname', 'LIKE', "{$request->richiesta}%")
            //->orWhere('email', 'LIKE', "{$request->richiesta}%")
            ->limit($request->limite)
            ->get();
        /* $result = User::where([['name', 'LIKE', "{$request->richiesta}%"]
         ])->orWhere(['surname', 'LIKE', "{$request->richiesta}%"])->limit($request->limite)->get();
*/      for($i=0;$i<count($result);$i++){
            array_push($r, $result[$i]->name.' '.$result[$i]->surname.' id: '.$result[$i]->id);
}


        //$result = User::where(['nome', 'LIKE', "{$request->query}%"])->get();
        //->orWhere(['cognome', 'LIKE', "{$request->query}%"])->orWhere((['nome'.' '.'cognome', 'LIKE', "{$request->query}%"]))
        //  ->limit(10)->get()->toArray();
        return $r;
    }

    public function update_status(Request $request)
    {

        $user = auth('api')->user();
        if ($request['status'] != '') {
            $user->status = $request['status'];
            $user->save();
            return ['success' => true, 'newstatus' => $user['status']];
        }
        return ['success' => false, 'info' => 'No status specified'];
    }

    public function init(Request $request)
    {

        $user = auth('api')->user();
        
        $user->phonenumber = $request['phone_number'];
        if($user->phonenumber==null){
            $user->phonenumber=0;
        }
        $user->preferenza = $request['preferenza'];
        $user->id_partner = $request['id_partner'];
        $user->save();

        return ['success' => true, 'phone_number' => $user['phonenumber'], 'preferenza' => $user['preferenza']];
    }
}
