<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationProductDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_product_detail', function (Blueprint $table) {
            $table->bigIncrements('product_detail_id')->index();
            $table->unsignedBigInteger('item_id')->index()->nullable();
            $table->unsignedBigInteger('quotation_id')->index()->nullable();
            $table->text('p_description')->nullable();
            $table->double('qty')->nullable();
            $table->double('rate')->nullable();
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
            $table->foreign('quotation_id')
                ->references('quotation_id')->on('quotation')
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
        Schema::dropIfExists('quotation_product_detail');
    }
}
