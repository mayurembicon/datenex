<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesReturnDocketDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_return_docket_details', function (Blueprint $table) {
            $table->bigIncrements('srdd_id')->index();
            $table->unsignedBigInteger('sales_return_id')->index()->nullable();
            $table->string('delivery_location')->nullable();
            $table->string('courier_name')->nullable();
            $table->string('tracking_no')->nullable();
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
        Schema::dropIfExists('sales_return_docket_details');
    }
}
