<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesReturnTransportDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_return_transport_details', function (Blueprint $table) {
            $table->bigIncrements('srtd_id')->index();
            $table->unsignedBigInteger('sales_return_id')->index()->nullable();
            $table->string('desp_through')->nullable();
            $table->string('transport_id')->nullable();
            $table->string('lorry_no')->nullable();
            $table->string('lr_no')->nullable();
            $table->string('lr_date')->nullable();
            $table->string('place_of_supply')->nullable();

            $table->foreign('sales_return_id')
                ->references('sales_return_id')->on('sales_return')
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
        Schema::dropIfExists('sales_return_transport_details');
    }
}
