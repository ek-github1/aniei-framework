<?php 

/**
  * Database Connection abstraction
  * Provides extremely useful functions for data retrieval, and other database affairs.
  * @package spiderFrame
  * 
  * @author Spidermay <may.estilosfrescos@gmail.com>
  * @package ThaFrame
  * @copyright Copyright (c) 2015, Spidermay <may.estilosfrescos@gmail.com>
  * @license http://opensource.org/licenses/gpl-license.php GNU Public License
  */
class DbConnection
{
	protected static $_instances = array();

	public $pdo  			= null;
	protected $db_instance 	= "";
	protected $db_password 	= "";
	protected $db_host     	= "";
	protected $db_user     	= "";
	protected $db_name     	= "";
	protected $error      	= "";
	protected $query	   	= "";
	protected $sql		   	= "";	

	private static $limit_paginate = 30;
	
	
	/**
	* Construct an object of the the DbConectionPDO
	* 
	* @param string $db_host
	* @param string $db_user
	* @param string $db_password
	* @param string $db_name
	* @param string $db_instance
	* @return object $pdo
	*/
	protected function __construct($db_host, $db_user, $db_password, $db_name, $db_instance)
	{
		$this->db_host     = $db_host;
		$this->db_user     = $db_user;
		$this->db_password = $db_password;
		$this->db_name     = $db_name;
		$this->db_instance = $db_instance;

		try
		{
			$this->pdo = new PDO( "mysql:host=" . $this->db_host . "; dbname=" . $this->db_name . "; port=3306; charset=utf8; ", $this->db_user, $this->db_password );
			
			//$this->execute("SET CHARACTER SET 'utf8'");
			//$this->pdo->exec("SET NAMES utf8");

		} catch (PDOException $exception) {
			$this->error = array("PDOException" => $exception);

			return false;
		}

		return $this->pdo;
	}

	/**
	* Gets an instance of the the DbConectionPDO
	* 
	* @param string $db_host
	* @param string $db_user
	* @param string $db_password
	* @param string $db_name
	* @return DbConectionPDO
	*/
	public static function getInstance($db_instance, $db_host="", $db_user="", $db_password="", $db_name="") 
	{
		/*if(empty($db_host) && !isset(self::$_instances[$db_instance])) 
		{
			$Config       = new Config("db_config", true);
			$db_host      = $Config->__DbConecction["db_host" . $db_instance];
			$db_user      = $Config->__DbConecction["db_user" . $db_instance];
			$db_password  = $Config->__DbConecction["db_password" . $db_instance];
			$db_name      = $Config->__DbConecction["db_name" . $db_instance];    
		}*/

		if ( !isset(self::$_instances[$db_instance]) ) 
		{
			$DbConectionPDO = new DbConnection($db_host, $db_user, $db_password, $db_name, $db_instance);
			self::$_instances[$db_instance] = $DbConectionPDO;
		} 

		return self::$_instances[$db_instance];
	}

	/**
  	* Get data connection
  	*
  	* @return array the info to connection
  	*/
	public function getDataInfo()
	{
		$data["host"] 		= $this->db_host; 
		$data["user"] 		= $this->db_user;
		$data["password"] 	= $this->db_password;
		$data["name"] 		= $this->db_name;
		$data["instance"] 	= $this->db_instance;

		return $data;
	}
  
  	/**
  	* Prepare an sql sentence
  	*
  	* @param string $sql
  	* @return void
  	*/
	public function prepare($sql = false, $fetch_style = false)
	{
		($sql)? $this->sql = $sql : false;
		//$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		//$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		try
		{
			$this->query = $this->pdo->prepare($this->sql);
		} catch( PDOException $exception ){
			$this->error = array("PDOException" => $exception);
			return false;
		}

		return true;
	}

	/**
  	* Execute an sql sentence, 
  	* "this is the master method to execute any sentences"
  	*
  	* @param string $sql
  	* @return void
  	*/
	public function execute($sql = false, $fetch_style = PDO::FETCH_ASSOC, $whole = true)
	{
		$result = array();
		$this->pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
			
		try
		{
			$this->prepare($sql);
			$this->query->execute();
			
			$result = ($whole == true)? $this->query->fetchAll($fetch_style) : $this->query->fetch($fetch_style) ;
			$this->clearCache();
			//Functions::__displayVariable( $result );
		} catch ( PDOException $exception ){
			$this->error = array("PDOException" => $exception);
			return false;
		}

		return $result;
	}

