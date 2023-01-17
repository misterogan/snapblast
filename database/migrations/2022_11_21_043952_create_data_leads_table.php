<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_leads', function (Blueprint $table) {
            $table->id();
            $table->string('data_code')->nullable();
            $table->string('row_status')->nullable();
            $table->string('data_status')->nullable();
            $table->integer('campaign_id')->nullable()->index();
            $table->string('full_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('dob')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('company_name')->nullable();
            $table->text('message')->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_banner')->nullable();
            $table->string('utm_term')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('device')->nullable();
            $table->string('device_name')->nullable();
            $table->string('os')->nullable();
            $table->string('browser')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->string('referer')->nullable();
            $table->string('ip')->nullable();
            $table->string('sub1')->nullable();
            $table->string('sub2')->nullable();
            $table->string('sub3')->nullable();
            $table->string('sub4')->nullable();
            $table->string('sub5')->nullable();
            $table->string('assignee')->nullable();
            $table->string('assigned_to')->nullable();
            $table->dateTime('due_date')->nullable();
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
        Schema::dropIfExists('data_leads');
    }
}
