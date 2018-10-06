<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsStocksTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ltech_inventory_stocks_transactions', function (Blueprint $table) {
            $table->bigIncrements('stocks_transactions_id');
            $table->date('stocks_transaction_date');
            $table->bigInteger('inventory_stock_id');
            $table->integer('item_category_id');
            $table->integer('stocks_supplier_id')->nullable();
            $table->integer('stocks_employee_id')->nullable();
            $table->integer('cost_center_id');
            $table->string('stocks_transaction_desc');
            $table->string('item_quantity_unit');
            $table->string('stocks_transaction_type');
            $table->string('opening_transaction_stocks_quantity')->nullable();
            $table->string('transaction_stocks_quantity')->nullable();
            $table->string('closing_transaction_stocks_quantity')->nullable();
            $table->float('stocks_quantity_rate')->nullable()->default(0);
            $table->float('opening_transaction_stocks_cost')->nullable()->default(0);
            $table->float('stocks_quantity_cost')->nullable()->default(0);
            $table->float('closing_transaction_stocks_cost')->nullable()->default(0);
            $table->float('stocks_supplier_credit_amount')->nullable()->default(0);
            $table->float('stocks_supplier_debit_amount')->nullable()->default(0);
            $table->float('stocks_supplier_balance_amount')->nullable()->default(0);
            $table->float('return_status')->nullable()->default(0);
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
         Schema::drop('ltech_inventory_stocks_transactions');
    }
}
