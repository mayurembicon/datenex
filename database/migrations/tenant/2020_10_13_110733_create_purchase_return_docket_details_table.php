<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseReturnDocketDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_return_docket_details', function (Blueprint $table) {
            $table->bigIncrements('prdd_id')->index();
            $table->unsignedBigInteger('purchase_return_id')->index()->nullable();
            $table->string('delivery_location')->nullable();
            $table->string('courier_name')->nullable();
            $table->string('tracking_no')->nullable();
            $table->foreign('purchase_return_id')
                ->references('purchase_return_id')->on('purchase_return')
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
        Schema::dropIfExists('purchase_return_docket_details');
    }
}
