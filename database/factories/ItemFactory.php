<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Item;
use App\Model;
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'taxrate'=>$faker->numberBetween($min = 0.5, $max = 28),
        'sale_rate' => $faker->numberBetween($min = 1000, $max = 9000),
        'purchase_rate' => $faker->numberBetween($min = 1000, $max = 9000),
        'discount_amount' => $faker->numberBetween($min = 5, $max = 50),
        'descripation' => $faker->unique()->text,




    ];
});
