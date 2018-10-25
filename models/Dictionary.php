<?php 
/**
 * Holds the {@link Dictionary} Singleton
 * @author spiderMay <may.estilosfrescos@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package spiderFrame
 */

/**
 * Provides a Config abstraction in the form of a singleton
 */
class Dictionary
{

  public    $language = "";
  public 	$loaded = false;
  public    $data = array();
  public 	$section = array();
  public 	$sections = array();
  
  protected static $_instances = array();
  protected $_filepath = null;
  protected $_language = "";
  
    
   /**
    * Constructor is private so it can't be instantiated
    * @return Dictionary
    */
	public function __construct($language)
  	{
	    $this->language = $language;
	    $this->_language = $language;
		
	    if(file_exists("languages/" . $language . ".json"))
		{
			$this->_filepath = "languages/" . $language . ".json";
		} else if(file_exists(TO_ROOT . "/subcore/languages/" . $language . ".json"))
		{
			$this->_filepath = TO_ROOT . "/subcore/languages/" . $language . ".json";
		} else if(file_exists(TO_ROOT . "/spiderframe/languages/" . $language . ".json"))
		{
			$this->_filepath = TO_ROOT . "/spiderframe/languages/" . $language . ".json";
		} else {
			$this->createNewDictionary($language);
			$this->_filepath = TO_ROOT . "/subcore/languages/" . $language . ".json";
		}
	    
	    if ( !file_exists( $this->_filepath ) ) 
	    {
	      throw new RuntimeException("Couldn't load dictionary file: " . $this->_filepath);
	    }     
	}
	
  /**
   * Loads the dictionary language from an dic file into an array
   *
   * To override the default just call Dictionary::load($languaje) with your custom
   * languaje.
   * @param string $languaje
   * @return Dictionary
   */
  	public static function getInstance($language)
  	{
	    if ( !isset($_instances[$language]) || !(self::$_instances[$language] instanceof self) )
	    {
	      self::$_instances[$language] = new self($language);
	    }
	    return self::$_instances[$language];
	}
  	
  	public function getData()
  	{
  		if($this->loaded == false)
  		{
  			$this->load();
  		}

  		return $this->data;
  	}

  	public function getDictionary()
  	{
  		return $this->getData();
  	}


  	// ------------------------------ //////////// --------------------------------- //
    // -------------------------  TRANSLATE FUNCTIONS ------------------------------ //
    // ------------------------------ //////////// --------------------------------- //    
	public static function __T($str, $section = false, $language = LANGUAGE) 
	{	
		return self::__Translate($str, $section, $language);
	}
	
	public static function __Translate($str, $section = false, $language = LANGUAGE) 
	{	
		$Dictionary = self::getInstance($language);
		
		if( $Dictionary->loaded == false ) 
		{
			$Dictionary->load();
		}

		return $Dictionary->translate($str, $section);
    }

	public function translate($str, $section = null)
	{
		if($str)
		{
			if($this->loaded)
			{
				if(!$section)
				{
					return $this->searchText($str);
				} else {
					return $this->searchTextBySection($str, $section);
				}
			}
		}
		
		return $str;
	}

	public function load()
  	{
  		if($this->loaded == false)
  		{
  			if ($this->_filepath) 
			{
				
				$file_data = file_get_contents($this->_filepath);
				$rows = json_decode($file_data, true);
				
			   	if($rows)
				{
					foreach($rows AS $row => $data_row)
				    {
				    	if($data_row)
						{
							foreach($data_row AS $section => $values)
							{
								$i = 0;
								foreach($values AS $system_value => $translate_value)
								{
									$this->data[$section][$i]["system_value"] = $system_value;
						    		$this->data[$section][$i]["translate_value"] = $translate_value;
						    		$i++;
						    	}
							}

							$this->loaded = true;
							//Functions::__display($this->data);
				    	}
					}

				} else {
					$this->loaded = false;
					$this->data = array();
				}
			}
  		}
  	}

  	private function searchText($str)
	{
		if($str)
		{
			if($this->loaded)
			{ 
				foreach ($this->data AS $section => $section_values) 
				{
					foreach ($section_values AS $values) 
					{
						if(trim($str) == trim($values["system_value"]))
						{
							return ($values["translate_value"]) ? $values["translate_value"] : $str;
						}
					}
				} 
			}
		}
		return $str;
	}

	private function searchTextBySection($str, $section)
	{
		if($section)
		{
			if($str)
			{
				if($this->loaded)
				{
					if($this->data[$section])
					{
						foreach ($this->data[$section] AS $values) 
						{
							if(trim($str) == trim($values["system_value"]))
							{
								return $values["translate_value"];
							}
						} 
					}
				}
			}
		}
		return $str;
	}
































	
	public function getLine($id)
	{
		return $this->searchTextById($id);
	}
	
	public function getSections()
	{
		if($this->loaded)
		{
			$i = 1;
			foreach ($this->data AS $section => $values) 
			{
				$this->sections[$i++] = $section;
			}
		}
		
		return $this->sections;
	}
	
	public function getSection($section, $common_section = true)
	{
		if($section)
		{
			if($this->loaded)
			{
				if($common_section == true)
				{
					$this->section["common"] = $this->data["common"];
				}
				
				$this->section[$section] = $this->data[$section];
			}
		}
		
		return $this->section;
	}
	
	public function setTranslateValue($value, $id)
	{ 
		if($id)
		{
			return $this->setTextById($id, $value);
		} 
		return false;
	}
	
	public function setSystemValue($value, $id)
	{
		if($id)
		{
			return $this->setTextById($id, $value, false);
		}  
		return false;
	}
	
