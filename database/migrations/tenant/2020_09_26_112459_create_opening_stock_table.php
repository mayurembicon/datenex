<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpeningStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('opening_stock', function (Blueprint $table) {
            $table->bigIncrements('opening_stock_id')->index();
            $table->double('opening_stock');
            $table->unsignedBigInteger('item_id')->index()->nullable();
            $table->unsignedBigInteger('financial_year_id')->index()->nullable();
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
        Schema::dropIfExists('opening_stock');
    }
}
