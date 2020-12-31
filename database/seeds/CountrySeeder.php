<?php

use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Country::create(['country_name'=>'india']);
        \App\Country::create(['country_name'=>'london']);
        \App\Country::create(['country_name'=>'us']);
        \App\Country::create(['country_name'=>'japan']);
    }
}
