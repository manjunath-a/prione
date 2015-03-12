<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SalesChannelTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
    Schema::create('sales_channel', function(Blueprint $table) {
      $table->increments('id')->unsigned();
      $table->string('channel_name', 255)->unique();
      $table->boolean('status');
      $table->integer('sort');
    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sales_channel');
	}

}
