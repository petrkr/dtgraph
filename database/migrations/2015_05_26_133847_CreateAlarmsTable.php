<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoreDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('digitemp_alarms')) {
            // Raw select to create table
//            DB::statement('create table digitemp_daily as (select unix_timestamp(date(time)) as unixtime, TIMESTAMPADD(HOUR, 12, date(time)) as date, SerialNumber, avg(Fahrenheit) as Fahrenheit, max(Fahrenheit) max, min(Fahrenheit) min from digitemp  group by SerialNumber, date(time) order by date(time), SerialNumber)');


            Schema::create('digitemp_alarms', function (Blueprint $table) {
                $table->increments('alarm_id')->unsigned();
                $table->string('SerialNumber', 17);
                $table->decimal('Fahrenheit', 5, 2);
                
                $table->dateTime('time_raised');
                $table->dateTime('time_cleared')->nullable();
                $table->dateTime('time_updated')->nullable()->default('CURRENT_TIMESTAMP');

                $table->string('alarm_type', 15);
                $table->string('description', 255)->nullable();

                $table->index('SerialNumber');
                $table->index('time_raised');
                $table->index('time_cleared');
                $table->index('alarm_type');
            });

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('digitemp_alarms');
    }
}