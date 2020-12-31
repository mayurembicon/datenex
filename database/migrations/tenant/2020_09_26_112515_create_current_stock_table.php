<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrentStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('current_stock', function (Blueprint $table) {
            $table->bigIncrements('current_stock_id')->index();
            $table->double('current_stock');
            $table->unsignedBigInteger('item_id')->index()->nullable();
            $table->foreign('item_id')
                ->references('item_id')->on('itemmaster')
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
        Schema::dropIfExists('current_stock');
    }
}
