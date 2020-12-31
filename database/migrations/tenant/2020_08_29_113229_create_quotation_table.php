<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation', function (Blueprint $table) {
            $table->bigIncrements('quotation_id')->index();
            $table->enum('quotation_status',['Pi Created','Pending','Sales Created'])->default('Pending');
            $table->string('quotation_no',10)->nullable();
            $table->string('display_quotation_no',10)->nullable();

            $table->unsignedBigInteger('customer_id')->index()->nullable();
            $table->unsignedBigInteger('inquiry_id')->index()->nullable();
            $table->date('q_date')->nullable();
            $table->string('reference',150)->nullable();
            $table->string('contact_person',150)->nullable();
            $table->string('phone_no',15)->nullable();
            $table->string('email',62)->nullable();
            $table->string('gstin',15)->nullable();
            $table->unsignedBigInteger('state_id')->nullable();


            $table->unsignedBigInteger('financial_year_id')->index()->nullable();


            $table->double('total')->nullable();
            $table->double('pf')->nullable();
            $table->double('pf_taxrate')->nullable();
            $table->double('total_with_pf')->nullable();
            $table->double('rounding_amount')->nullable();
            $table->double('grand_total')->nullable();



            $table->string('dispatch_through')->nullable();
            $table->string('delivery_period')->nullable();
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
        Schema::dropIfExists('quotation');
    }
}
