<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLtechGeneralJournalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ltech_general_journal', function (Blueprint $table) {
            $table->bigIncrements('journal_id');
            $table->date('journal_date');
            $table->integer('transaction_id');
            $table->integer('journal_particular_id');
            $table->string('journal_particular_name');
            $table->integer('journal_particular_depth');
            $table->string('journal_particular_naration');
            $table->string('journal_particular_amount_type');
            $table->float('journal_particular_amount',15,2);
            $table->integer('cost_center_id');
            $table->string('posting_type');
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
        Schema::drop('ltech_general_journal');
    }
}
