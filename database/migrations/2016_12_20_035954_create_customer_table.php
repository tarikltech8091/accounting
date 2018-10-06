<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ltech_customers', function (Blueprint $table) {
            $table->increments('customer_id');
            $table->string('customer_account_id');
            $table->string('customer_company');
            $table->string('customer_company_slug');
            $table->string('customer_name')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('customer_mobile')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_tax_reg_no')->nullable();
            $table->string('customer_status')->nullable();
            $table->float('customer_net_debit_amount')->nullable();
            $table->float('customer_net_credit_amount')->nullable();
            $table->float('customer_net_balance_amount')->nullable();
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
        Schema::drop('ltech_customers');
    }
}