  	/**
  	* Execute an sql sentence
  	*
  	* @param string $sql
  	* @return string $result
  	*/
	public function executeQuery($sql = false, $fetch_style = PDO::FETCH_ASSOC, $whole = true)
	{
		return $this->execute($sql, $fetch_style, $whole);
	}

	/**
  	* Execute an sql sentence to be return an general array
  	*
  	* @param string $sql
  	* @return array $result
  	*/
	public function getAll($sql, $fetch_style = PDO::FETCH_ASSOC)
	{
		return $this->execute($sql, $fetch_style, true);
	}

	/**
  	* Execute an sql sentence to be return a row
  	*
  	* @param string $sql
  	* @return array $result
  	*/
	public function getRow($sql, $fetch_style = PDO::FETCH_ASSOC)
	{
		return $this->execute($sql, $fetch_style, false);
	}

	/**
  	* Execute an sql sentence to be return a value
  	*
  	* @param string $sql
  	* @return array $result
  	*/
	public function getValue($sql, $fetch_style = false)
	{
		$result = array();

		if($result = $this->execute($sql, $fetch_style, false))
		{
			return $result[0];
		}

		return false;
	}

	/**
  	* Execute an sql sentence to be return an array $key => $value 
  	*
  	* @param string $sql
  	* @return array $result
  	*/
	public function getPair($sql)
	{
		return $this->execute($sql, PDO::FETCH_KEY_PAIR, true);
	}

	/**
  	* Execute an sql sentence to be return a first column
  	*
  	* @param string $sql
  	* @return array $result
  	*/
	public function getColumn($sql)
	{
		return $this->execute($sql, PDO::FETCH_COLUMN, true);
	}

	/**
  	* Assign value to an variable
  	* 
  	* @Todo Hacer que esta funcion haga el llamado a su correspondiente
  	* @todo Hacer que llame el bindValue correcto
  	* @param string $variable
  	* @param string $value
  	* @return void
  	*/
	public function bindValue($variable, $value = false, $param_type = PDO::PARAM_STR)
	{
		//llamar a la funcion bindValue del PDO presentado en $this->query o $this->sql 
		//Functions::__displayVariable( $this->sql );
		//$this->query->bindValue(":$variable", $value, $param_type);
		//echo $this->query->bindValue(":$variable", $value);
		//Functions::__displayVariable( $this->query );

		if($this->sql)
		{
			$this->sql = str_replace(":" . $variable, $value, $this->sql);
		}	

		return $this->prepare();
	}

	/**
  	* Assign value to an variable
  	*
  	* @param string $variable
  	* @param string $value
  	* @return void
  	*/
	public function assign($variable, $value = false)
	{
		return $this->bindValue($variable, $value);
	}

	/**
  	* Founds the last insert id in run time  
  	*
  	* @return array $lastInsertId
  	*/
	public function getLastId()
	{
		return $this->pdo->lastInsertId();
	}

	/**
  	* Returns the object connection  
  	*
  	* @return array $pdo
  	*/
	public function getMysqlConnection() 
	{
		return $this->pdo;
	}

	/**
  	* Returns the instance name in run time
  	*
  	* @return array $db_instance
  	*/
	public function getInstanceName() 
	{
		return $this->db_instance;
	}

	/**
  	* Clear data in principal vars
  	*
  	* @return void
  	*/
  	public function clearCache()
  	{
  		$this->query->closeCursor();
  		$this->sql = "";
		$this->query = "";
		$this->error = "";

		return true;
  	}

