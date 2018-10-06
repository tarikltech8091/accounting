<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ltech_sales_orders', function (Blueprint $table) {
            $table->bigIncrements('order_id');
            $table->date('order_date');
            $table->string('order_description');
            $table->integer('order_customer_id');
            $table->integer('cost_center_id');
            $table->float('order_net_amount')->nullable();
            $table->integer('order_status');
            $table->date('order_delivery_date')->nullable();
            $table->float('order_delivery_amount')->nullable();
            $table->float('order_delivery_discount_rate')->nullable();
            $table->float('order_delivery_discount_amount')->nullable();
            $table->float('order_delivery_net_amount')->nullable();
            $table->float('order_delivery_debit_amount')->nullable();
            $table->float('order_delivery_credit_amount')->nullable();
            $table->float('order_delivery_balance_amount')->nullable();
            $table->float('customer_order_delivery_net_balance_amount')->nullable();
            $table->date('order_delivered_customer_date')->nullable();
            $table->string('order_delivered_by')->nullable();
            $table->string('sales_referrence')->nullable();
            $table->string('sales_return_referrence')->nullable();
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
        Schema::drop('ltech_sales_orders');
    }
}
