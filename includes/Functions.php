<?php  
  
if( !defined("TO_ROOT") ) { define("TO_ROOT", ".."); }
  
  /**
 * Provides a General Functions
 * Holds the {@link Functions} class
 * @author Ismael Cortés <may.estilosfrescos@gmail.com>
 * @copyright Copyright (c) 2010, Ismael Cortés <may.estilosfrescos@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package spiderFrame
 */

class Functions {
	private static $lang 			= null;
	private static $dictionary 		= null;
	private static $dictionary_file = null;
    private static $dictionaries	= null;
    
    public static function __test(){
    	return "hello world";
    }
    
	public static function __display($variable) {
  		return self::__displayVariable($variable);
  	}

  	public static function __displayVariable($variable) {
  		echo "<pre>";
  		print_r($variable);
  		echo "</pre>";
  	}

  	
  	// ------------------------------ //////////// --------------------------------- //
    // --------------------------  CRYPTION FUNCTIONS ------------------------------ //
    // ------------------------------ //////////// --------------------------------- //    
	public static function __spiderCryption($string) {
	  	$spider_crypted = sha1($string);

		return $spider_crypted;
	}


	// ------------------------------ //////////// --------------------------------- //
    // ----------------  USER SUPPORT AND LOGIN TOKEN FUNCTIONS -------------------- //
    // ------------------------------ //////////// --------------------------------- //    
	public static function __getUserIdByToken($token, $user_type, DbConnection $DbConnection = null){
    	$DbConnection = ($DbConnection == null) ? DbConnection::getInstance("_root") : $DbConnection ;
       	
       	$user_id = false;
        $token_array = explode("-", $token);
        $token_data = (count($token_array) === 4) ? $token : self::__decryptedToken($token);
       
        if (!(strpos($token_data, "-") === false)) {
			$token_parts = explode("-", $token_data);
	        
	        $id = (isset($token_parts[0]))? $token_parts[0] : false ;
	        $password = (isset($token_parts[1]))? $token_parts[1] : false ;
	        $type = (isset($token_parts[2]))? $token_parts[2] : false ;
			
			$field_id = $user_type . "_id";
	        $table_field = $user_type . "." . $field_id;

			if( ($id != false) && ($password != false) && ($user_type === $type) ){
				$sql = "SELECT " . $table_field . " FROM " . $user_type . " WHERE " . $table_field . " = '" . $id . "' AND " . $user_type . ".password = '" . $password . "' LIMIT 1 ";
	        	$user_id = $DbConnection->getValue($sql);  
	        	// echo $table_field . " = " . $user_id . " \n ";
	        	// echo $sql . " \n ";
			}
			
		} 

		return $user_id;
    }

	public static function __isValidUserToken($token, $user_type, DbConnection $DbConnection = null) {
  		$user_id = self::__getUserIdByToken($token, $user_type, $DbConnection);
  		
  		return ($user_id) ? true : false;
  	}

	public static function __encryptedToken($string_token) {
	  	$result = null;
	  	$token_key = TOKEN_KEY;

	  	for( $i = 0; $i < strlen($string_token); $i++) {
	      	$char    = substr($string_token, $i, 1);
	      	$keychar = substr($token_key, ($i % strlen($token_key))-1, 1);
	      	$ordChar = ord($char);
	      	$ordKeychar = ord($keychar);
	      	$sum     = $ordChar + $ordKeychar;
	      	$char    = chr($sum);
	      	$result .= $char;
	  	}
	  	
	  	$result = base64_encode($result); 
	  	$result = str_replace( "=" , "" , $result );
	  	$result = str_replace( "+" , "|spider|" , $result );
		return $result;
	}

	public static function __decryptedToken($string_token) {
		$result = null;
		$token_key = TOKEN_KEY;
		$string_token = str_replace("|spider|" , "+", $string_token);
		$string_token = base64_decode($string_token);

		for( $i=0; $i<strlen($string_token); $i++) {
		    $char    = substr($string_token, $i, 1);
		    $keychar = substr($token_key, ($i % strlen($token_key))-1, 1);
		    $ordChar = ord($char);
		    $ordKeychar = ord($keychar);
		    $sum     = $ordChar - $ordKeychar;
		    $char    = chr($sum);
		    $result .= $char;
		}
		
		return $result;
	}

	public static function __getTokenParts($token) {
    	$token_parts = array();
    	$token_array = explode("-", $token);
        $token_data = (count($token_array) === 4) ? $token : self::__decryptedToken($token);
       
        if (!(strpos($token_data, "-") === false)) {
			$token_array = explode("-", $token_data);
	        
	        $token_parts["user_id"] 	= (isset($token_array[0]))? $token_array[0] : false ;
			$token_parts["password"] 	= (isset($token_array[1]))? $token_array[1] : false ;
	        $token_parts["user_type"] 	= (isset($token_array[2]))? $token_array[2] : false ;
			$token_parts["start_date"] 	= (isset($token_array[3]))? $token_array[3] : false ;
			$token_parts["end_date"] 	= (isset($token_array[4]))? $token_array[4] : false ;
		} 

		return $token_parts;
    }


	// ------------------------------ //////////// --------------------------------- //
    // -------------------------  PERMISSION FUNCTIONS ----------------------------- //
    // ------------------------------ //////////// --------------------------------- //    
	public static function __hasPermissionByToken($module, $permission, $token, $user_type, DbConnection $DbConnection = null) {
  		$DbConnection = ($DbConnection == null) ? DbConnection::getInstance("_root") : $DbConnection ;
  		
  		if( $user_id = self::__getUserIdByToken($token, $user_type, $DbConnection) )
  		{
  		 	return self::__hasPermission($module, $permission, $user_id, $user_type, $DbConnection);
  		}
  		
		return false;
  	}

