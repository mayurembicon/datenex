<?php

use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\City::create(['city_name'=>'Rajkot']);
        \App\City::create(['city_name'=>'Morbi']);
        \App\City::create(['city_name'=>'Mumbai']);
        \App\City::create(['city_name'=>'Vadodara']);
    }
}
