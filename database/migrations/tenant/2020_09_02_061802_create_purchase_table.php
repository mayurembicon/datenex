<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase', function (Blueprint $table) {
            $table->bigIncrements('purchase_id')->index();
            $table->unsignedBigInteger('financial_year_id')->index()->nullable();
            $table->unsignedBigInteger('po_id')->index()->nullable();
            $table->unsignedBigInteger('customer_id')->index()->nullable();
            $table->unsignedBigInteger('payment_terms_id')->index()->nullable();
            $table->integer('bill_no')->nullable();
            $table->integer('order_no')->nullable();
            $table->date('bill_date')->nullable();
            $table->date('due_date')->nullable();
            $table->text('notes')->nullable();
            $table->double('total')->nullable();
            $table->double('pf')->nullable();
            $table->double('pf_taxrate')->nullable();
            $table->double('total_with_pf')->nullable();
            $table->double('rounding_amount')->nullable();
            $table->double('grand_total')->nullable();
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
        Schema::dropIfExists('purchase');
    }
}