  	public static function __hasPermission($module, $permission, $user_id, $user_type, DbConnection $DbConnection = null) {
  		$DbConnection = ($DbConnection == null) ? DbConnection::getInstance("_root") : $DbConnection ;
  		
  		$sql= "SELECT 
  					permission_per_" . $user_type . ".permission_per_" . $user_type . "_id 
  			   FROM 
  			   		catalog_module, catalog_module_permission, " . $user_type . " 
  			   WHERE 
  			   		catalog_module.user_type = " . $user_type . " 
  			   AND
  			   		catalog_module_permission.catalog_module_id = catalog_module.catalog_module_id 
  			   AND
  			   		permission_per_" . $user_type . ".catalog_module_permission_id = catalog_module_permission.catalog_module_permission_id
  			   AND
  			   		catalog_module.module = '" . $module . "' 
  			   AND
  			   		catalog_module_permission.permission = '" . $permission . "' 
  			   AND 
  			   		permission_per_" . $user_type . "." . $user_type . "_id = '" . $user_id . "' 
  			   AND 
  			   		permission_per_" . $user_type . ".active = '1' ";
  		
		return ($DbConnection->getValue($sql)) ? true : false;
  	}

	// ------------------------------ //////////// --------------------------------- //
    // --------------------  CREATE DYNAMIC SECRET FUNCTIONS ----------------------- //
    // ------------------------------ //////////// --------------------------------- //    
	public static function __createSecret($table, $field, $type_x = "", $long = 15, $case_sensitive = false, DbConnection $DbConnection = null) {
  		$DbConnection = ($DbConnection == null) ? DbConnection::getInstance("_root") : $DbConnection ;
  		
	  	$done = false ;
	 	$code = "";
		$random = mt_rand(2, $long);
		$possible = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_";
	 	
		do {
			for ($i=0; $i<$long; $i++){
				$char = substr($possible, mt_rand(0, 63), 1);
			  	$code .= $char;
			  	if(strlen($code)==$random)
 {
			  		$code .=$type_x;
			  	}
			}
				
			$code = ($case_sensitive) ? strtolower($code) : $code ;
			$sql = "SELECT " . $field . " FROM " . $table . " WHERE " . $field . "='" . $code . "' LIMIT 1";
				
			if (!$DbConnection->getValue($sql)) {
			  $done = true;
			}
	
		} while ( !$done );
		 
		return trim($code);
	}
	
	
	
	
	// ------------------------------ //////////// --------------------------------- //
    // -------------------------  LANGUAGE FUNCTIONS ------------------------------ //
    // ------------------------------ //////////// --------------------------------- //    
	public static function __setLanguage($language) {
		if( !defined("LANGUAGE") ) { 
			define("LANGUAGE", $language); 
		}

		$_SESSION["language"] = $language;
		
		return true;
	}

	public static function __getLanguage() {
		if( !defined("LANGUAGE") ) { 
			$ConfigSystem = Config::getInstance();
			define("LANGUAGE", $ConfigSystem->__system["LANGUAGE"]);
		}

		$_SESSION["language"] = LANGUAGE;
		
		return $_SESSION["language"];
	}

	public static function __getCatalogLanguages() {
		return Dictionary::getDictionaries();
	}

	public static function __T($str, $section = false, $language = LANGUAGE) {	
		return self::__Translate($str, $section, $language);
	}
	
	public static function __Translate($str, $section = false, $language=LANGUAGE) {	
		$Dictionary = Dictionary::getInstance($language);
		$Dictionary->load();
		return $Dictionary->translate($str, $section);
    }
    
    
	// ------------------------------ //////////// --------------------------------- //
    // --------------------------  LOGOUT FUNCTIONS -------------------------------- //
    // ------------------------------ //////////// --------------------------------- //    
	public static function __sessionDestroy() {
		$_SESSION = array();
		
		if( !empty($_SESSION)) {	
			unset($_SESSION);
			session_destroy();
		    session_start();
		}
		
		return true;
	}
	

	// ------------------------------ //////////// --------------------------------- //
    // -----------------------------  DATE FUNCTIONS ------------------------------- //
    // ------------------------------ //////////// --------------------------------- //    
	
	/**
	 * Returns date and to convert for a date format
	 * @return varchar date
	 */
	public static function __getFormatDate($date, $format = false, $style = "d-m-Y") {
		$style = ($style) ? $style : "d-m-Y" ;
		$date = (int)$date;
		switch ($format) {
	  		case "time":
	  			$new_date = ($date) ? date("H:i:s", $date) : date("H:i:s", time());
	  			break;
	  		
			case "with_time":
	  			$new_date = ($date) ? date($style . " H:i:s", $date) : date($style . " H:i:s", time());
				break;
		
			case "am/pm":
				$hour = date("H", $date);
				$new_date = ($date) ? date($style, $date) : date($style);
				$new_date = ($hour <= 12) ? $new_date . " AM" : $new_date . " PM";
				break;
			
	  		case "short":
				$new_date["d"] =  date("d", $date);
				$new_date["m"] =  date("m", $date);
				$new_date["y"] =  date("Y", $date);
				
				break;
			default:
				$new_date = ($date) ? date($style, $date) : date($style, time());
				break;
	  	}
	    return $new_date;
	}