  	/**
  	* Save in to table the values
  	* 
  	* @param string $table
  	* @param string $data
  	* @param int $id
  	* @return void
  	*/
	public function save($table, $data, $id = 0)
	{
		$return = false;

		// Si data es un array de key=>value
		if(is_array($data))
		{
			// Si tiene tabla validar que exista en la base actual
			if($table)
			{
				// Si la tabla existe validar que los campos existan en la tabla
				if($this->tableExist($table))
				{
					// Si los campos existen 
					if($this->fieldExist($table, $data))
					{
						// Se crea el string adecuado 

						if((int)$id > 0)
						{
							$this->sql = $this->getUpdateString($data);
							$this->sql = str_replace(":__id", $id, $this->sql);
						} else if( (int)$id == 0) {
							$this->sql = $this->getInsertString($data);
						}

						$this->sql = str_replace(":__table_name", $table, $this->sql);
						
						// Se ejecuta la consulta 
						$this->execute();

						$return = (!$id)? $this->getLastId() : $id;	
					} 

				} else {
					$this->error = array("TABLE_DOESNT_EXIST" => $table);
				}
			} else {
				$this->error = array("EMPTY_TABLE" => $table);
			}
		} else {
			$this->error = array("IS_NOT_ARRAY_DATA" => $data);
		}

		return $return;
	}

	/**
  	* Insert in to table the values
  	* 
  	* @param string $table
  	* @param string $data
  	* @return void
  	*/
	public function insert($table, $data)
	{
		// Si data es un array de key=>value
		if(is_array($data))
		{
			// Si tiene tabla validar que exista en la base actual
			if($table)
			{
				// Si la tabla existe validar que los campos existan en la tabla
				if($this->tableExist($table))
				{
					// Si los campos existen 
					if($this->fieldExist($table, $data))
					{
						// Se crea el string adecuado 
						$sql = $this->getInsertString($data);
						$sql = str_replace(":__table_name", $table, $sql);
						
						// Se ejecuta la consulta 
						$this->execute($sql);
					} 

				} else {
					$this->error = array("TABLE_DOESNT_EXIST" => $table);
				}
			} else {
				$this->error = array("EMPTY_TABLE" => $table);
			}
		} else {
			$this->error = array("IS_NOT_ARRAY_DATA" => $data);
		}

		return $this->getLastId();
	}

	/**
  	* Insert in to table the values
  	* 
  	* @param string $table
  	* @param string $data
  	* @param int $id
  	* @return void
  	*/
	public function update($table, $data, $id)
	{
		$return = false;

		if($id >= 1)
		{
			// Si data es un array
			if(is_array($data))
			{
				// Si tiene tabla validar que exista en la base actual
				if($table)
				{
					// Si la tabla existe validar que los campos existan en la tabla
					if($this->tableExist($table))
					{
						// Si los campos existen 
						if($this->fieldExist($table, $data))
						{
							// Se crea el string adecuado 
							$sql = $this->getUpdateString($data);
							$sql = str_replace(":__table_name", $table, $sql);
							$sql = str_replace(":__id", $id, $sql);
							
							// Se ejecuta la consulta 
							$this->execute($sql);
							$return = $id;
						} 

					} else {
						$this->error = array("TABLE_DOESNT_EXIST" => $table);
					}
				} else {
					$this->error = array("EMPTY_TABLE" => $table);
				}
			} else {
				$this->error = array("IS_NOT_ARRAY_DATA" => $data);
			}
		} else {
			$this->error = array("EMPTY_ID" => $id);
		}

		return $return;
	}

	/**
  	* Create the string to mysql update 
  	* 
  	* @param array $data
  	* @return string
  	*/
	public function getUpdateString($data)
	{
		$sql_string = false;

		if(is_array($data))
		{
			$escaped_fields = array();

			foreach($data AS $field => $value)
			{
				//mysqli_real_escape_string($value)
				$escaped_fields[] = "`" . $field . "` = '" . $value . "'";
			}

          	$string_fields = implode(", ", $escaped_fields);
        
          	$sql_string = "UPDATE `" . $this->db_name . "`.`:__table_name` SET " . $string_fields . " WHERE `:__table_name`.`:__table_name_id` = :__id LIMIT 1; ";
		}

		return $sql_string;
	}

