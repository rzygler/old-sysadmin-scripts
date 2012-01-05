<?php

/* program to generate pascal's triangle

A simple construction of the triangle proceeds in the following manner. On row 0, write only the number 1. Then, to construct the elements of following rows, add the number directly above and to the left with the number directly above and to the right to find the new value. I

1	
1	1
1	2	1
1	3	3	1
1	4	6	4	1
1	5	10	10	5	1	
			1
		1		1
	1		2		1
1		3		3		1
	
*/
$members = array();

for ( $row = 0; $row < 10; $row++ )
{

	$members[ $row ] = array();

	for ( $col = 0; $col <= $row; $col++ )
	{	
		$prevRow = $row - 1;
		$leftCol = $col - 1;	
		$rightCol = $col;

		$leftVal = 1;

		if ( isset( $members[ $prevRow ][ $leftCol ] ) )
		{
			$leftVal = $members[ $prevRow ][ $leftCol ];	
		}
		if ( $col == 0 ) $leftVal = 0;
		if ( $row == 0 ) $leftVal = 1;
		if ( isset( $members[ $prevRow ][ $rightCol ] ) )
		{
			$rightVal = $members[ $prevRow ][ $rightCol ];	
		}
		if ( $col == $row ) $rightVal = 0;
		$value = $leftVal + $rightVal;
		print "   $value   ";
		array_push( $members[ $row ], $value );
	}	
	print "\n";
}


