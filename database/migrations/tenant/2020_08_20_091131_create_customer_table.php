<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->bigIncrements('customer_id')->index();
            $table->unsignedBigInteger('payment_terms_id')->index()->nullable();
            $table->enum('customer_type',['C','V','B'])->nullable();
            $table->string('customer_name',150)->nullable();
            $table->string('company_name',150)->nullable();
            $table->string('email',62)->nullable();
            $table->string('f_phone_no',15)->nullable();
            $table->string('s_phone_no',15)->nullable();
            $table->string('website',125)->nullable();
            $table->string('contact_person_name',150)->nullable();
            $table->string('gst_no',15)->nullable();;
            $table->string('place_of_supply',3)->nullable();
            $table->text('notes')->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();

        });

        DB::statement('ALTER TABLE customer ADD FULLTEXT companyname(company_name)');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('customer');
    }
}
