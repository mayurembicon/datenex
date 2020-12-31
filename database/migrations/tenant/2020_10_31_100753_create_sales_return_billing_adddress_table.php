<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesReturnBillingAdddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_return_billing_adddress', function (Blueprint $table) {
            $table->bigIncrements('sr_ba_id')->index();
            $table->unsignedBigInteger('sales_return_id')->index();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->string('city_name',50)->nullable();
            $table->string('zip_code',11)->nullable();
            $table->text('address')->nullable();
            $table->enum('shipping_same_as_billing',['Y','N'])->nullable();;
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
        Schema::dropIfExists('sales_return_billing_adddress');
    }
}
