<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SellerRequestTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		 Schema::create('seller_request', function(Blueprint $table) {
      $table->increments('id', true)->unsigned();
      $table->string('requester_name', 150);
      $table->string('email',150 );
      $table->mediumInteger('contact_number');
      $table->string('merchant_name', 150);
      $table->string('merchant_id',255);
      $table->integer('merchant_city_id')->unsigned();
      $table->integer('merchant_sales_channel_id')->unsigned();
      $table->string('poc_name', 150);
      $table->string('poc_number', 150);
      $table->string('poc_email', 150);
      $table->integer('category_id')->unsigned();
      $table->integer('total_sku')->unsigned();
      $table->boolean('image_available')->unsigned();
      $table->string('comment');
      $table->dateTime('updated_at');
      $table->dateTime('created_at');

      // Foreign Key
      $table->foreign('merchant_city_id')->references('id')->on('city')->onDelete('cascade');
      $table->foreign('merchant_sales_channel_id')->references('id')
      	->on('sales_channel')->onDelete('cascade');
     	$table->foreign('category_id')->references('id')
      	->on('category')->onDelete('cascade');
    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('seller_request');
	}

}
