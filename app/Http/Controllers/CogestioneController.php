<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Corso;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CogestioneController extends Controller
{
    public function corsiClasse(Request $request)
    {
        $classe = Classe::where('classe', $request->classe)->first();

        if (isset($classe))
            return $classe;
        else
            return 'Class not found';
    }

    public function modificaPiano(Request $request)
    {
        $classe = Classe::where('codice', $request->response['codice'])->first();
        if(!$classe){
            return "Error";
        }
       
            $classe['1A'] = $request->response['A1'];

       
            $classe['2A'] = $request->response['A2'];

      
            $classe['3A'] = $request->response['A3'];

     
            

       
            $classe['4A'] = $request->response['A4'];

            $classe['1B'] = $request->response['B1'];

            $classe['2B'] = $request->response['B2'];

        
            $classe['3B'] = $request->response['B3'];

            $classe['4B'] = $request->response['B4'];

            $classe['1C'] = $request->response['C1'];

            $classe['2C'] = $request->response['C2'];

            $classe['3C'] = $request->response['C3'];

            $classe['4C'] = $request->response['C4'];

        $classe->save();

        return 'Success';
    }

    public function tuttiICorsi()
    {
        return Corso::all();
    }

    public function creaCorso(Request $request)
    {

        $validator = Validator::make($request->values, [
            'titolo' => 'required|unique:corsos,titolo',
            'descrizione' => 'required|unique:corsos,descrizione',
            'name' => 'required',
            'email' => 'required',
            'date' => ['required', Rule::in(['25', '26', '27'])],
            'time' => ['required', Rule::in(['9:00-1', '9:00-2', '10:00-1', '11:30-1', '11:30-2', '12:30-1'])],
            'sex' => 'required',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $corso = new Corso;
        $corso->titolo = $request->values['titolo'];
        $corso->descrizione = $request->values['descrizione'];
        $corso->nome_cognome_responsabile = $request->values['name'];
        $corso->email_responsabile = $request->values['email'];
        if (isset($request->values['ospiti']))
            $corso->ospiti = $request->values['ospiti'];

        $corso->giorno = $request->values['date'];
        $corso->ora = $request->values['time'];
        $corso->materia_sessuale = $request->values['sex'];
        $corso->save();

        return 'Success';
    }
}