	public function addNewLine($section, $system_value, $translate_value = "")
	{ 
		if($section)
		{
			$section = strtolower($section);
			return $this->addLine($section, $system_value, $translate_value);
		} 
		return false;
	}
	
	public function deleteLine($id)
	{
		if($id)
		{
			if($this->loaded)
			{
				foreach ($this->data AS $section => $values) 
				{
					foreach ($values AS $value)
					{
						if($value["id"] == $id)
						{
							unset($this->data[$section][$id]);
							return true;
						}	
					}
				}
			}
		}
		return false;
	}
	
	


  	
	







	
	private function searchTextById($id)
	{
		if($id)
		{
			if($this->loaded)
			{
				foreach ($this->data AS $section => $values) 
				{
					foreach ($values AS $value)
					{
						if($value["id"] == $id)
						{
							$value["section"] = $section;
							return $value;
						}	
					}
				}
			}
		}
		return false;
	}
	
	
	private function setTextById($id, $text, $translate_value = true)
	{
		if($id)
		{
			if($this->loaded)
			{
				foreach ($this->data AS $section => $values) 
				{
					foreach ($values AS $value)
					{
						if($value["id"] == $id)
						{
							$key = ($translate_value) ? "translate_value" : "system_value" ;
							if($text)
							{
								$this->data[$section][$id][$key] = $text;
							}
							return true;
						}	
					}
				}
			}
		}
		return false;
	}
	
	private function addLine($section, $system_value, $translate_value = "")
	{ 
		$section = ($section) ? $section : "common" ;
		if($this->loaded)
		{
			if($system_value)
			{
				$this->data[$section][] = array("system_value" => $system_value, "translate_value" => $translate_value);
				return true;
			}
		}
		return false;
	}
	
  	private function prepareDataToSave()
  	{
  		$first = true;
  		if($this->data)
  		{
  			foreach ($this->data AS $section => $values)
  			{
  				$jump = (!$first) ? "\n" : "";
  				$data.= $jump . "[__" . $section . "]\n";
  				foreach ($values AS $value)
  				{
  					$data.= $value["system_value"] . "=>" . $value["translate_value"] . "\n";
  				}
  				$first = false;
  			}
  		}
  		return $data;
  	}
  	
	private function splitStrings($str, $parser = "=>") 
	{
        if($str !== "" && $str !== " " && $str !== "\n")
        {
        	return explode($parser,trim($str));
        }
       return false;
    }
    
	public function save()
  	{
	   	$data = $this->prepareDataToSave();
  		$openFile 	= fopen( $this->_filepath, "w");
				
	    if(fwrite($openFile, $data))
	    {
	    	fclose($openFile);
	    	return true;
	    }	
	    
	    fclose($openFile);
	  	return false;
  	}
  	

	private function createNewDictionary($language)
	{
		
		if(!file_exists(TO_ROOT . "/subcore"))
		{
			@mkdir(TO_ROOT . "/subcore", 0777); 
		}

		if(!file_exists(TO_ROOT . "/subcore/languages"))
		{
			@mkdir(TO_ROOT . "/subcore/languages", 0777); 
		}

		$library_path = TO_ROOT . "/subcore/languages";
		$library = opendir($library_path); 
		$dictionaries = array();
		
		while ($dictionary = readdir($library))
		{
				$fileExtension = explode ('.', $dictionary);
				if($fileExtension[0] && $fileExtension[1] == "dic")
				{
					if($fileExtension[0] == $language)
					{
						$reason = "FILEEXIST";
						return false;
					} 
				}		
		}
		
		closedir($library); 
		
		if(copy(TO_ROOT . "/spiderframe/languages/system.json", $library_path . "/" . $language . ".json"))
  		{
			$reason = "CREATED";
			return true;
		} else if($dictionary = fopen($library_path . "/" . $language . ".json", "a") ){
			fputs($dictionary, "[_metadata]\n");
			fputs($dictionary, "\n/**\n * Holds the {@link Dictionary} {$language}\n * @package spiderFrame\n * @author spiderMay <may.estilosfrescos@gmail.com>");
			fputs($dictionary, "\n * @copyright Copyright (c) 2010, spiderMay <may.estilosfrescos@gmail.com>\n * @license http://opensource.org/licenses/gpl-license.php GNU Public License");
			fputs($dictionary, "\n * Provides a Config abstraction in the form of a singleton\n */\n");
			fputs($dictionary, "\n\n\n[__common]\nHello=>Hello\n");
			fclose($dictionary);

			$reason = "CREATED";
			return true;
		} else { 
			$reason = "NOT_CREATED"; 
			return false;
		}
	}
	
	public static function getDictionaries() 
	{
	  	$library_path = TO_ROOT . "/subcore/languages";

		if(file_exists($library_path))
		{
			$i = 0 ;
			$dictionaries = array();
			$library = opendir($library_path);

			while ($dictionary = readdir($library))
			{
					$i++;
					$fileExtension = explode ('.', $dictionary);
					if($fileExtension[0] && $fileExtension[1] == "json")
					{
						$dictionaries[$fileExtension[0]] = $fileExtension[0]; 
					} 
			}
			
			if(file_exists(TO_ROOT . "/spiderframe/languages/system.json"))
			{
				$dictionaries["system"] = "system"; 
			}

			closedir($library); 
			return $dictionaries;
		}

		return false;
  	}

	public function deleteDictionary($language)
	{
		if($language != "system")
		{
			if(unlink($this->_filepath))
			{
				return true;
			}
		}
		return false;
	}
	
}
