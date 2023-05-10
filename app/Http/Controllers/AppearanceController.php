<?php

namespace App\Http\Controllers;

use App\Models\Appearance;
use App\Models\Eleve;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class AppearanceController extends Controller
{
    public function index (Request $request)
    {
        $request->validate([
            'filliere' => 'required_with:date|string',
            'date' => 'required_with:filliere',
            'seance' => 'required_with:filliere',
        ],[
            'filliere.required' => 'Choisisser une filliere',
            'date' => 'Selectionner une date'
        ]);
        $date = $request->date;
        $filliere  = $request->filliere;
        
     
        $eleves_present = Appearance::where('date', $date)
                    ->project(['_id' => 0])
                    ->first()
                    ?->presence;
        // dd(Appearance::SEANCES[request('seance')]);
        $eleves_present = Arr::where($eleves_present, function ($value , $key){
            return $value['checkIn']<Appearance::SEANCES[request('seance')] && 
            (array_key_exists('checkOut', $value) ? !($value['checkOut']<Appearance::SEANCES[request('seance')]) : true );
        });
        // dd($eleves_present);
        // $retard = Arr::map($eleves_present, function ($value, $key){
        //     // retard de 30 min , 150 min par seance
        //         $retard[$key] = Appearance::SEANCES[request('seance')]-$value['checkIn']<120;
        //     return $retard;
        // });
        $retard = collect($eleves_present)->mapWithKeys(function ($eleve , $key){
            return [$eleve['perso_id'] => Appearance::SEANCES[request('seance')]-$eleve['checkIn']<120];
        });
        $eleves_present = collect($eleves_present)->unique('perso_id')->toArray();
        $ids_eleves = array_column($eleves_present,"perso_id");
        $eleves_present = Eleve::whereIn('id', $ids_eleves)->get();
        $eleves = Eleve::where('classe', $filliere)->get();
        return view ('table' ,[
            "eleves" => $eleves,
            "eleves_present" => $eleves_present,
            "retard_list" => $retard,
        ]);

    }
}
