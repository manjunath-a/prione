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
	'groups' => array('Cataloging'=>5000228695, 'Editing'=>5000228696, 'Local'=> 5000228697),
	 // Custome Fields at FreshDesk
  'custom_fields' => array (
  	'stage_203188' => 'stage_name',
  	'city_203188' => 'city_name' ,
  	'no_of_skus_to_be_cataloged_203188' => 'total_sku',
  	'no_of_skus_imaged_203188' => 'total_images',
  	// 'no_of_images_203188' => 'total_images',
  	's3_bucket_images_amp_mif_203188' => 's3_folder' ,
  ),

  'freshdesk_fields' => array (
    'stage_name' => 'stage_203188',
    'city_name' => 'city_203188' ,
    'total_sku' =>'no_of_skus_to_be_cataloged_203188',
    'total_images' =>  'no_of_skus_imaged_203188',
    // 'no_of_images_203188' => 'total_images',
    's3_folder' => 's3_bucket_images_amp_mif_203188',
  ),
);