	public static function __getSpiderDate($string_date, $style = "Y-mm-dd h:i:s", $type = "") {
		switch ($style) {
	  		default: 
			case "Y-mm-dd am/pm"://2018-01-08 8:16 AM1/A1
	  		case "Y-mm-dd h:i am/pm"://2018-01-08 8:16 AM1/A1
			case "Y-mm-dd h:i AM/PM"://2018-01-08 8:16 AM1/A1
									 //2018-03-14 10:30 PM
				$date_parts = explode(" ", $string_date);
	  			$date = (!empty($date_parts[0]))? explode("-", $date_parts[0]) : false;
				$time = (!empty($date_parts[1]))? explode(":", $date_parts[1]) : false;
				$case = (!empty($date_parts[2]))? "-" . $date_parts[2] : false;
	  			
  				$new_date["y"] = (!empty($date[0]))? $date[0] : date("Y");
				$new_date["m"] = (!empty($date[1]))? $date[1] : date("m");
				$new_date["d"] = (!empty($date[2]))? $date[2] : date("d");
				$new_date["i"] = (!empty($time[1]))? (int)$time[1] : date("i");
				$new_date["h"] = (strrpos($case, "PM") != false )? (int)$time[0] + 12 : (int)$time[0] ;
				
				$string_date = mktime($new_date["h"],$new_date["i"],0,$new_date["m"],$new_date["d"],$new_date["y"]);
	  			break;
			
			case "Y-mm-dd h:i:s"://2016-01-24T06:00:00.000Z
	  			$string_date = str_replace(".", "", $string_date);
	  			$date_parts = explode("T", $string_date);
	  			$date = (!empty($date_parts[0]))? explode("-", $date_parts[0]) : false;
	  			$time = (!empty($date_parts[1]))? explode(":", $date_parts[1]) : false;
	  			

  				$new_date["y"] = ($date[0])? $date[0] : date("Y");
				$new_date["m"] = ($date[1])? $date[1] : date("m");
				$new_date["d"] = ($date[2])? $date[2] : date("d");
				$new_date["h"] = ($time[0])? $time[0] : date("H");
				$new_date["i"] = ($time[1])? $time[1] : date("i");
				$new_date["s"] = ($time[2])? (int)$time[2] : date("s");
	  			
	  			$string_date = mktime($new_date["h"],$new_date["i"],$new_date["s"],$new_date["m"],$new_date["d"],$new_date["y"]);
	  			($type == "start")? $string_date = mktime(0,0,0, $new_date["m"],$new_date["d"],$new_date["y"]) : false;
	  			($type == "end")? $string_date = mktime(23,59,59, $new_date["m"],$new_date["d"],$new_date["y"]) : false;
				  break;

			case "Y-mm-dd":
			case "dd/mm/yy":
	  		case "dd-mm-yy":
	  		case "yy-mm-dd":
	  		case "yyyy-mm-dd":
	  			$date = array();

	  			if(strpos($string_date, "/"))
	  			{
	  				$date = explode("/", $string_date);
	  			} else if(strpos($string_date, "-")) {
	  				$date = explode("-", $string_date);
	  			}

	  			if(!empty($date[0]) && !empty($date[1]) && !empty($date[2]))
	  			{
	  				if($style = "yy-mm-dd" || $style = "yyyy-mm-dd")
	  				{
	  					$day    = $date[2];
			            $month  = $date[1];
			            $year   = $date[0];
	  				} else {
	  					$day    = $date[0];
			            $month  = $date[1];
			            $year   = $date[2];
	  				}
	  				
		            if((int)$date[1] > 12)
		            {
		            	$day = $date[1];
		            	$month = $date[0];
		            } 

	  				$string_date = mktime(0,0,0, (int)$month, (int)$day, (int)$year);		
		            ($type == "start")? $string_date = mktime(0,0,0, (int)$month, (int)$day, (int)$year) : false;
	  				($type == "end")? $string_date = mktime(23,59,59, (int)$month, (int)$day, (int)$year) : false;							
					//:: TO TEST $string_date = self::__getFormatDate($string_date, false, $style = "d-m-Y");
	  			}
	  			break;
	  			
	  	}

	    return $string_date;
	}

	public static function __getStartTime() { 
        list($usec, $sec) = explode(" ",microtime()); 

        return ((float)$usec + (float)$sec); 
  	} 
  
	public static function __getDdiferenceDate($start_date, $end_date) {
	    $difference = $end_date - $start_date;
		$days = ($difference)/86400;
		$days = abs($days); 
		$days = floor($days);		
		return $days;
	}

	public static function __diference_time($start_time, $end_time) {
		$difference = $end_time - $start_time;

		$hours = floor($difference / 3600);
    	$minutes = floor(($difference - ($hours * 3600)) / 60);
    	$seconds = $difference - ($hours * 3600) - ($minutes * 60);

    	return date("H:i:s", mktime($hours, $minutes, $seconds) );
	}

