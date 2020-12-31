<?php

use App\Financial_Year;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class FinancialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\FinancialYear::create([
            'start_date' => '2020-04-01',
            'end_date' => '2021-03-31',
            'current_year' => date('Y', strtotime('2020-04-01')) . '-' . date('Y', strtotime('2021-03-31')),
            'is_default' => 'Y',
        ]);
        \App\FinancialYear::create([
            'start_date' => '2021-04-01',
            'end_date' => '2022-03-31',
            'current_year' => date('Y', strtotime('2021-04-01')) . '-' . date('Y', strtotime('2022-03-31')),
            'is_default' => 'N',
        ]);
        \App\FinancialYear::create([
            'start_date' => '2022-04-01',
            'end_date' => '2023-03-31',
            'current_year' => date('Y', strtotime('2022-04-01')) . '-' . date('Y', strtotime('2023-03-31')),
            'is_default' => 'N',
        ]);
    }
}
