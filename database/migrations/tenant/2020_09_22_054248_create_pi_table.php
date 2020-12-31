<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pi', function (Blueprint $table) {
            $table->bigIncrements('pi_id');

            $table->enum('sales_status', ['Sales Created', 'Pending'])->default('Pending');

            $table->unsignedBigInteger('financial_year_id')->index()->nullable();
            $table->unsignedBigInteger('customer_id')->index()->nullable();
            $table->unsignedBigInteger('payment_terms_id')->index()->nullable();
            $table->unsignedBigInteger('quotation_id')->index()->nullable();
            $table->string('email', 62)->nullable();
            $table->string('ref_order_no')->nullable();
            $table->date('ref_order_date')->nullable();

            $table->string('pi_no')->nullable();
            $table->string('order_no')->nullable();
            $table->date('pi_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('pi_person', 150)->nullable();
            $table->text('notes')->nullable();
            $table->double('total')->nullable();
            $table->double('pf')->nullable();
            $table->double('pf_taxrate')->nullable();
            $table->double('total_with_pf')->nullable();
            $table->double('rounding_amount')->nullable();
            $table->double('grand_total')->nullable();

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
        Schema::dropIfExists('pi');
    }
}
