<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInquiryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inquiry', function (Blueprint $table) {


            $table->bigIncrements('inquiry_id')->index();
            $table->integer('customer_id')->index()->nullable();
            $table->date('date')->nullable();
            $table->string('inquiry_from')->nullable();
            $table->string('subject',255)->nullable();
            $table->string('contact_person',150)->nullable();
            $table->string('phone_no',15)->nullable();
            $table->unsignedBigInteger('assign_id')->index()->nullable();
            $table->string('ratedIndex')->nullable();
            $table->string('email',62)->nullable();
            $table->text('notes')->nullable();

            $table->unsignedBigInteger('financial_year_id')->index()->nullable();

            $table->enum('inquiry_status',['Quotation Created','Pending','Inquiry Close'])->default('Pending');

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
        Schema::dropIfExists('inquiry');
    }
}
