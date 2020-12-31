<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesReturnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_return', function (Blueprint $table) {
            $table->bigIncrements('sales_return_id')->index();
            $table->unsignedBigInteger('financial_year_id')->index()->nullable();
            $table->unsignedBigInteger('customer_id')->index()->nullable();
            $table->string('original_invoice_no')->nullable();
            $table->date('sales_return_date')->nullable();
            $table->date('original_invoice_date')->nullable();
            $table->double('total')->nullable();
            $table->double('pf')->nullable();
            $table->double('pf_taxrate')->nullable();
            $table->double('total_with_pf')->nullable();
            $table->double('rounding_amount')->nullable();
            $table->double('grand_total')->nullable();
            $table->text('notes')->nullable();

            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->string('invoice_no')->nullable();

            $table->unsignedBigInteger('created_id')->nullable();
            $table->unsignedBigInteger('updated_id')->nullable();
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
        Schema::dropIfExists('sales_return');
    }
}
