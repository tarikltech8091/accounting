<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinishGoodsTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ltech_finish_goods_transactions', function (Blueprint $table) {
            $table->bigIncrements('ltech_finish_goods_transactions_id');
            $table->date('finish_goods_transaction_date');
            $table->string('finish_goods_accounts_id');
            $table->integer('finish_goods_id');
            $table->integer('cost_center_id');
            $table->integer('customer_id')->nullable();
            $table->string('finish_goods_type');
            $table->string('finish_goods_transaction_type');
            $table->string('opening_transaction_finish_goods_quantity')->nullable();
            $table->string('transaction_finish_goods_quantity')->nullable();
            $table->string('closing_transaction_finish_goods_quantity')->nullable();
            $table->float('finish_goods_quantity_rate')->nullable()->default(0);
            $table->float('opening_transaction_finish_goods_cost')->nullable()->default(0);
            $table->float('finish_goods_quantity_cost')->nullable()->default(0);
            $table->float('closing_transaction_finish_goods_cost')->nullable()->default(0);
            $table->longtext('finish_goods_inventory')->nullable();
            $table->string('referrence');
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
        Schema::drop('ltech_finish_goods_transactions');
    }
}
