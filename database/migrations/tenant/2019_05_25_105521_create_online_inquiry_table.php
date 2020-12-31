<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnlineInquiryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_inquiry', function (Blueprint $table) {
            $table->bigIncrements('o_id');
            $table->enum('inquiry_from', ['TRADEINDIA', 'INDIAMART']);
            $table->string('inq_uuid');
            $table->date('inq_date');
            $table->string('sender_company')->nullable();
            $table->string('sender_name')->nullable();
            $table->string('sender_email')->nullable();
            $table->string('sender_other_email')->nullable();
            $table->string('sender_mobile')->nullable();
            $table->string('sender_other_mobile')->nullable();
            $table->string('sender_city')->nullable();
            $table->string('sender_state')->nullable();
            $table->string('sender_country')->nullable();
            $table->string('subject')->nullable();
            $table->string('product_name')->nullable();
            $table->integer('product_qty')->nullable();
            $table->longText('notes')->nullable();
            $table->longText('inq_full_info')->nullable();
            $table->enum('inq_created', ['Y', 'N'])->default('N');
            $table->timestamps();
            $table->unsignedBigInteger('financial_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('online_inquiry');
    }
}
