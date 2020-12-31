<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerInquiryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_inquiry', function (Blueprint $table) {
            $table->bigIncrements('customer_inquiry_id')->index();
            $table->string('company_name',150)->nullable();
            $table->string('contact_person',150)->nullable();
            $table->string('phone_no',15)->nullable();
            $table->string('subject',255)->nullable();
            $table->string('email',62)->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('customer_inquiry');
    }
}
