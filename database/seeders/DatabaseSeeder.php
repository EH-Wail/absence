<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Eleve;
use App\Models\History;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'username' => 'zlaiga',
            'password' => Hash::make('zlaiga'),
        ]);
        
        Eleve::factory(25, ['classe' => "DDOFS"])->create();
        Eleve::factory(25, ['classe' => "INFO"])->create();
       
       
        
    }
}
