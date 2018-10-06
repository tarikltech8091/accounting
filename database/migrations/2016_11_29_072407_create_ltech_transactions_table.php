<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLtechTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ltech_transactions', function (Blueprint $table) {
            $table->bigIncrements('transactions_id');
            $table->date('transactions_date');
            $table->string('transactions_naration');
            $table->float('transaction_amount',15,2);
            $table->integer('cost_center_id');
            $table->string('posting_type');
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
        Schema::drop('ltech_transactions');
    }
}
