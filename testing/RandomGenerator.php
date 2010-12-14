<?php
class RandomGenerator
{
	/**
	 * 
	 * @param int $length how long your string should be
	 * @param string $type currently supported alpha and alphanum
	 * @param boolean $random whether the length should be random (ie, if you put in 80 for length and say random:true, the actual length of the string will be between 5 and 80 chars
	 * @param int $min minimum number of chars to return
	 * @return string
	 */
    public static function getString( $length, $type = 'alpha', $random = false, $min = 5 )
    {

    	$type = strtolower( $type );
    	
        // - get chars into an aray
        // we could use the randomized length here instead of later in the process
        // but the randomized results are a little more random when we get this big pie
        // and then grab little slices of it later
        switch ($type)
        {
        	case 'alpha':
                $str = RandomGenerator::getAlphaString( $length );
                break;   
        	case 'alphawhitespace':
				$str = RandomGenerator::getAlphaWhitespaceString( $length );
        		break;
            case 'alphanum':
                $str = RandomGenerator::getAlphaNumString( $length );
                break;
            case 'alphanumwhitespace':
            	$str = RandomGenerator::getAlphaNumWhitespaceString( $length );
            	break; 
            case 'alphanumchar':
            	$str = RandomGenerator::getAlphaNumCharString( $length );
            	break;
            default:
               $str = RandomGenerator::getAlphaNumCharString( $length );
                break;
        }

		// put the string chars into an array 
		$arr = str_split( $str );
		
        // if it's a random length
        // then we only pull out that new random length
        // pull out random indexes
        if ( $random )
        {
        	$length = rand( $min, $length);
        }
        $arr_index = array_rand( $arr, $length );
   
        // pull indexes of original array and make a new array of chars
        $arr_random = array();
        for ( $i = 0; $i < $length; $i++ )
        {
            $arr_random[] = $arr[ $arr_index[$i] ];
        }
        return implode('', $arr_random);
    }

    /**
     * 
     * @param int $length
     */
    protected static function getAlphaString( $length )
    {
        $str = '';
        // we're just making sure with this loop that we have enough random chars to pull from
        // its faster to make a really big string and pull out values from it
        // then to keep looping over the same string
        for ( $i = 0; $i < $length; $i = $i + 26 )
        {
            $str .= "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        } 
        return $str;
    }

    /**
     * 
     * @param int $length
     */
    protected static function getAlphaWhitespaceString( $length )
    {
        $str = '';
        // we're just making sure with this loop that we have enough random chars to pull from
        // its faster to make a really big string and pull out values from it
        // then to keep looping over the same string
        for ( $i = 0; $i < $length; $i = $i + 40 )
        {
            $str .= "abcd  efghij  klmnopq  rstuvwxy  zABCDEFG  HIJKLMNO  PQRSTUV  WXYZ";
        } 
        return $str;
    }
    
    /**
     * 
     * @param int $length
     */
    protected static function getAlphaNumString( $length )
    {
        $str = '';
        // we're just making sure with this loop that we have enough random chars to pull from
        // its faster to make a really big string and pull out values from it
        // then to keep looping over the same string
        for ( $i = 0; $i < $length; $i = $i + 46 )
        {
            $str .= "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ012345678900123456789001234567890";
        }
        return $str;
    }

    /**
     * 
     * @param int $length
     */
    protected static function getAlphaNumWhitespaceString( $length )
    {
        $str = '';
        // we're just making sure with this loop that we have enough random chars to pull from
        // its faster to make a really big string and pull out values from it
        // then to keep looping over the same string
        for ( $i = 0; $i < $length; $i = $i + 60 )
        {
            $str .= "abcde  fghijk  lmnopqr  stuvwxy  zABCDEFGHI  JKLMNOP  QRST  UVWX  YZ01234  567890  012345678  900123  4567890";
        }
        return $str;
    }
    
    /**
     * 
     * @param int $length
     */
    protected static function getAlphaNumCharString( $length )
    {
        $str = '';
        // we're just making sure with this loop that we have enough random chars to pull from
        // its faster to make a really big string and pull out values from it
        // then to keep looping over the same string
        for ( $i = 0; $i < $length; $i = $i + 46 )
        {
            $str .= "     ''''''!@#$%^&*()!\\\   [][][{}{}--=++==_;;//?>>>>>   ><@#$%^&*()abcde  fghijklmnopqrstuvwxyz 'ABCDEFGHIJKLMNOPQRSTUVWXYZ '0123456789 0012345678900123456789 '0''''''";
        }
        return $str;
    }   
}