	/**
  	* Create trhe string to mysql insert 
  	* 
  	* @param array $data
  	* @return string
  	*/
	public function getInsertString($data)
	{
		$sql_string = false;

		if(is_array($data))
		{
			$escaped_fields = array();
			$escaped_values = array();
          	$fields = array_keys($data);
			$values = array_values($data);

			foreach($fields AS $field) 
			{
				$escaped_fields[] = "`" . $field . "`";
			}

			$string_fields = implode(", ", $escaped_fields);

			foreach($values AS $value)
			{
				//mysqli_real_escape_string($value)
				$escaped_values[] = "'" . $value . "'";
			}

			$string_values = implode(", ", $escaped_values);
        
          	$sql_string = "INSERT INTO `" . $this->db_name . "`.`:__table_name` (" . $string_fields . ") VALUES (" . $string_values . "); ";
		}

		return $sql_string;
	}

	/**
  	* Founds the table  
  	* 
  	* @param string $table
  	* @return bool
  	*/
	public function tableExist($table)
	{
		return ($this->getValue("SHOW TABLES LIKE '" . $table . "'"))? true : false;
	}
	
	/**
  	* Founds the table  
  	* Call the tableExist method 
  	* 
  	* @param string $table
  	* @return bool
  	*/
	public function rowExist($table_name)
    {
        return $this->tableExist($table);
    }

	/**
  	* Give if exist the table structure 
  	* 
  	* @param string $table
  	* @return array $structure
  	*/
	public function getTableStructure($table)
    {
        $structure = array();

	   	if($this->tableExist($table))
	   	{
	   		$fields = $this->execute("DESCRIBE " . $table );
	        
	        foreach ($fields AS $field)
	        {
	          	$structure[$field["Field"]] = $field;
	        }
	   	}

        return $structure;
    }

    /**
  	* Give if exist the table structure 
  	* 
  	* @param string $table
  	* @return array $structure
  	*/
    public function getStructure($table)
    {
    	return $this->getAll("DESCRIBE " . $table );
    }




    /**
  	* Gives the fields in table  
  	* 
  	* @param array $table
  	* @return array $fields
  	*/
    public function getFields($table)
    {
        $structure = $this->getTableStructure($table);
        
        foreach ($structure AS $field)
        {
            $fields[$field["Field"]] = $field["Field"];
        }

        return $fields;
    }

    /**
  	* Gives the fields in table  
  	* 
  	* @param array $table
  	* @return array $fields
  	*/
    public function getFieldStructure($table)
    {
        $structure = $this->getTableStructure($table);
        
        foreach ($structure AS $field)
        {
            $fields[$field["Field"]] = "";
        }

        return $fields;
    }

    /**
  	* Founds the fields in table  
  	* 
  	* @param array $table
  	* @param array $data
  	* @return bool
  	*/
	public function fieldExist($table, $data)
	{
		$success = true;
		$fields_collection = array();
		$structure = $this->getTableStructure($table);
		$structure = array_keys($structure);

		if(!is_array($data))
		{
		    if( strpos($data, ",") )
		    {
		        $fields_collection = $data;
		    } else {
		        $fields_collection[] = $data;
		    }
		} else {
		    $fields_collection = array_keys($data);
		}

		foreach($fields_collection AS $field)
		{   
		    $field = str_replace(" ", "", $field);
		    
		    if($success == true)
		    {
		    	if( !in_array($field, $structure) )
		    	{
					$success = false ;
					$this->error = array("FIELD_DOESNT_EXIST" => $field);
		    	} 
		    }
		}

		return $success;
	}

	/**
  	* Returns the error
  	*
  	* @return array $error
  	*/
	public function getError()
	{
		return $this->error;
	}

	/**
  	* Display the error
  	*
  	* @print array $error
  	*/
	public function displayError()
	{
		echo "<pre> Error: ";
		print_r($this->error);
		echo "</pre>";
	}

	/**
  	* Returns the error array to string
  	*
  	* @return array $string
  	*/
	public function getErrorString()
	{
		$string = "";

		foreach($this->error AS $error)
		{
		  $string .= $error . "\n";
		}

		return $string;
	}




	// ------------------------------ //////////// --------------------------------- //
 	// --------------------------- LISTING FUNCTIONS ------------------------------- //
  	// ----------------------------- //////////// ---------------------------------- //
    
