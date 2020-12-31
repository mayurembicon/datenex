<?php

use Illuminate\Database\Seeder;

class TaxRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\TaxRate::create(['tax_rate' => '5']);
        \App\TaxRate::create(['tax_rate' => '6']);
        \App\TaxRate::create(['tax_rate' => '12']);
        \App\TaxRate::create(['tax_rate' => '18']);
        \App\TaxRate::create(['tax_rate' => '28']);
    }
}
