<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ltech_suppliers', function (Blueprint $table) {
            $table->increments('supplier_id');
            $table->string('supplier_account_id');
            $table->string('supplier_company');
            $table->string('supplier_company_slug');
            $table->string('supplier_name')->nullable();
            $table->string('supplier_address')->nullable();
            $table->string('supplier_mobile')->nullable();
            $table->string('supplier_email')->nullable();
            $table->string('supplier_tax_reg_no')->nullable();
            $table->string('supplier_status')->nullable();
            $table->float('supplier_net_debit_amount')->nullable();
            $table->float('supplier_net_credit_amount')->nullable();
            $table->float('supplier_net_balance_amount')->nullable();
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
        Schema::drop('ltech_suppliesrs');
    }
}
