<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserVariables;
use App\Http\Controllers\AppuntiController;
use App\Http\Controllers\CogestioneController;
use App\Http\Controllers\PetizioniController;
use App\Http\Controllers\register;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/cogestione/corsi_classe', [CogestioneController::class, 'corsiClasse']);
Route::put('/cogestione/modifica_piano', [CogestioneController::class, 'modificaPiano']);
Route::post('/cogestione/crea_corso', [CogestioneController::class, 'creaCorso']);
Route::get('/cogestione/tutti_i_corsi', [CogestioneController::class, 'tuttiICorsi']);
Route::post('/cogestione/modifica_prospetto', [CogestioneController::class, 'modificaProspetto']);

Route::get('/', function () {
    return ['prova' => [['slug' => '1', 'name' => 'a'], ['slug' => '2', 'name' => 'b']]];
});
Route::post('/login', [UserVariables::class, 'login'])->name('login');
Route::post('/register', [register::class, 'check_code']);
Route::middleware('jwt.auth')->get('/ottieni_appunti', [AppuntiController::class, 'lista']);
Route::middleware('jwt.auth')->get('/cerca_appunti', [AppuntiController::class, 'cerca']);
Route::middleware('jwt.auth')->get('/visualizza_appunto', [AppuntiController::class, 'visualizza']);
Route::middleware('jwt.auth')->put('/modifica_appunto', [AppuntiController::class, 'modifica']);
Route::middleware('jwt.auth')->post('/crea_appunto', [AppuntiController::class, 'crea']);
Route::middleware('jwt.auth')->delete('/elimina_appunto', [AppuntiController::class, 'destroy']);
Route::middleware('jwt.auth')->get('me', [UserVariables::class, 'me']);
Route::middleware('jwt.auth')->put('update_status', [UserVariables::class, 'update_status']);
Route::post('/register-debug', [UserVariables::class, 'register']);
Route::middleware('jwt.auth')->get('/insegnanti', [AppuntiController::class, 'insegnanti'] );
Route::middleware('jwt.auth')->get('/feed', [UserVariables::class, 'feed'] );


