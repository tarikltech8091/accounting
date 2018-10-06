<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventorySupplierCreditTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ltech_inventory_supplier_credit_transactions', function (Blueprint $table) {
            $table->bigIncrements('supplier_credit_transactions_id');
            $table->bigInteger('supplier_id');
            $table->bigInteger('stocks_transactions_id');
            $table->float('opening_stocks_credit_amount')->nullable()->default(0);
            $table->float('closing_stocks_credit_amount')->nullable()->default(0);
            $table->float('opening_stocks_debit_amount')->nullable()->default(0);
            $table->float('closing_stocks_debit_amount')->nullable()->default(0);
            $table->float('opening_stocks_balance_amount')->nullable()->default(0);
            $table->float('closing_stocks_balance_amount')->nullable()->default(0);
            $table->string('payment_method')->nullable();
            $table->date('transaction_date')->nullable();
            $table->string('payment_account')->nullable();
            $table->float('transaction_amount')->nullable();
            $table->string('referrence')->nullable();
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
        Schema::drop('ltech_inventory_supplier_credit_transactions');
    }
}
