<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('followup', function (Blueprint $table) {
            $table->bigIncrements('followup_id')->index();
            $table->unsignedBigInteger('inquiry_id')->index()->nullable();
            $table->unsignedBigInteger('quotation_id')->index()->nullable();
            $table->unsignedBigInteger('pi_id')->index()->nullable();
            $table->unsignedBigInteger('o_id')->index()->nullable();
            $table->unsignedBigInteger('c_i_id')->index()->nullable();
            $table->text('remark')->nullable();
            $table->date('next_followup_date')->index()->nullable();

            $table->bigInteger('created_id')->nullable();


//            $table->foreign('quotation_id')
//                ->references('quotation_id')->on('quotation')
//                ->onDelete('cascade');
//
//            $table->foreign('inquiry_id')
//                ->references('inquiry_id')->on('inquiry')
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
        Schema::dropIfExists('followup');
    }
}
