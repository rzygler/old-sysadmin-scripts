<?php 


	$output = array(
		'succes' => true,
		'rows' => array(
			array(
				'id' => 1,
				'name' => 'Homer Simpson',
				'email' => 'homer@springfieldnuclear.com'
			),
			array(
				'id' => 2,
				'name' => 'Apu Nahasapeemapetilon',
				'email' => 'apu@kwikemart.com'
			),
			array(
				'id' => 3,
				'name' => 'Bumblebee Man',
				'email' => 'bumblebeeman@spanishtv.com'
			)
		)
	);
	
	$json = json_encode( $output );

	print $json;

