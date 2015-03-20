<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTicketTransactionAddCoulmnActive extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ticket_transaction', function(Blueprint $table) 	{
			$table->tinyInteger('active')->unsigned()->after('stage_id');
			$table->integer('photgrapher_id')->unsigned()->after('active');
			$table->integer('mif_id')->unsigned()->after('photgrapher_id');
			$table->dateTime('photosuite_date')->after('mif_id');
			$table->dateTime('photosuite_location')->after('photosuite_date');
			$table->integer('sa_variation')->unsigned()->after('photosuite_location');
			$table->integer('sa_sku')->unsigned()->after('sa_variation');
			$table->text('notes')->after('sa_sku');

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
