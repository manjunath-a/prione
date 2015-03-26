<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Fresh Desk API Information
	|--------------------------------------------------------------------------

	*/
	// Domain URL
	'url' => 'https://compassnet.freshdesk.com',
	// Access tocken
	'token' => 'k0FfSA2BHE9Yxw22ww',
	// Password
	'password' => 'X',
	// Password
	'groups' => array('cataloging'=>5000228695, 'editing'=>5000228696, 'local'=> 5000228697),
	 // Custome Fields at FreshDesk
  'custom_fields' => array (
  	'stage_203188' => 'stage_name',
  	'city_203188' => 'city_name' ,
  	'no_of_skus_to_be_cataloged_203188' => 'total_sku',
  	// 'no_of_skus_imaged_203188' => 'no_of_sku_images',
  	// 'no_of_images_203188' => 'total_images',
  	// 's3_bucket_images_amp_mif_203188' => 's3_url' ,
  	),
);
