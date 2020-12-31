<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerOpeningBalanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_opening_balance', function (Blueprint $table) {
            $table->bigIncrements('customer_opening_balance_id')->index();
            $table->integer('opening_balance')->nullable();
            $table->enum('opening_balance_type',['C','D'])->nullable();
            $table->unsignedBigInteger('customer_id')->index()->nullable();
            $table->unsignedBigInteger('financial_year_id')->index()->nullable();
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
        Schema::dropIfExists('customer_opening_balance');
    }
}
