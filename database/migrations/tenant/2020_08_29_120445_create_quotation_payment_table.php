<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_payment', function (Blueprint $table) {
            $table->bigIncrements('payment_id')->index();
            $table->unsignedBigInteger('quotation_id')->index();
            $table->unsignedBigInteger('payment_terms_id')->index()->nullable();
            $table->unsignedBigInteger('journal')->index()->nullable();
            $table->string('term_condition')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('quotation_payment');
    }
}
