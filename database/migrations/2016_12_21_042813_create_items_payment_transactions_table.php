<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsPaymentTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ltech_inventory_payment_transactions', function (Blueprint $table) {
            $table->bigIncrements('inventory_payment_id');
            $table->bigInteger('stocks_transactions_id');
            $table->bigInteger('inventory_stock_id');
            $table->integer('item_category_id');
            $table->integer('stocks_supplier_id');
            $table->float('supplier_credit_amount')->nullable();
            $table->float('supplier_debit_amount')->nullable();
            $table->string('payment_method')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('payment_account')->nullable();
            $table->float('payment_transaction_amount')->nullable();
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
        Schema::drop('ltech_inventory_payment_transactions');
    }
}
