<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocketDeteilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docket_deteils', function (Blueprint $table) {
            $table->bigIncrements('docket_deteils_id')->index();
            $table->unsignedBigInteger('invoice_id')->index()->nullable();
            $table->unsignedBigInteger('pi_id')->index()->nullable();
            $table->string('delivery_location')->nullable();
            $table->string('courier_name',150)->nullable();
            $table->string('tracking_no',50)->nullable();
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
        Schema::dropIfExists('docket_deteils');
    }
}
