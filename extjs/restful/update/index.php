<?php

$output = array();
$obj = json_decode(file_get_contents("php://input"));

if ( isset( $obj->rows->id ) && (isset( $obj->rows->name ) || isset( $obj->rows->email ) ))
{
	$output = array(
		'success' => true,
		'rows' => array(
			'id' => $obj->rows->id
		)
	);
	if (isset( $obj->rows->name))
	{
		$output['rows']['name'] = $obj->rows->name;
	}
	if (isset( $obj->rows->email))
	{
		$output['rows']['email'] = $obj->rows->email;
	}
}

echo json_encode( $output );
