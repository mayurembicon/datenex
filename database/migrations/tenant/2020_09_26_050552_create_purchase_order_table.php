<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order', function (Blueprint $table) {
            $table->bigIncrements('po_id');

            $table->enum('po_status',['Purchase Created','Pending'])->default('Pending');

            $table->unsignedBigInteger('financial_year_id')->index()->nullable();
            $table->unsignedBigInteger('customer_id')->index()->nullable();
            $table->unsignedBigInteger('payment_terms_id')->index()->nullable();
            $table->string('email',62)->nullable();
            $table->string('place_of_supply')->nullable();

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
        Schema::dropIfExists('purchase_order');
    }
}
