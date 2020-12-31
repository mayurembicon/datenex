<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Customer;
use Faker\Generator as Faker;

$factory->define(Customer::class, function (Faker $faker) {
    return [


        'company_name'=>$faker->unique()->firstName,
        'customer_name'=>$faker->unique()->name,
        'f_phone_no'=>$faker->biasedNumberBetween(11111111111),
        'email'=>$faker->unique()->email



    ];
});
