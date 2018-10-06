<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ltech_inventory_stocks', function (Blueprint $table) {
            $table->bigIncrements('inventory_stock_id');
            $table->string('stocks_type');
            $table->integer('item_category_id');
            $table->string('item_name');
            $table->string('item_name_slug');
            $table->string('item_account_id')->nullable();
            $table->string('item_description')->nullable();
            $table->string('item_quantity_unit');
            $table->string('stocks_onhand')->nullable();
            $table->string('stocks_onproduction')->nullable();
            $table->string('stocks_onproduction_cost')->nullable();
            $table->string('stocks_total_quantity')->nullable();
            $table->float('stocks_total_cost')->nullable()->default(0);
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
        Schema::drop('ltech_inventory_stocks');
    }
}
