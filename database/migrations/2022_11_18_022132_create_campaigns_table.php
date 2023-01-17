<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('ad_id');
            $table->string('row_status')->nullable();
            $table->string('campaign_name')->nullable();
            $table->text('campaign_url')->nullable();
            $table->integer('target_lead')->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('postback_id')->default(0)->nullable();
            $table->string('postback_trigger')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
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
        Schema::dropIfExists('campaigns');
    }
}
