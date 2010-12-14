<?php

$obj = json_decode(file_get_contents("php://input"));

if (isset( $obj->rows->name ) && isset( $obj->rows->email ))
{
	$row = array(
			'id' => rand( 4, 10 ),
			'name' => $obj->rows->name,
			'email' => $obj->rows->email
	);
	
	$output = array(
		'success' => true,
		'rows' => array( $row )
	);
}

$json = json_encode( $output );
echo $json;