    /**
  	* Returns an limked list, many tables all fields
  	*
  	* @return array collection rows
  	*/
	public function getList($page, $table_name, $extra = "", $limit = false)
	{
		$start = ((int)$page < 1 )? 0 : ( ($page - 1) * self::$limit_paginate) ;
		$limit = ($limit)? $limit : self::$limit_paginate ;
		
		if( strpos($table_name, ",") !== false )
		{  
			return self::getSimpleList($page, $table_name, $limit);
		}

		$sql = "SELECT * FROM " . $table_name . $extra . " LIMIT " . $start . ", " . $limit . "";
 		
		return $this->getAll($sql);
	}

	public function getTotal($table_name, $extra = "")
	{
		$sql = "SELECT COUNT(" . $table_name . "_id) FROM " . $table_name . $extra;
 		$returnData["sql"] =$sql;
		$returnData["total"] = $this->getValue($sql);
 		$returnData["pages"] = ceil($returnData["total"] / self::$limit_paginate);

		return $returnData;
	}

	/**
  	* Returns an simple list, one table all fields
  	*
  	* @return array collection rows
  	*/
    public function getSimpleList($page, $table_name, $limit = false)
	{
		$start = ((int)$page < 1 )? 0 : ( ($page - 1) * self::$limit_paginate) ;
        $limit = ($limit)? $limit : self::$limit_paginate ;

        if( strpos($table_name, ",") === false )
		{
			return self::getList($page, $table_name, $limit);
		} else {
			$sentence = self::getJoinSentence($table_name); 
			$sql = "SELECT * FROM " . $sentence . " LIMIT " . $start . ", " . $limit . " ";
			
			// return $this->getAll($sql);
		}
	}

	/**
  	* Returns an limked string like tables and fields
  	*
  	* @return string
  	*/
	public function getJoinSentence($table_name, $inner = false)
	{
		$sentence = "";
		$joined = array();
		$tables = array();
		$table_pices = array();
		$inner_sentence = ($inner)? "INNER " : "LEFT ";

		$table_name = trim(str_replace(" ", "", $table_name)); 
		$table_pices = explode(",", $table_name); 

		//$field_id = $table_name . "_id";

		if($table_pices)
		{ 	
			foreach ($table_pices AS $key => $table) 
			{
				$tables[$table] = $this->getColumn("show COLUMNS FROM " . $table);

				foreach ($tables[$table] AS $field) 
				{
					$joined[$table] = $inner_sentence . " JOIN " . $table . " ON " . $table . "." . $field . " = " . $table . "." . $table . "_id";

					// $name_table = str_replace("_id", "", $field);

					// if( ($table != $name_table)  && !isset($joined[$table]) && ($field == $field_id) )
					// {
					// 	if($table)
					// 	{
					// 		$joined[$table] = $inner_sentence . " JOIN " . $table . " ON " . $table . "." . $field . " = " . $table_name . "." . $field_id;
					// 	}
					// }

					// if( ($table != $name_table)  && (!empty($table_pices)) && (!in_array($table, $table_pices)) )
					// {
					// 	unset($table);
					// }
				}
			}
		}  //echo "<pre>"; print_r($joined); echo "</pre>";

		Functions::__displayVariable($joined);

		// $sentence = implode(" \n", $joined);

		// return $sentence;
	}

	/**
  	* Returns an limked string like tables and fields
  	*
  	* @return string
  	*/
	public function getStructureJoinedFields($table_name)
	{
		$tables = array();
		
		if( $db_structure = $this->getColumn("show tables"))
		{
			if($tables = self::getStructureJoinedTables($table_name) )
			{
				foreach ($tables AS $key => $table) 
				{ 
					foreach ($table["fields"] AS $field_id => $field) 
					{
						if( strpos($field, "_id") === false )
						{
							unset($tables[$key]["fields"][$field_id]);
						} else {
							$name_table = str_replace("_id", "", $field);

							if( !in_array($name_table, $db_structure) )
							{
								unset($tables[$key]["fields"][$field_id]);
							} 
						}
					}
				}
			} 
		}

		return $tables;
	}

