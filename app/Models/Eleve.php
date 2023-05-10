<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Eleve extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $keyType = 'int';



}
