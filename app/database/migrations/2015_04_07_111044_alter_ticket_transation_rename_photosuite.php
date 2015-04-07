<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTicketTransationRenamePhotosuite extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ticket_transaction', function(Blueprint $table)
		{
  	    $table->renameColumn('photosuite_date', 'photoshoot_date');
  	    $table->renameColumn('photosuite_location', 'photoshoot_location');
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
