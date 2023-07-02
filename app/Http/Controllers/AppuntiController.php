<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appunti;
use App\Models\Insegnanti;


class AppuntiController extends Controller
{
    public function lista(Request $request)
    {
        return Appunti::orderBy('updated_at', 'desc')->limit(10)->get();
    }
    

    public function insegnanti (){
        return Insegnanti::all();
    }
    public function cerca(Request $request) //utilizzare colonna params
    {
        
        $answer = Appunti::where([['titolo', 'LIKE', "%{$request->titolo}%"],
         ['materia', 'LIKE', "%{$request->materia}%"], 
         ['insegnante', 'LIKE', "%{$request->insegnante}%"]
         ])->orderBy('visualizzazioni', 'desc')->limit(10)->get();
        
        
        return ['success'=>true, 'answer'=>$answer];
    }
    public function modifica(Request $request){
        $appunto = Appunti::find($request->id);
        if($appunto['idautore']==auth('api')->user()['id']){
        $appunto->testo= $request->testo;
        $appunto->update();
        return ['success'=> true, 'appunto'=>$appunto];
        }
        return ['success'=>false, 'message'=>'Non sei stato tu a creare quest\'appunto, per questo non puoi modificarlo'];
    }

    public function visualizza(Request $request){
        $appunto = Appunti::find($request->id);
        $appunto->visualizzazioni++;
        $appunto->update();
        return ['success'=> true, 'appunto'=>$appunto];
    }
    public function crea(Request $request)
    {   
        $autore = auth('api')->user()['name'].' '.auth('api')->user()['surname'];
        Appunti::create([
            'autore' => $autore,
            'titolo' => $request->titolo,
            'testo' => $request->testo,
            'materia' =>$request->materia,
            'insegnante' => $request->insegnante,
            'idautore'=> auth('api')->user()['id']
        ]);
        return ['message' => 'Appunto creato con successo'];
    }
    public function destroy(Request $request)
    {
        // delete a post
        $appunto = Appunti::find($request->id);
        if ($appunto['idautore']==auth('api')->user()['id']) {
            $result = Appunti::destroy($request->id);
            return ['success' => true, 'message' => 'Appunto eliminato con successo'];
        }
        return ['success' => false, 'message' => 'Non sei stato tu a creare quest\'appunto, per questo non puoi eliminarlo'];
       
        
    }
}
//['autore', 'titolo', 'data', 'testo', 'materia', 'insegnante'
