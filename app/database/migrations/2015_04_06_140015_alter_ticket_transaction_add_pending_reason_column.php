<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTicketTransactionAddPendingReasonColumn extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ticket_transaction', function(Blueprint $table)
		{
      //By default assign to admin
      $table->integer('pending_reason_id')->unsigned()->nullable()->default(NULL)->after('status_id');
      $table->foreign('pending_reason_id')->references('id')->on('pending_reason')->onDelete('restrict');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