	public static function __getFormatNumber($number, $format = false, $currency = false, $currency_position = 1) {
		switch ($format) {
	  		default: 
	  			$decimals = 0; $decimalpoint = ""; $separator = "";
	  			break;

	  		case "1,000.00";
	  			$decimals = 2; $decimalpoint = "."; $separator = ",";
	  			break;

	  		case "1,000";
	  			$decimals = 0; $decimalpoint = "."; $separator = ",";
	  			break;

	  		case "1.000,00";
	  			$decimals = 2; $decimalpoint = ","; $separator = ".";
	  			break;
	  		
	  		case "1.000";
	  			$decimals = 0; $decimalpoint = ","; $separator = ".";
	  			break;
	  	}

	  	if($currency) {
	  		$new_number = ($currency_position)? $currency . number_format($number, $decimals, $decimalpoint, $separator) : number_format($number, $decimals, $decimalpoint, $separator). $currency_position;
	  	} else {
	  		$new_number = number_format($number, $decimals, $decimalpoint, $separator);
	  	}

	    return $new_number;
	}
	
	public static function __getBrowser($agent = fasle) {
		$browser = array("IE","OPERA","MOZILLA","NETSCAPE","FIREFOX","SAFARI","CHROME");
		$os = array("WIN","MAC","LINUX","ANDROID","IPHONE");
		$agent = (!$agent)? $_SERVER["HTTP_USER_AGENT"] : $agent;
	 
		# definimos unos valores por defecto para el navegador y el sistema operativo
		$info["browser"] = "OTHER";
		$info["os"] = "OTHER";
	 
		# buscamos el navegador con su sistema operativo
		foreach($browser AS $parent) {
			$s = strpos(strtoupper($agent), $parent);
			$f = $s + strlen($parent);
			$version = substr($agent, $f, 15);
			$version = preg_replace("/[^0-9,.]/","",$version);
			
			if($s){
				$info["browser"] = strtolower($parent);
				$info["version"] = $version;
			}
		}
	 
		# obtenemos el sistema operativo
		foreach($os AS $val) {
			if (strpos(strtoupper($agent), $val)!==false){
				$info["os"] = strtolower($val);
			}
		}
	 
		# devolvemos el array de valores
		return $info;
	}

	// ------------------------------ //////////// --------------------------------- //
    // -----------------------  NORMALIZE STRING FUNCTIONS ------------------------- //
    // ------------------------------ //////////// --------------------------------- //    
	public static function __normalizeString($string) {
	    $originals 	= "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ";
	    $modify 	= "aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr";
	    $string 	= utf8_decode($string);
	    $string 	= strtr($string, utf8_decode($originals), $modify);
	    $string 	= strtolower($string);
	    return utf8_encode($string);
	}

	// ------------------------------ //////////// --------------------------------- //
    // ------------------------  SEARCH NEXT FUNCTIONS ----------------------------- //
    // ------------------------------ //////////// --------------------------------- //    
	public static function __getNext($table, $current, &$next, &$previous, DbConnection $DbConnection = null) {
  		$DbConnection = ($DbConnection == null) ? DbConnection::getInstance("_root") : $DbConnection ;
  		
		$sql = "SELECT {$table}_id FROM {$table} ORDER BY {$table}_id DESC";
		
		if($last_id = $DbConnection->getValue($sql)) {
  			$next = ($current == $last_id) ? 1 : $current + 1;
  			$previous = ($current == 1) ? $last_id : $current - 1;
  			return true;
		}
		return false;
  	}


  	// ------------------------------ //////////// --------------------------------- //
    // ------------------------  GET CATALOGS FUNCTIONS ---------------------------- //
    // ------------------------------ //////////// --------------------------------- //    
	
	/**
  	 * Get all Countries on Array Pair
  	 * @param enum 1 or 0 $active
  	 * @param DbConnection $DbConnection
  	 */
	public static function __getCatalogCountries($active = "1", DbConnection $DbConnection = null) {
	  	$DbConnection = ($DbConnection == null) ? DbConnection::getInstance("_world") : $DbConnection ;
	  	$sql = "SELECT country_id, country FROM country";
	  	
	  	return $DbConnection->getPair($sql);
	}
	  
	/**
  	 * Get all States by country_id on Array Pair
  	 * @param integer $country_id is the country id for states
  	 * @param enum 1 or 0 $active
  	 * @param DbConnection $DbConnection
  	 */
	public static function __getCatalogStates($country_id, $active = "1", DbConnection $DbConnection = null) {
	  	$DbConnection = ($DbConnection == null) ? DbConnection::getInstance("_world") : $DbConnection ;
	  	$sql = "SELECT state_id, state FROM state WHERE country_id = '{$country_id}'";
	  	
	  	return $DbConnection->getPair($sql);
	}
	  
	/**
  	 * Get all cities by state_id on Array Pair
  	 * @param integer $state_id is the state id for cities
  	 * @param enum 1 or 0 $active
  	 * @param DbConnection $DbConnection
  	 */
	public static function __getCatalogCities($state_id, $active = "1", DbConnection $DbConnection = null) {
		$DbConnection = ($DbConnection == null) ? DbConnection::getInstance("_world") : $DbConnection ;
	  	$sql = "SELECT city_id, city FROM city WHERE state_id = '{$state_id}' ";

	  	return $DbConnection->getPair($sql);
	}

	public static function __getCatalogModule($active = "1", DbConnection $DbConnection = null) {
		return self::__getCatalog("catalog_module", "module", null, $active, $DbConnection);
	}
	
	public static function __getCatalogFormatDate($active = "1", DbConnection $DbConnection = null) {
	  	return self::__getCatalog("catalog_format_date", "format_date", null, $active, $DbConnection);
	}

	public static function __getCatalogFormatNumber(DbConnection $DbConnection = null) {
		return self::__getCatalog("catalog_format_number", "format_number", null, $active, $DbConnection);
	}

