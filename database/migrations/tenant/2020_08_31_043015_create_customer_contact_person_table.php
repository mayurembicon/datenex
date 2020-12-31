<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerContactPersonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_contact_person', function (Blueprint $table) {
            $table->bigIncrements('contact_person_id')->index();
            $table->unsignedBigInteger('customer_id')->index()->nullable();
            $table->string('salutation')->nullable();
            $table->string('contact_person_name',150)->nullable();
            $table->string('email',62)->nullable();
            $table->string('phone_no',15)->nullable();
            $table->string('designation')->nullable();
            $table->string('department')->nullable();
            $table->foreign('customer_id')
                ->references('customer_id')->on('customer')
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
        Schema::dropIfExists('customer_contact_person');
    }
}
