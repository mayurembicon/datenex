<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_address', function (Blueprint $table) {
            $table->bigIncrements('customer_address_id')->index();
            $table->unsignedBigInteger('customer_id')->index()->nullable();
            $table->string('billing_attention')->nullable();
            $table->unsignedBigInteger('country_id')->index()->nullable();
            $table->unsignedBigInteger('state_id')->index()->nullable();
            $table->string('billing_address1')->nullable();
            $table->string('billing_address2')->nullable();
            $table->string('billing_address3')->nullable();
            $table->integer('billing_pincode')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_distinct')->nullable();

            $table->string('shipping_attention')->nullable();
            $table->unsignedBigInteger('shipping_country_id')->index()->nullable();
            $table->unsignedBigInteger('shipping_state_id')->index()->nullable();
            $table->string('shipping_address1')->nullable();
            $table->string('shipping_address2')->nullable();
            $table->string('shipping_address3')->nullable();
            $table->integer('shipping_pincode')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_distinct')->nullable();
            $table->foreign('customer_id')
                ->references('customer_id')->on('customer')
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
        Schema::dropIfExists('customer_address');
    }
}
