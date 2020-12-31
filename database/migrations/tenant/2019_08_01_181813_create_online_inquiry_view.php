<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnlineInquiryView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("create view online_inquiry_view as
select o_id,
       date_format(inq_date, '%d-%m-%Y')                                                         as inquiry_date,
       ifnull(inquiry_from, '')                                                                  as inquiry_from,
       ifnull(sender_name, '')                                                                   as sender_name,
       ifnull(sender_company, '')                                                                as sender_company,
       ifnull(sender_mobile, '')                                                                 as sender_mobile,
       ifnull(sender_email, '')                                                                  as sender_email,
       ifnull(`subject`, '')                                                                  as `subject`,
       ifnull(product_name, '')                                                                  as product_name,
       concat(
               '<a href=\"', (select ifnull(url, '') from config), 'make-inquiry/', o_id,
               '\" class=\"btn btn-pill btn-primary btn-sm\"><i class=\"flaticon2-chat-1\"></i></a>') as action
from online_inquiry order by o_id desc;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       DB::statement("DROP VIEW IF EXISTS online_inquiry_view");
    }
}
