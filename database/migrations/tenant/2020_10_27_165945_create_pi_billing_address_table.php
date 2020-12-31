<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePiBillingAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pi_billing_address', function (Blueprint $table) {
            $table->bigIncrements('pi_ba_id')->index();
            $table->unsignedBigInteger('pi_id')->index();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->string('city_name',50)->nullable();
            $table->string('zip_code',11)->nullable();
            $table->text('address')->nullable();
            $table->enum('shipping_same_as_billing',['Y','N'])->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pi_billing_address');
    }
}
