<?php

$output = array();
$obj = json_decode(file_get_contents("php://input"));

	$output = array(
		'success' => true
	);


echo json_encode( $output );
