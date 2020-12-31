<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create(['name' => 'Parag Lashkari', 'role' => 'Super ', 'email' => 'super@embicon.in', 'password' => bcrypt('embicon@2020')]);

    }

}
