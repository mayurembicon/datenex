<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationShippingAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_shipping_address', function (Blueprint $table) {
            $table->bigIncrements('shipping_address_id')->index();
            $table->unsignedBigInteger('quotation_id')->index()->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->string('city_name',50)->nullable();
            $table->string('shipping_pincode',11)->nullable();
            $table->text('shipping_address')->nullable();
            $table->foreign('quotation_id')
                ->references('quotation_id')->on('quotation')
                ->onDelete('cascade');
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
        Schema::dropIfExists('quotation_shipping_address');
    }
}