	/**
  	* Returns structure one table
  	*
  	* @return string
  	*/
	public function getStructureJoinedTables($table_name)
	{
		
		$tables = array();
		$table_pices = explode("_", $table_name);
		$field_id = $table_name . "_id";
		$prefix = $table_pices[0];

		// if( $db_structure = $this->getColumn("show tables"))
		// {
		// 	foreach ($db_structure AS $id => $table) 
		// 	{
		// 		if( strpos($table, $prefix) !== false)
		// 		{
		// 			$tables[$id]["table"] = $table;
		// 			$tables[$id]["fields"] = $this->getColumn("show COLUMNS FROM " . $table);

		// 			if(!in_array($field_id, $tables[$id]["fields"]))
		// 			{
		// 				unset($tables[$id]);
		// 			}
		// 		}
		// 	}
		// }

		return $tables;
  	}
	
	// ------------------------------ //////////// --------------------------------- //
	// --------------------------- PAGINATE FUNCTIONS ------------------------------ //
	// ----------------------------- //////////// ---------------------------------- //
	
	/**
  	* Returns the self limit pagination
  	*
  	* @return int $limit_paginate
  	*/
    public function getLimitPaginate()
    {
        return self::$limit_paginate;

        return true;
    }

    /**
  	* Sets the self limit pagination
  	*
  	* @return int $limit_paginatevoid
  	*/
    public function setLimit($limit_paginate)
	{
		self::$limit_paginate = $limit_paginate;

		return true;
	}

	/**
  	* Gets the total rows in one table
  	*
  	* @return int total rows
  	*/
    public function getTotalRows($table_name, $active = "")
	{
		$active_sentence = ($active == 1)? " WHERE " . $table_name . ".active = '1'" : "";
		$active_sentence = ($active == 0)? " WHERE " . $table_name . ".active = '0'" : $active_sentence;
		$active_sentence = ($active == -1)?" WHERE " . $table_name . ".active = '-1'" : $active_sentence;

	    $sql= "SELECT COUNT(" . $table_name . "." . $table_name . "_id) FROM " . $table_name . $active_sentence;
	    
	    return $this->getValue($sql);
	}

	/**
  	* Gets the totals info
  	*
  	* @return array info
  	*/
    public function getPages($table_name, $page = 0)
	{	
		$returnData["active"]   = $this->getTotalRows($table_name, 1);
        $returnData["inactive"] = $this->getTotalRows($table_name, 0);
        $returnData["delete"]   = $this->getTotalRows($table_name, -1);  

        $returnData["total"]  = $returnData["inactive"] + $returnData["active"];
        $returnData["pages"] = ceil($returnData["total"] / self::$limit_paginate) ;
        
        $returnData["previous"] = ($page <= 0 ) ? 1 : $page -1;
		$returnData["page"] = ($page <= 0 ) ? 1 : $page;
		$returnData["next"] = ($page == 0 || $page == 1 ) ? 2 : $page + 1;
		($page >= $returnData["pages"] ) ? $returnData["next"] = 1 : false;
		
        return $returnData;
	}

	/**
  	* Gets the total rows in one table
  	*
  	* @return int $limit_paginatevoid
  	*/
    public function getTotalPages($table_name, $limit_paginate = null)
	{
	    $total_pages = 1;
	    		
		if((int)$limit_paginate > 1 ) 
	    {
	    	self::setLimit($limit_paginate);
	    }
	    
	    if($total_rows = self::getTotalRows($table_name) )
	    {
	        $total_pages = round($total_rows / self::$limit_paginate);  
	    }
	    
	    return $total_pages;
	}
  	
  	/**
  	* Gets the next and previous page
  	* 
  	* @return void
  	*/
	public static function getNextPage($page = 1, $total_pages = 0, &$next, &$previous)
	{
		if( $total_pages > 1 && $page < $total_pages )
		{
			if($page == 1)
			{
				$next = $page + 1;
				$previous = $page;   
			} else if ( $page > 1 ) {
				$previous = $page - 1;
				$next = $page + 1;
			}
		} else {
			if( $page == 1 )
			{
				$next = 1;
				$previous = 1;
			} else {
				$next = $total_pages;
				$previous = $page - 1;
			}
		}
		return true;
	}

}