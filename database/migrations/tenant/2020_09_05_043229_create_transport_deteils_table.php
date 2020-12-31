<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransportDeteilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transport_deteils', function (Blueprint $table) {
            $table->bigIncrements('transport_deteils_id')->index();
            $table->unsignedBigInteger('invoice_id')->index()->nullable();
            $table->unsignedBigInteger('pi_id')->index()->nullable();
            $table->string('desp_through')->nullable();
            $table->string('transport_id')->nullable();
            $table->string('lorry_no')->nullable();
            $table->string('lr_no')->nullable();
            $table->string('lr_date')->nullable();
            $table->string('place_of_supply')->nullable();
//            $table->foreign('invoice_id')
//                ->references('invoice_id')->on('invoice')
//                ->onDelete('cascade');

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
        Schema::dropIfExists('transport_deteils');
    }
}