	public static function __getCatalogTimezone(DbConnection $DbConnection = null) {
		return self::__getCatalog("catalog_timezone", "timezone", null, $active, $DbConnection);
	}

	public static function __getCatalog($table, $field_value = null, $field_id = null, $active = null, DbConnection $DbConnection = null) {
		$DbConnection = ($DbConnection == null) ? DbConnection::getInstance("_root") : $DbConnection ;
	  	
		$field_id = (!$field_id) ? $table . "_id" : $field_id;
		$field_value = (!$field_value) ? str_replace("catalog", "", $table) : $field_value;
		
	  	switch($active) {
	  		case "0":
	  			$active_condition = " WHERE active = '0'";
	  			break;
	  		
	  		case "1":
	  			$active_condition = " WHERE active = '1'";
	  			break;
	  			
	  		case "all":
	  			$active_condition = " WHERE active <> ''";
	  			break;

	  		case "":
	  		default:
	  			$active_condition = "";
	  			break;
	  	}

	  	$sql = "SELECT " . $field_id . ", " . $field_value . "  FROM " . $table . " " . $active_condition;

	  	return $DbConnection->getPair($sql);
	}

	// ------------------------------ //////////// --------------------------------- //
    // ---------------------  GET FOREIGN DATA FUNCTIONS  -------------------------- //
    // ------------------------------ //////////// --------------------------------- //   
    public static function XML_decode($url) {   
	    $xml = file_get_contents($url);
	    foreach ($http_response_header as $header)
	    {   
	        if (preg_match('#^Content-Type: text/xml; charset=(.*)#i', $header, $m))
	        {   
	            switch (strtolower($m[1]))
	            {   
	                case 'utf-8':
	                    // do nothing
	                    break;

	                case 'iso-8859-1':
	                    $xml = utf8_encode($xml);
	                    break;

	                default:
	                    $xml = iconv($m[1], 'utf-8', $xml);
	            }
	            break;
	        }
	    }

	    return simplexml_load_string($xml);
	}


	public static function __doRequest($url, $data = false) {
		$params = array("http" => array(
			"method" => "POST",
			"header"  => "Content-type: application/x-www-form-urlencoded",
			"content" => http_build_query($data)
		));
		
		$context = stream_context_create($params);
		$returnData = file_get_contents($url, false, $context);
		
		return $returnData;
	}

	public static function __urlExists( $url = NULL ) {
	    if(( $url != "" ) || ( $url != NULL ) )
	    {
	        $handle = curl_init($url);

		    if($handle != false)
		    {
		    	$headers = @get_headers($url); 
		    	
		    	//self::__displayVariable($headers[0]);
				return is_array($headers) ? preg_match("/^HTTP\\/\\d+\\.\\d+\\s+2\\d\\d\\s+.*$/", $headers[0]) : false; 
		    }
	    }

	    return false;
	}

	
	// ------------------------------ //////////// --------------------------------- //
    // ---------------------  FILES & FOLDERS FUNCTIONS  --------------------------- //
    // ------------------------------ //////////// --------------------------------- //
	public static function __deleteFile($file_path, $full_path = false) {
		if($full_path == true) {
			$new_path = $file_path;
		} else {
			$new_path = TO_ROOT . $file_path;
		}

		if(file_exists($new_path)) {
			unlink($new_path);
			$returnData["success"] = true;
		}
		
		$returnData["new_path"] = $new_path;
		$returnData["file_path"] = $file_path;
		$returnData["full_path"] = $full_path;
		
		return $returnData;
	}
	
	public static function __createFolder($path, $folder) {
		if(!is_dir($path . "/" . $folder))  { 
			if( @mkdir($path . "/" . $folder, 0777) ){
				return true;
			}
		}

		return false;
	}
	
	public static function __getFolders($path, $hidden_files) {
		$folders = array();
	  	$library = opendir($path);
			
		while ($folder = readdir($library)) {
		  	if(!is_file($folder)) {
		  		if($folder != in_array($folder, $hidden_files) )
		  		{
		  			$folders[] = $folder;
		  		}
		  	}
		}
		
		closedir($library); 
		return $folders;	  
  	}
  	
	public static function __getApps() {
		$admin_apps = null;
        $apps_path = TO_ROOT . "/apps";
        $hidden_files = array(".", "..", ".DS_Store", ".svn");
		
        if ( $apps = self::__getFolders($apps_path, $hidden_files) )
        {
            foreach ( $apps AS $app ) 
            {
                $admin_apps[$app]["folder"] = $app;
                $admin_apps[$app]["title"]  = ucfirst( str_replace("_", " ", $app) );
                
                if ( file_exists( $apps_path . "/" . $app . "/" . $app . ".php" ) ) 
                {
                	$admin_apps[$app]["path"] = $apps_path . "/" . $app . "/" . $app . ".php";
                } else if ( file_exists( $apps_path . "/" . $app . "/index.php" ) ){
                    $admin_apps[$app]["path"] = $apps_path . "/" . $app . "/index.php";
                } else {
                    unset($admin_apps[$app]);
                }
            }

            return $admin_apps;
        }

        return false;  
  	}

	// ------------------------------ //////////// --------------------------------- //
    // ---------------------------  IMAGE FUNCTIONS -------------------------------- //
    // ------------------------------ //////////// --------------------------------- //    
	public static function __deleteImage($file_path, $full_path = false) {
		return self::__deleteFile($file_path, $full_path);
	}

