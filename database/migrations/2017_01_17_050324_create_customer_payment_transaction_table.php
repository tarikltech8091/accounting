<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerPaymentTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ltech_customer_payment_transactions', function (Blueprint $table) {
            $table->bigIncrements('customer_payment_transactions_id');
            $table->bigInteger('customer_id');
            $table->bigInteger('order_id');
            $table->float('opening_customer_credit_amount')->nullable()->default(0);
            $table->float('closing_customer_credit_amount')->nullable()->default(0);
            $table->float('opening_customer_debit_amount')->nullable()->default(0);
            $table->float('closing_customer_debit_amount')->nullable()->default(0);
            $table->float('opening_customer_balance_amount')->nullable()->default(0);
            $table->float('closing_customer_balance_amount')->nullable()->default(0);
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
        Schema::drop('ltech_customer_payment_transactions');
    }
}
