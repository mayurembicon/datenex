<?php

use Illuminate\Database\Seeder;

class StateSedder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\State::create(['state_name'=>'Gujarat']);
        \App\State::create(['state_name'=>'Andaman and Nicobar Islands']);
        \App\State::create(['state_name'=>'Andhra Pradesh']);
        \App\State::create(['state_name'=>'Arunachal Pradesh']);
        \App\State::create(['state_name'=>' Assam']);
    }
}
