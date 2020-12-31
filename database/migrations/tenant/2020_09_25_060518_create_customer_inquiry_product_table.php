<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerInquiryProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_inquiry_product', function (Blueprint $table) {
            $table->bigIncrements('customer_inquiry_product_id')->index();
            $table->unsignedBigInteger('customer_inquiry_id')->index()->nullable();
            $table->string('item_name',150)->nullable();
            $table->text('p_description')->nullable();
            $table->double('qty')->default(0)->nullable();


            $table->foreign('customer_inquiry_id')
                ->references('customer_inquiry_id')->on('customer_inquiry')
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
        Schema::dropIfExists('customer_inquiry_product');
    }
}
