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
        
        $presence_date = Appearance::where('date', $date)
                            ->project(['_id' => 0])
                            ->first();
        if ($presence_date) {
            $seance_Index = null;
            foreach ($presence_date["exceptions"] as $index => $exception) {
                if (isset($exception["seance$request->seance"])) {
                    $seance_Index = $index;
                    break;
                }
            }
            if ($seance_Index !== null){
                $exceptions_present = [];
                $exceptions_absent = [];
                $exceptions_retard = [];
                foreach($presence_date["exceptions"][$seance_Index]["seance$request->seance"] as $exception){
                    $perso_id_exception = $exception['perso_id'];
                    if ($exception['cas'] === 'Present') {
                        $exceptions_present[] = $perso_id_exception;
                    } elseif ($exception['cas'] === 'Absent') {
                        $exceptions_absent[] = $perso_id_exception;
                    } elseif ($exception['cas'] === 'Retard') {
                        $exceptions_retard[] = $perso_id_exception;
                    }
                }
            }
            
            $eleves_present = $presence_date->presence;
            $eleves_present = Arr::where($eleves_present, function ($value , $key){
                if ( isset($value['checkIn']) &&
                    $value['checkIn']<Appearance::SEANCES[request('seance')]
                        &&
                    (array_key_exists('checkOut', $value) ? !($value['checkOut']<Appearance::SEANCES[request('seance')]) : true )){
                    return $value;
                };
            });
            // retard de 30 min , 150 min par seance
            $retard = collect($eleves_present)->filter(function ($eleve) {
                return Appearance::SEANCES[request('seance')] - $eleve['checkIn'] < 120;
            })->pluck('perso_id');

            
            
            $eleves_present = collect($eleves_present)->unique('perso_id')->toArray();
            $ids_eleves = array_column($eleves_present,"perso_id");
            $ids_eleves = Arr::map($ids_eleves, function($value, $key){
                return (int) $value;
            });
            // dd($retard);
            
            //exceptions : 
            if (isset($exceptions_present)){
                $retard = $retard->reject(function ($item) use ($exceptions_present) {
                    return in_array($item, $exceptions_present);
                });
                $ids_eleves = array_unique(array_merge($ids_eleves,$exceptions_present));
            }
            if (isset($exceptions_absent)){
                $retard = $retard->reject(function ($item) use ($exceptions_absent) {
                    return in_array($item, $exceptions_absent);
                });
                $ids_eleves = array_diff($ids_eleves, $exceptions_absent);
            }
            if (isset($exceptions_retard)){
                $retard = $retard->merge($exceptions_retard);
                $ids_eleves = array_unique(array_merge($ids_eleves,$exceptions_retard));
            }
            
            $eleves_present = Eleve::whereIn('id', $ids_eleves)->get();
        }

        $eleves = Eleve::where('classe', $filliere)->get();
        return view ('table' ,[
            "eleves" => $eleves,
            "eleves_present" => $eleves_present ?? [],
            "retard_list" => $retard ?? [],
        ]);

    }
    public function update_absence(Request $request)
    {
        $date = $request->date;
        $person_id = (int) $request->id;
        $seance = $request->seance;
        $statut_absence = Appearance::STATUT_ABSENCE[$request->statut_absence];
        // 1 => absent , 2 => present 3=> retard
        
        $document = Appearance::where('date', $date)->first();
        if ($document) {
            $exceptions = $document['exceptions'] ?? [];
        
            // verifier si exceptions existe d'abord
            $seance_Index = null;
            foreach ($exceptions as $index => $exception) {
                if (isset($exception["seance$seance"])) {
                    $seance_Index = $index;
                    break;
                }
            }
        
            if ($seance_Index === null) {
                // verifier si seance_souhaite existe d'abord sinon creer 
                $exceptions[] = [
                    "seance$seance" => [
                        [
                            "perso_id" => $person_id,
                            "cas" => $statut_absence,
                        ]
                    ]
                ];
            } else {
                //si la seance existe, faut l'update
                $seance_Exceptions = $exceptions[$seance_Index]["seance$seance"];
                $personIndex = null;
        
                // verifier si la personne existe deja
                foreach ($seance_Exceptions as $index => $seance_Exception) {
                    if ($seance_Exception['perso_id'] === $person_id) {
                        $personIndex = $index;
                        break;
                    }
                }
        
                if ($personIndex === null) {
                    // si la persoone n'existe pas , on l'ajoute
                    $seance_Exceptions[] = [
                        "perso_id" => $person_id,
                        "cas" => $statut_absence,
                    ];
                } else {
                    // si la persoone existe , on l'update
                    $seance_Exceptions[$personIndex]['cas'] = $statut_absence;
                }
        
                $exceptions[$seance_Index]["seance$seance"] = $seance_Exceptions;
            }
        
            $document['exceptions'] = $exceptions;
            $document->save();
        }
        // Appearance::where('date', $request->date)
        //     ->where('presence.perso_id', (int) $request->id)
        //     ->update(
            
        //     [
        //         '$set' => [
        //             'presence.$[element].exceptions.$[index].seance' => (int) $request->seance,
        //             'presence.$[element].exceptions.$[index].cas' => Appearance::STATUT_ABSENCE[$request->seance]
        //         ]
        //     ],
        //     [
        //         'arrayFilters' => [
        //             ['element.perso_id' => (int) $request->id],
        //             ['index.seance' => (int) $request->seance]
        //         ],
        //         'upsert' => true
        //     ]
        // );
        return 1;
        
    }
}
