<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseReturnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_return_items', function (Blueprint $table) {
            $table->bigIncrements('purchase_return_items_id')->index();

            $table->unsignedBigInteger('purchase_return_id')->index()->nullable();
            $table->unsignedBigInteger('item_id')->index()->nullable();
            $table->text('p_description')->nullable();
            $table->double('qty')->default(0)->nullable();
            $table->double('rate')->default(0)->nullable();
            $table->double('discount_rate')->default(0)->nullable();
            $table->enum('discount_type', ['R', 'P'])->default('P')->nullable();
            $table->double('discount_amount')->default(0)->nullable();
            $table->double('taxable_value')->nullable();
            $table->unsignedBigInteger('taxrate')->index()->nullable();
            $table->double('gst_amount')->default(0)->nullable();
            $table->double('cgst_amount')->default(0)->nullable();
            $table->double('sgst_amount')->default(0)->nullable();
            $table->double('igst_amount')->default(0)->nullable();
            $table->double('item_total_amount')->default(0)->nullable();
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
        Schema::dropIfExists('purchase_return_items');
    }
}
