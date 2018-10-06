<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinishgoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ltech_finish_goods_stocks', function (Blueprint $table) {
            $table->bigIncrements('finish_goods_id');
            $table->date('finish_goods_entry_date');
            $table->string('finish_goods_accounts_id')->nullable();
            $table->string('finish_goods_name');
            $table->string('finish_goods_name_slug')->nullable();
            $table->float('finish_goods_net_production_cost')->nullable();
            $table->string('finish_goods_net_production_quantity')->nullable();
            $table->float('finish_goods_net_sales_cost')->nullable();
            $table->string('finish_goods_net_sales_quantity')->nullable();
            $table->float('finish_goods_net_cost')->nullable();
            $table->string('finish_goods_net_quantity')->nullable();
            $table->string('finish_goods_waste_quantity')->nullable()->default(0);
            $table->string('finish_goods_waste_rate')->nullable()->default(0);
            $table->string('finish_goods_waste_cost')->nullable()->default(0);
            $table->string('goods_status')->nullable()->default(0);
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
        Schema::drop('ltech_finish_goods_stocks');
    }
}
