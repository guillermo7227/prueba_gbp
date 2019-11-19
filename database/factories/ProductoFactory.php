<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Producto;
use Faker\Generator as Faker;
use App\Bodega;

$factory->define(Producto::class, function (Faker $faker) {
    return [
        'nombre' => ucfirst($faker->word()),
        'cantidad' => $faker->numberBetween(1, 1000),
        'estado' => $faker->randomElement([0,1]),
        'observaciones' => $faker->sentence(3),
        'bodega_id' => $faker->randomElement(Bodega::all()->pluck('id')->all()),
    ];
});
