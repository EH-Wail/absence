<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Appearance extends Model
{
    use HasFactory;
    // nom du collection dans la base de données n'est pas Appearances, alors on définit : 
    protected $collection = 'appearance';

    protected $guarded = [];
    
    // $fillieres = Eleve::raw()->distinct('classe');
    public const FILLIERES = [
        'DDOFS' => 'Développement Digital',
        'INFO' => 'Infographie'
    ];

    public const SEANCES_STRING = [
        "1" => "séance 1",
        "2" => "séance 2",
        "3" => "séance 3",
        "4" => "séance 4",
    ];

    public const SEANCES = [
        1 => 660,
        2 => 780,
        3 => 960,
        4 => 1110,
    ];
    public function presences ()
    {
        return $this->embedsMany(Presence::class);
    }
}
