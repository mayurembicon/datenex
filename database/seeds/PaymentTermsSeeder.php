<?php

use Illuminate\Database\Seeder;

class PaymentTermsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\PaymentTerms::create(['payment_terms' => '100% ADVANCE']);
        \App\PaymentTerms::create(['payment_terms' => '50% ADVANCE BALANCE AGAINST PI']);
        \App\PaymentTerms::create(['payment_terms' => '100% AGAINST DELIVERY ']);
        \App\PaymentTerms::create(['payment_terms' => '100% AFTER DELIVERY METERIAL']);
        \App\PaymentTerms::create(['payment_terms' => '100% WITHIN 30 DAYS']);
        \App\PaymentTerms::create(['payment_terms' => '100% PDC CHECK']);
    }
}
