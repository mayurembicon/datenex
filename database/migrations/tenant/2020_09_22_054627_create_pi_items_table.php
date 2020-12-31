<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePiItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pi_items', function (Blueprint $table) {
            $table->bigIncrements('pi_items_id')->index();
            $table->unsignedBigInteger('pi_id')->index()->nullable();
            $table->unsignedBigInteger('item_id')->index()->nullable();
            $table->text('p_description')->nullable();

            $table->double('qty')->default(0)->nullable();
            $table->double('rate')->default(0)->nullable();
            $table->double('discount_rate')->default(0)->nullable();
            $table->enum('discount_type', ['R', 'P'])->default('P')->nullable();
            $table->double('discount_amount')->default(0)->nullable();
            $table->double('taxable_value')->nullable()->nullable();
            $table->unsignedBigInteger('taxrate')->index()->nullable();
            $table->double('gst_amount')->default(0)->nullable();
            $table->double('cgst_amount')->default(0)->nullable();
            $table->double('sgst_amount')->default(0)->nullable();
            $table->double('igst_amount')->default(0)->nullable();
            $table->double('item_total_amount')->default(0)->nullable();

            $table->foreign('pi_id')
                ->references('pi_id')->on('pi')
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
        Schema::dropIfExists('pi_items');
    }
}
