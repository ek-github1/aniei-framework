<?php

/**
 * Include files based on the Instance's ClassName being created
 * Gets {@link Includes} from core/includes/
 * Gets {@link Includes} from core/functions/
 * Gets {@link Patterns} from core/patterns/
 * Gets {@link Config} from core/config/
 * Gets {@link Models} from core/models/
 * Gets {@link Subcore} from apps/subcore/models
 * Gets {@link Apps Models} from apps/{$app}/subcore/models/
 * Gets {@link Apps Functions} from apps/{$app}/subcore/functions/
 */

class Autoloader 
{	
	public static function autoload($class_name)
  	{   
  		$paths = array();
  		$app_paths = array();
  		$service_paths = array();

  		$file_name = $class_name . ".php";
	    $hidden_files = array(".", "..", ".DS_Store");
		
  		$path_apps = TO_ROOT . "/apps/";
  		$apps = self::getAppFolders($path_apps, $hidden_files);
	    
		$path_services = TO_ROOT . "/services/";
	    $services = self::getAppFolders($path_services, $hidden_files);
	    
	    $general_paths = array(
				        
				        TO_ROOT . "/subcore/config/",
				        TO_ROOT . "/subcore/models/",
				        
				        SPIDERFRAME . "/includes/",  
				        SPIDERFRAME . "/patterns/",
				        SPIDERFRAME . "/models/",
				        
				        TO_ROOT . "/includes/",  
				        TO_ROOT . "/config/",
				        TO_ROOT . "/models/",

	    );

		if($apps)
		{
		    foreach ($apps AS $app)
			{
				$app_paths[] = TO_ROOT . "/apps/" . $app . "/subcore/models/";
			}

			$paths = array_merge($app_paths, $general_paths);
		} else {
			$paths = $general_paths;
		}

		if($services)
		{
		    foreach ($services AS $service)
			{
				$app_paths[] = TO_ROOT . "/services/" . $service . "/models/";
			}

			$paths = array_merge($app_paths, $paths, $service_paths);
		} 

		foreach($paths AS $path) 
	    { 
	    	$file = $path . $file_name;
				
		    if( file_exists($file) ) 
		    {  	
		      	include_once $file;
		        return true;
		    } 
		}
		
	    return false;
  	}
  		
	/**
	 * Configure autoloading 
	 * This is designed to play nicely with other autoloaders.
	 */
	public static function registerAutoload()
	{
	    spl_autoload_register( array ("Autoloader", "autoload")  );
	}

	/**
	 * Configure registeredApp 
	 * This is designed to better performance.
	 */
	public static function registerApp()
	{
		//echo TOKEN_ID;
	    return true;
	}
	
	private static function getAppFolders($path, $hidden_files) 
	{	
		$folders = array();

		if(file_exists($path))
		{	
		  	$library = opendir($path);
				
			while ($folder = readdir($library))
			{
			  	if(!is_file($folder))
			  	{
			  		if($folder != in_array($folder, $hidden_files) )
			  		{
			  			$folders[] = $folder;
			  		}
			  	}
			}
			
			closedir($library); 
			return $folders;	
		}
		
		return false;
  	}
}