//14 segreto
/*Route::get('createRandomUsers', function () { //Debug

    for ($i = 0; $i < 100; $i++) {

        if (rand(0, 1) == 0) {

            $user = new User;
            $user->name = 'a';
            $user->surname = 'a';
            $user->email = 'a';
            $user->password = 'a';
            $user->status = 'a';
            $user->phonenumber = 'a';
            $user->sesso = 'F';
            $user->preferenza = 'F';
            $user->register_code = 'a';
            $user->id_partner = rand(1, 100);
            $user->save();
        } else {

            $user = new User;
            $user->name = 'a';
            $user->surname = 'a';
            $user->email = 'a';
            $user->password = 'a';
            $user->status = 'a';
            $user->phonenumber = 'a';
            $user->sesso = (rand(0, 1) == 0) ? ('M') : ('F');
            $user->preferenza = (rand(0, 1) == 0) ? ('M') : ('F');
            $user->register_code = 'a';
            $user->id_partner = 0;
            $user->save();
        }
    }
});*/
Route::middleware('jwt.auth')->post('init', [UserVariables::class, 'init']); //Aggiunge dati all'account mettendolo in attesa per il matching
Route::middleware('jwt.auth')->get('svi', function () {

    $user = auth('api')->user();

    $matchUser = User::select("*")
    ->where('id', '=', "{$user->match}")
    ->get();
    
    //$userSel = User::find($user->id_partner);
    
    if(count($matchUser)>0){
        return ['matchPn' => $matchUser->phonenumber, 'matchEmail' => $matchUser->email, 'matchUser' => $matchUser->name, 'phonenumber' => $pn, 'sesso' => $user->sesso, 'preferenza' => $user->preferenza];
    }
    return ['success'=>false, 'message'=>'ancora nessun match'];
    
});
Route::get('start', function () { //Inizia il processo di matching il 14/02

    if (false) {

        $matchedIP = []; //Tutti i match per partner
        $notMatchedIP = [];

        $usersIP = User::where('id_partner', '>', 0)->get()->toArray();
        $done = [];
        foreach ($usersIP as $user) {

            $found = false;
            foreach ($usersIP as $check) {

                if ($user['id'] == $check['id_partner'] && $user['id_partner'] == $check['id']) {

                    if (!in_array($user['id'], $done) && !in_array($check['id'], $done)) {

                        array_push($matchedIP, [$user, $check]);
                        array_push($done, $user['id']);
                        array_push($done, $check['id']);
                    }

                    $found = true;
                }

                if ($found)
                    break;
            }

            if (!$found)
                array_push($notMatchedIP, $user);
        }

        $matchedRandom = []; //Tutti i match random

        $usersMM = User::where('id_partner', 0)->where('sesso', 'M')->where('preferenza', 'M')->get()->toArray();
        $usersMF = User::where('id_partner', 0)->where('sesso', 'M')->where('preferenza', 'F')->get()->toArray();
        $usersFF = User::where('id_partner', 0)->where('sesso', 'F')->where('preferenza', 'F')->get()->toArray();
        $usersFM = User::where('id_partner', 0)->where('sesso', 'F')->where('preferenza', 'M')->get()->toArray();

        foreach ($notMatchedIP as $user) {

            ($user['sesso'] == 'M' && $user['preferenza'] == 'F') ? (array_push($usersMF, $user)) : (false);
            ($user['sesso'] == 'F' && $user['preferenza'] == 'M') ? (array_push($usersFM, $user)) : (false);
            ($user['sesso'] == 'F' && $user['preferenza'] == 'F') ? (array_push($usersFF, $user)) : (false);
            ($user['sesso'] == 'M' && $user['preferenza'] == 'M') ? (array_push($usersMM, $user)) : (false);
        }

        while (count($usersMF) > 0 && count($usersFM) > 0) {

            $randomIndex = [rand(0, count($usersMF) - 1), rand(0, count($usersFM) - 1)];
            array_push($matchedRandom, [$usersMF[$randomIndex[0]], $usersFM[$randomIndex[1]]]);
            array_splice($usersMF, $randomIndex[0], 1);
            array_splice($usersFM, $randomIndex[1], 1);
        }

        while (count($usersFF) > 1) {

            $randomIndex = [rand(0, count($usersFF) - 1), rand(0, count($usersFF) - 1)];
            while ($randomIndex[0] == $randomIndex[1])
                $randomIndex = [rand(0, count($usersFF) - 1), rand(0, count($usersFF) - 1)];
            array_push($matchedRandom, [$usersFF[$randomIndex[0]], $usersFF[$randomIndex[1]]]);
            array_splice($usersFF, $randomIndex[0], 1);
            if ($randomIndex[1] < $randomIndex[0])
                array_splice($usersFF, $randomIndex[1], 1);
            else
                array_splice($usersFF, $randomIndex[1] - 1, 1);
        }

        while (count($usersMM) > 1) {

            $randomIndex = [rand(0, count($usersMM) - 1), rand(0, count($usersMM) - 1)];
            while ($randomIndex[0] == $randomIndex[1])
                $randomIndex = [rand(0, count($usersMM) - 1), rand(0, count($usersMM) - 1)];
            array_push($matchedRandom, [$usersMM[$randomIndex[0]], $usersMM[$randomIndex[1]]]);
            array_splice($usersMM, $randomIndex[0], 1);
            if ($randomIndex[1] < $randomIndex[0])
                array_splice($usersMM, $randomIndex[1], 1);
            else
                array_splice($usersMM, $randomIndex[1] - 1, 1);
        }

        foreach ($matchedIP as $match) {

            $userM = User::findOrFail($match[0]['id']);
            $userM->match = $match[1]['id'];
            $userM->save();

            $userM = User::findOrFail($match[1]['id']);
            $userM->match = $match[0]['id'];
            $userM->save();
        }

        foreach ($matchedRandom as $match) {

            $userM = User::findOrFail($match[0]['id']);
            $userM->match = $match[1]['id'];
            $userM->save();

            $userM = User::findOrFail($match[1]['id']);
            $userM->match = $match[0]['id'];
            $userM->save();
        }

        return [$matchedIP, $matchedRandom];
    }
});

Route::post('/register-debug', [UserVariables::class, 'register']);
Route::middleware('jwt.auth')->get('/insegnanti', [AppuntiController::class, 'insegnanti']);

Route::middleware('jwt.auth')->get('/studenti', [UserVariables::class, 'search_users']);
Route::middleware('jwt.auth')->get('/ricerca', [UserVariables::class, 'ricerca']);
Route::middleware('jwt.auth')->get('/vedi_studente', [UserVariables::class, 'visualizza']);
Route::middleware('jwt.auth')->get('/vedi_classe', [UserVariables::class, 'singola_classe']);
Route::middleware('jwt.auth')->get('/vedi_classeparallela', [UserVariables::class, 'classe_parallela']);
Route::middleware('jwt.auth')->get('/vedi_sezione', [UserVariables::class, 'sezione']);
Route::middleware('jwt.auth')->get('/crea_petizione', [PetizioniController::class, 'crea']);
Route::middleware('jwt.auth')->get('/lista_petizioni', [PetizioniController::class, 'lista']);
Route::middleware('jwt.auth')->get('/vedi_petizione', [PetizioniController::class, 'visualizza']);
Route::middleware('jwt.auth')->post('/vota_petizione', [PetizioniController::class, 'imposta_parere']);
