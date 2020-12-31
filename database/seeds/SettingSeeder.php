<?php

use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Setting::create(['last_indiamart_sync'=> date('Y-m-d H:i:s'),'last_tradeindia_sync'=>date('Y-m-d H:i:s')]);
    }
}
