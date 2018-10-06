<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ltech_items_details', function (Blueprint $table) {
            $table->bigIncrements('item_id');
            $table->string('item_name');
            $table->integer('item_category_id');
            $table->string('item_name_slug');
            $table->string('item_description')->nullable();
            $table->string('item_quantity_unit');
            $table->string('created_by');
            $table->string('updated_by');
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
        Schema::drop('ltech_items_details');
    }
}
