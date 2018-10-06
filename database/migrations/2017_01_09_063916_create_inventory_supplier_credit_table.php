<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventorySupplierCreditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ltech_inventory_supplier_credit', function (Blueprint $table) {
            $table->bigIncrements('supplier_credit_id');
            $table->integer('supplier_id');
            $table->float('supplier_net_debit_amount')->nullable();
            $table->float('supplier_net_credit_amount')->nullable();
            $table->float('supplier_net_balance_amount')->nullable();
            $table->string('supplier_ledger')->nullable();
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
        Schema::drop('ltech_inventory_supplier_credit');
    }
}
