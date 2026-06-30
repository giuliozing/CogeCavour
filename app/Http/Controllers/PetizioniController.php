<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Petizioni;
use App\Models\Pareri;


class PetizioniController extends Controller
{
    public function crea(Request $request)
    {   
        $autore = auth('api')->user()['name'].' '.auth('api')->user()['surname'];
        Petizioni::create([
            'autore' => $autore,
            'titolo' => $request->titolo,
            'testo' => $request->testo,
            'idautore'=> auth('api')->user()['id']
        ]);
        return ['message' => 'Petizione creata con successo'];
    }
    public function lista(Request $request)
    {
        return Petizioni::orderBy('created_at', 'desc')->limit($request->limite)->get();
    }
    public function visualizza(Request $request){
        $petizione = Petizioni::find($request->id)or die("L'identificativo indicato non esiste");
        $petizione->visualizzazioni++;
        $petizione->update();
        $pros = Pareri::where([['pro', '=', 1],['idpetizione', '=', $request->id]
        ])->get();
        $cons = Pareri::where([['pro', '=', 0],['idpetizione', '=', $request->id]
        ])->get();
        $answer = Pareri::where([['idautore', '=', auth('api')->user()['id']],
        ['idpetizione', '=', $request->id]
        ])->limit(1)->get();
        if(count($answer)==1){
            $aggiungiparere = $answer;
        }
        else{
            
            $aggiungiparere = null;
        }
        
        return ['success'=> true, 'petizione'=>$petizione, 'parere'=>$aggiungiparere, 'pro'=>count($pros), 'contro'=>count($cons)];
    }
    public function imposta_parere(Request $request){
        $answer = Pareri::where([['idautore', '=', auth('api')->user()['id']],
        ['idpetizione', '=', $request->id]
        ])->limit(1)->get();
        $petizione = Petizioni::find($request->id);
        if(count($answer)==0 && $petizione){
            Pareri::create([
                'idautore' => auth('api')->user()['id'],
                'idpetizione'=>$request->id,
                'pro'=>$request->pro
                ]);
                return ['success'=>true];
            }
        return ['success'=>false, 'message'=>'Hai già votato o la petizione che volevi votare non esiste'];
        
    }

}
