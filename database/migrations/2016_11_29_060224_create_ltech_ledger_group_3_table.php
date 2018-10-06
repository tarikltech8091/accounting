<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLtechLedgerGroup3Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ltech_ledger_group_3', function (Blueprint $table) {
            $table->increments('ledger_id');
            $table->string('ledger_name');
            $table->string('ledger_name_slug');
            $table->string('ledger_naration')->nullable();
            $table->integer('ledger_group_parent_id');
            $table->integer('ledger_group_have_child');
            $table->integer('depth');
            $table->float('ledger_debit')->nullable()->default(0);
            $table->float('ledger_credit')->nullable()->default(0);
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
         Schema::drop('ltech_ledger_group_3');
    }
}
