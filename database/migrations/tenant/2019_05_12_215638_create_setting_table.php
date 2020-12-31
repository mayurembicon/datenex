<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('freight_tax_rate')->nullable();
            $table->string('app_url')->nullable();
            $table->string('telegram_token_id')->nullable();
            $table->integer('indiamart_sync_time_limit')->nullable();


            $table->string('trade_user_id')->nullable();
            $table->string('trade_profile_id')->nullable();
            $table->string('trade_key')->nullable();

            $table->string('india_mobile_no')->nullable();
            $table->string('india_key')->nullable();

            $table->dateTime('last_indiamart_sync')->nullable();
            $table->dateTime('last_tradeindia_sync')->nullable();

            $table->string('mail_body')->nullable();
            $table->string('telegram_api')->nullable();

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
        Schema::dropIfExists('setting');
    }
}
