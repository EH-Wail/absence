<?php

namespace Database\Factories;

use App\Models\Eleve;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Eleve>
 */
class EleveFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        static $number = 1;
        return [
            "id" => $number++,
            "nom" => fake()->lastName(),
            "prenom" => fake()->firstName(),
            "classe" => "DDOFS",
            "img_path" => "",
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Eleve $eleve){
            $eleve->img_path = strval($eleve->id) . ".png";
            $eleve->save();
        });
    }
}
