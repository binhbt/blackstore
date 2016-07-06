<?php
/**
 * UTF8 safe class
 * http://bbit.vn
 *
 * @package			bbitUtf8
 * @author			Bbit
 */
class bbitUtf8
{
	/*
    * Some required plugin information
    */
    const VERSION = '1.0';

    /*
    * Store some helpers config
    */
	public $the_plugin = null;

	private $module_folder = '';

	static protected $_instance;
	
	public $matchWords;

    /*
    * Required __construct() function
    */
    public function __construct()
    {
    	global $bbit;

    	$this->the_plugin = $bbit;
    	
    	// /\\pL[\\pL\\p{Mn}\'-]*/
    	// /\\p{L}[\\p{L}\\p{Mn}\\p{Pd}'\\x{2019}]*/
    	// /[\pL']+/
    	// /\p{L}+/
    	// /^[-\' \p{L}]+$/
    	$this->matchWords = "/\\pL[\\pL\\p{Mn}\'-]*/";
    	$this->matchWords .= "imu";
    }

	/**
    * Singleton pattern
    *
    * @return bbitUtf8 Singleton instance
    */
    static public function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }
    
    
    /**
     *  string length
     *
     */
	public function strlen( $str ) {
		return strlen( utf8_decode( $str ) );
	}
	
	
	/**
	 * return substring from string & number of occurences!
	 * 
	 * unicode example with "case-sensitive" option
	 *
	 */
	public function substr_count( $str, $substr, $caseSensitive = true, $offset = 0, $length = null ) {
		if ($offset) {
			$str = $this->substr($str, $offset, $length);
		}

		$pattern = $caseSensitive
			? '~(?:'. preg_quote($substr) .')~u'
			: '~(?:'. preg_quote($substr) .')~ui';
		preg_match_all($pattern, $str, $matches);

		return isset($matches[0]) ? count($matches[0]) : 0;
	}

	public function substr( $str, $start, $length = null ) {
		return join('', array_slice( preg_split('~~u', $str, -1, PREG_SPLIT_NO_EMPTY), $start, $length ));
	}
	

	/**
	 * number of words in string!
	 * 
	 */
    public function str_word_count($string, $format = 0, $charlist = null) {
    	if ($charlist === null) {
    		$regex = '/\\pL[\\pL\\p{Mn}\'-]*/u';
    	} else {
    		$split = array_map( 'preg_quote', preg_split('//u', $charlist, -1, PREG_SPLIT_NO_EMPTY) );
    		$regex = sprintf( '/(\\pL|%1$s)([\\pL\\p{Mn}\'-]|%1$s)*/u', implode('|', $split) );
    	}

    	$ret = false;
    	switch ($format) {
    		default:
    		case 0:
    			// For PHP >= 5.4.0 this is fine:
    			// return preg_match_all($regex, $string);

    			// For PHP < 5.4 it's necessary to do this:
    			$results = null;
    			$ret = preg_match_all($regex, $string, $results);
    			break;
    		case 1:
    			$results = null;
    			preg_match_all($regex, $string, $results);
    			$ret = $results[0];
    			break;
    		case 2:
    			$results = null;
    			preg_match_all($regex, $string, $results, PREG_OFFSET_CAPTURE);
    			$ret = empty($results[0])
    				? array()
    				: array_combine( array_map('end', $results[0]), array_map('reset', $results[0]) );
    			break;
    	}
    	return $ret;
    }
	
	public function strtolower($string) {

		$result = $string;
		//if ( seems_utf8($string) )  $result = utf8_decode($string);
		$result = strtolower($result);
		//$result = utf8_encode($result);
		return $result;
	}
}
$utf8 = new bbitUtf8();