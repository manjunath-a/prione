<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableSellerRequestAletrColumn extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('seller_request', function(Blueprint $table) 	{
			$table->dropColumn('merchant_id');
		});
		Schema::table('seller_request', function(Blueprint $table) 	{
			$table->string('merchant_id')->after('merchant_name');
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
