<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journal', function (Blueprint $table) {
            $table->bigIncrements('journal_id')->index();
            $table->enum('type',['C','D'])->nullable();
            $table->enum('transaction_type',['B','C'])->nullable();
            $table->unsignedBigInteger('financial_year_id')->index()->nullable();
            $table->date('date')->nullable();
//            $table->double('total')->nullable();
            $table->double('grand_total')->nullable();
            $table->text('description')->nullable();
            $table->integer('transaction_id')->index()->nullable();
            $table->bigInteger('customer_id')->index()->nullable();
            $table->enum('ref_type',['PU','SL','PY','RC','SR','PR'])->nullable();
//            $table->enum('customer_type',['customer','vendor','both'])->nullable();
            $table->bigInteger('created_id')->nullable();
            $table->bigInteger('updated_id')->nullable();
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
        Schema::dropIfExists('journal');
    }
}
