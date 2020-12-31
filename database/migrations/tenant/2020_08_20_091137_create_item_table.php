<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itemmaster', function (Blueprint $table) {
            $table->bigIncrements('item_id')->index();
            $table->unsignedBigInteger('taxrate')->index()->nullable();
            $table->string('ratedIndex')->nullable();
            $table->string('name',150)->nullable();
            $table->string('unit')->nullable();
            $table->string('hsn',20)->nullable();
            $table->string('sku',20)->nullable();
            $table->double('sale_rate')->default(0)->nullable();
            $table->double('purchase_rate')->default(0)->nullable();
            $table->double('discount_amount')->default(0)->nullable();
            $table->text('descripation')->nullable();
            $table->enum('type',['good','service'])->nullable();
            $table->timestamps();
        });
        DB::statement('ALTER TABLE itemmaster ADD FULLTEXT itemname(name)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('itemmaster');
    }
}