	public static function __uploadImage($image, $options) {
		return self::__createResizedImage($image, $options);
	}

	public static function __createResizedImage($image, $options) {
		$defaults = array(
			"name" => "",
			"upload_path" => null,
            "width" => 768,
            "height" => 600,
            "max_width" => 1280,
            "max_height" => null,
            "max_file_size" => null,
            "min_file_size" => 1,
            "accept_file_types" => "/.+$/i",
            "thumbnail" => false,
            "thumbnail_values" => array(
            							"width" => 100, 
            							"height" => null,
            							"max_width" => 150,
            							"max_height" => null,
            							"thumbnail_folder" => "thumbnail/"
            							),
		);
		
		$options = array_merge($defaults, $options);
		
		$image_name = $image["name"];
		$image_type = $image["type"];
		$image_size = $image["size"]; 
		$image_temp = $image["tmp_name"]; 
		$image_name_no_blanks = str_replace(" ", "", trim($image["name"]));

		$accept_file_types = array("jpg", "jpeg", "png", "gif", "image/jpg", "image/jpeg", "image/png", "image/gif", "image/bmp");
		
		if( !in_array($image_type, $accept_file_types))  {
			return false;
		} 

		if(!is_dir($options["upload_path"])) { 
			@mkdir($options["upload_path"], 0777, true);
			@mkdir($options["upload_path"] . $options["thumbnail_values"]["thumbnail_folder"] , 0777); 
		}

		list($width, $height) = @getimagesize($image_temp);

		$random = mt_rand(1, 99);
		$new_image = @imagecreate($width, $height);
		
		$date = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
		$new_name = strtolower(strstr($options["name"], ".", true)) . $date . "_" . $random . "." . strtolower(substr(strrchr($image_name, '.'), 1));
		
		$new_width = ($options["width"] < $options["max_width"]) ? $options["width"] : $options["max_width"];
		$new_height = ($new_width / $width) * $height;

		$thumbnail_width = ($options["thumbnail_values"]["width"] < $options["thumbnail_values"]["max_width"]) ? $options["thumbnail_values"]["width"] : $options["thumbnail_values"]["max_width"];
		$thumbnail_height = ($thumbnail_width / $width) * $height;

		/** * Fix with this
		if($width < $height {
			$new_width = ($options["width"] < $options["max_width"]) ? $options["width"] : $options["max_width"];
			$new_height = ($new_width / $width) * $height;
		} else {
			$new_height = ($options["height"] < $options["max_height"]) ? $options["height"] : $options["max_height"];
			$new_width = ($new_height / $height) * $width;
		}/** */

		$resized_image = @imagecreatetruecolor($new_width, $new_height);
		$thumbnail_image = @imagecreatetruecolor($thumbnail_width, $thumbnail_height);
		
		switch (strtolower(substr(strrchr($image_name, '.'), 1))) {
			case "jpg":
			case "jpeg":
				$original_image = @imagecreatefromjpeg($image_temp);
				$image_method = "imagejpeg";
				break;
			case "gif":
				$original_image = @imagecreatefromgif($image_temp);
				$image_method = "imagegif";
				break;
			case "png":
				$original_image = @imagecreatefrompng($image_temp);
				$image_method = "imagepng";
				break;
			default:
				$image_method = null;
		}

		@imagecopyresampled($resized_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		@imagecopyresampled($thumbnail_image, $original_image, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $width, $height);
		//imagecopyresized($resized_image, $original_image, 0,0,0,0, $new_width, $new_height, $width, $height);

		switch($image_method) {
			case "imagejpeg":
				@imagejpeg($resized_image, $options["upload_path"] . $new_name);
				if($options["thumbnail"] == true)
				{
					@imagejpeg($thumbnail_image, $options["upload_path"] . $options["thumbnail_values"]["thumbnail_folder"] . $new_name);	
				}
				break;
			case "imagegif":
				@imagegif($resized_image, $options["upload_path"] . $new_name);
				if($options["thumbnail"] == true)
				{
					@imagegif($thumbnail_image, $options["upload_path"] . $options["thumbnail_values"]["thumbnail_folder"] . $new_name);	
				}
				break;
			case "imagepng":
				@imagepng($resized_image, $options["upload_path"] . $new_name);
				if($options["thumbnail"] == true)
				{
					@imagepng($thumbnail_image, $options["upload_path"] . $options["thumbnail_values"]["thumbnail_folder"] . $new_name);	
				}
				break;
			default:
				$image_method = null;
		}
		
		@imagedestroy($resized_image);	
		@imagedestroy($original_image);
		
		return $new_name;
	}

	public static function __isValidMail($mail) {
  		return (filter_var($mail, FILTER_VALIDATE_EMAIL)) ? true : false;
  	}


  	/* backup the db OR just a table */
	public static function __databaseBackup($tables = '*', $file = null, $folder = null, DbConnection $DbConnection = null) {
	  	$DbConnection = ($DbConnection == null) ? DbConnection::getInstance("_root") : $DbConnection ;
		$folder = (!$folder)? TO_ROOT . "/subcore/storage" : $folder;
		$now = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
		$today = date("H:i:s d-m-Y");

		(!file_exists($folder))? @mkdir($folder, 0777, true) : true;

		if(file_exists($folder)) {	
			$info = $DbConnection->getDataInfo();
			$file = (!$file)? "backup-" . $info["name"] . "-" . $now . ".sql" : $file ;
			$file_path = $folder . "/" . $file;
			$i = 1;
			
			$data = "";
			$data = "-- spiderframe SQL Dump";
			$data.= "\n-- " . $today;
			$data.= "\n-- ";
			$data.= "\nSET SQL_MODE=\"NO_AUTO_VALUE_ON_ZERO\";\n";
			$data.= "\n--";
			$data.= "\n-- Data base: `" . $info["name"] . "`";
			$data.= "\n--\n";
			$data.= "\n-- --------------------------------------------------------";
			$data.= "\n-- --------------------------------------------------------\n";

			// $data.= "\n--";
			// $data.= "\n-- Drop database: `" . $info["name"] . "`";
			// $data.= "\n--\n";
			// $data.= "\nDROP DATABASE IF EXISTS `" . $info["name"] . "`;";
			// $data.= "\nCREATE DATABASE `" . $info["name"] . "`;";
			// $data.= "\nUSE `" . $info["name"] . "`;";
			// $data.= "\n-- --------------------------------------------------------";
			// $data.= "\n-- --------------------------------------------------------\n";

			//get all of the tables
			if($tables == '*'){
				$tables = array();
				$tables = $DbConnection->getColumn("SHOW TABLES");
			} else {
				$tables = is_array($tables) ? $tables : explode(",", $tables);
			}
			
			//:: cycle through
			foreach($tables AS $table){
				//:: Table structure
			 	$data.= "\n-- " . $i++;
			 	$data.= "\n--";
				$data.= "\n-- Table structure to table `" . $table . "`";
				$data.= "\n--\n";
				$data.= "\nDROP TABLE IF EXISTS `" . $table . "`;\n";
			 	$row_create = $DbConnection->getRow("SHOW CREATE TABLE ".$table);
			 	$data.= $row_create["Create Table"].";\n";
				
			 	//:: Data structure
				$data.= "\n--";
				$data.= "\n-- Dump the database for the table `" . $table . "`";
				$data.= "\n--\n\n";
				$result = $DbConnection->getAll("SELECT * FROM " . $table);
				
				foreach ($result AS $key => $values)
				{
					$field_value = array();
					$data.= "INSERT INTO " . $table . " VALUES(";
					
					foreach ($values AS $key_value => $value) 
					{
						$value = str_replace("'", "\'", $value);
						$field_value[$key_value] = "'" . $value . "'";
						//$field_value[$key_value] = (is_numeric($value))? $value : "'" . $value . "'";
					}

					$data.= implode(",", $field_value);
					$data.= ");\n";
				}

				$data.= "\n-- --------------------------------------------------------";
				$data.= "\n-- --------------------------------------------------------";
				$data.= "\n\n";
			}
			
			//:: save file
			$handle = fopen($file_path, "w+");
			fwrite($handle, $data);
			fclose($handle);
			
			if(!file_exists($folder . "/index.html")){
				$handle = fopen($folder . "/index.html", "w+");
				fwrite($handle, "");
				fclose($handle);
			}

			if(file_exists($file_path)){
				return $file_path;
			}
		}

		return false;
	}

	public static function __separateNames($full_name) {
  		/* separar el nombre completo en espacios */
		$tokens = explode(" ", trim($full_name));
		/* arreglo donde se guardan las "palabras" del nombre */
		$names = array();
		/* palabras de apellidos (y nombres) compuetos */
		$special_tokens = array("da", "de", "del", "la", "las", "los", "mac", "mc", "van", "von", "y", "i", "san", "santa");

		$prev = "";
		foreach($tokens AS $token) {
			$_token = strtolower($token);
			if(in_array($_token, $special_tokens)) {
				$prev .= $token . " ";
			} else {
				$names[] = $prev . $token;
				$prev = "";
			}
		}

		$num_names = count($names);
		$first_names = $lastname = $mother_lastname = "";
		
		switch ($num_names) {
			case 0:
				$first_names = "";
				break;
			case 1: 
				$first_names = $names[0];
				break;
			case 2:
				$first_names    = $names[0];
				$lastname  = $names[1];
				break;
			case 3:
				$first_names = $names[0];
				$lastname = $names[1];
				$mother_lastname = $names[2];
				break;
			case 4:
				$first_names = $names[0] . " " . $names[1] ;
				$lastname = $names[2];
				$mother_lastname = $names[3];
				break;
		}

		$the_name = array ( "names" => mb_convert_case($first_names, MB_CASE_TITLE, "UTF-8"),
							"lastname" => mb_convert_case($lastname, MB_CASE_TITLE, "UTF-8"),
							"mother_lastname" => mb_convert_case($mother_lastname, MB_CASE_TITLE, "UTF-8")
						  );
		return $the_name;
	  }

	/* Convert a number to string */
	  
	private static function __Units($number){
		switch($number){
			case 1: $return_data = "UN";
				break;
			case 2: $return_data = "DOS";
				break;
			case 3: $return_data = "TRES";
				break;
			case 4: $return_data = "CUATRO";
				break;
			case 5: $return_data = "CINCO";
				break;
			case 6: $return_data = "SEIS";
				break;
			case 7: $return_data = "SIETE";
				break;
			case 8: $return_data = "OCHO";
				break;
			case 9: $return_data = "NUEVE";
				break;
			default: $return_data = "";
				break;
		}
		return $return_data;
	}

	private static function __Tens($number){
		$tens = floor($number / 10);
		$unity = $number - ($tens * 10);

		switch($tens){
			case 1:
                switch($unity) {
					case 0: $return_data = "DIEZ";
					break;
					case 1: $return_data = "ONCE";
					break;
					case 2: $return_data = "DOCE";
					break;
					case 3: $return_data = "TRECE";
					break;
					case 4: $return_data = "CATORCE";
					break;
					case 5: $return_data = "QUINCE";
					break;
					default: $return_data = "DIECI" . self::__Units($unity);
					break;
				}
			break;
			case 2:
				switch($unity) {
					case 0: $return_data = "VEINTE";
					break;
					default: $return_data = "VEINTI" . self::__Units($unity);
					break;
				}
			break;
			case 3: $return_data = self::__TensY("TREINTA", $unity);
			break;
			case 4: $return_data = self::__TensY("CUARENTA", $unity);
			break;
			case 5: $return_data = self::__TensY("CINCUENTA", $unity);
			break;
			case 6: $return_data = self::__TensY("SESENTA", $unity);
			break;
			case 7: $return_data = self::__TensY("SETENTA", $unity);
			break;
			case 8: $return_data = self::__TensY("OCHENTA", $unity);
			break;
			case 9: $return_data = self::__TensY("NOVENTA", $unity);
			break;
			case 0: $return_data = self::__Units($unity);
			break;			
			default: $return_data = "";
				break;
		}
		return $return_data;
	}

	private static function __TensY($strSin, $numUnits){
        return ($numUnits > 0)? $strSin . " Y " . self::__Units($numUnits) : $strSin;
	}
	
	private static function __Hundreds($number){
        $hundreds = floor($number / 100);
        $tens = $number - ($hundreds * 100);
    
        switch($hundreds) {
            case 1:
                ($tens > 0) ? $return_data = "CIENTO " . self::__Tens($tens) : $return_data = "CIEN";
			break;
			case 2: $return_data ="DOSCIENTOS " . self::__Tens($tens);
			break;
			case 3: $return_data ="TRESCIENTOS " . self::__Tens($tens);
			break;
			case 4: $return_data ="CUATROCIENTOS " . self::__Tens($tens);
			break;
			case 5: $return_data ="QUINIENTOS " . self::__Tens($tens);
			break;
			case 6: $return_data ="SEISCIENTOS " . self::__Tens($tens);
			break;
			case 7: $return_data ="SETECIENTOS " . self::__Tens($tens);
			break;
			case 8: $return_data ="OCHOCIENTOS " . self::__Tens($tens);
			break;
			case 9: $return_data ="NOVECIENTOS " . self::__Tens($tens);
			break;
        }

		(!isset($return_data)) ? $return_data = self::__Tens($tens) : false;
		return $return_data;
	}
	
	private static function __Section($num = null, $divider = null, $strSingular = "", $strPlural = ""){
        $hundreds = floor($num / $divider);
        $rest = $num - ($hundreds * $divider);
        $letters = "";
    
        if($hundreds > 0){
            if ($hundreds > 1){
                $letters = self::__Hundreds($hundreds) . " " . $strPlural;
            } else {
                $letters = $strSingular;
            }
        }
        
        return ($rest > 0)? $letters . "" : $letters;
	}
	
	private static function __Thousands($number){
        $divider = 1000;
        $hundreds = floor($number / $divider);
        $rest = $number - ($hundreds * $divider);
        $strThousands = self::__Section($number, $divider, "UN MIL", "MIL");
        $strHundreds = self::__Hundreds($rest);
    
        return ($strThousands == "")? $strHundreds : $strThousands . " " . $strHundreds;
	}
	
	private static function __Millions($num) {
        $divider = 1000000;
        $hundreds = floor($num / $divider);
        $rest = $num - ($hundreds * $divider);
    
        $strMillions = self::__Section($num, $divider, "UN MILLON DE", "MILLONES DE");
        $strThousands = self::__Thousands($rest);
    
        return ($strMillions == "")?  $strThousands : $strMillions . " " . $strThousands;
	}
	
	public static function __numberToString($num) {
		$number = $num;
		$integer = floor($num);
		$cents = (((round($num * 100)) - (floor($num) * 100)));
		($cents == 0) ? $cents = "00" : false;
		$stringCents = "/100 M.N.";
		$stringCoins = 'PESOS';//"PESOS", 'Dólares', 'Bolívares', 'etcs'
		$stringCoin = 'PESO'; //"PESO", 'Dólar', 'Bolivar', 'etc'

		$lettersCoinCentPlural = "CENTAVOS";
		$lettersCoinCentSingular = "CENTAVO";
        
    
        // console.log(data.cents);
    
        // if (data.cents > 0) {
        //     data.stringCents = "CON " + (function (){
        //         if (data.cents == 1)
        //             return this.Millions(data.cents) + " " + data.lettersCoinCentSingular;
        //         else
        //             return this.Millions(data.cents) + " " + data.lettersCoinCentPlural;
        //         })();
        // };
    
        if($integer == 0){
            return "CERO " . $stringCoins . " " . $cents . $stringCents;
        }
        
        if ($integer == 1) {
            return self::__Millions($integer) . " " . $stringCoin . " " . $cents . $stringCents;
        } else {
            return self::__Millions($integer) . " " . $stringCoins . " " . $cents . $stringCents;
        }
    }


/** * 
  	public static function __validateLink($link) {
  		if(file_exists($link))
  		{
  			return $link;
  		} else if (file_exists(TO_ROOT . $link)){
  			return TO_ROOT . $link;
  		}

  		return $link;
  	}
/** */
}