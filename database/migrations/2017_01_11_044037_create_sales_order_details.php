<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesOrderDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ltech_sales_order_details', function (Blueprint $table) {
            $table->bigIncrements('order_details_id');
            $table->biginteger('order_id');
            $table->integer('order_customer_id');
            $table->integer('cost_center_id');
            $table->string('order_item_name');
            $table->string('order_item_quantity_type')->nullable();
            $table->string('order_item_quantity')->nullable();
            $table->float('order_item_quantity_rate')->nullable();
            $table->float('order_item_cost')->nullable();
            $table->string('order_item_deliverd_quantity')->nullable();
            $table->float('order_item_deliverd_quantity_rate')->nullable();
            $table->float('order_item_deliverd_cost')->nullable();
            $table->integer('order_item_process_status')->nullable();
            $table->date('order_item_process_date')->nullable();
            $table->longtext('order_item_process_list')->nullable();
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
        Schema::drop('ltech_sales_order_details');
    }
}
