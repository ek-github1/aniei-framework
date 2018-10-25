<?php

/**
 * Provides a database abstraction of a Row, simplyfing data access and modification
 * Holds the {@link Row} model
 * @package spiderFrame
 * @author Levhita <levhita@gmail.com>
 * @author Ismael Cortés <may.estilosfrescos@gmail.com>
 * @copyright Copyright (c) 2010, Ismael Cortés <may.estilosfrescos@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */
class Row {

    protected $id           = 0;
    public    $error        = "";
    public    $data         = array();
    public    $package      = array();
    public    $arguments    = "";
    public    $order_arguments = "";
    protected $table_name   = "";
    protected $field_id     = "";
    protected $loaded       = false;
    protected $loadedProfile= false;
    protected $loadedPackage= false;
    
    public static $sql = "";
    public static $_arguments = "";
    public static $_order_arguments = "";
    
    public static $limit_paginate = 20;
    
    protected static $_instances = array();
    
    /**
    * This icon class needs the font awsome library
    */
    protected $common_icon = array(
                                    "name"=>"fa fa-user",
                                    "names"=>"fa fa-user",
                                    "lastname"=>"fa fa-user",
                                    "mother_name"=>"fa fa-user",
                                    "mail"=>"fa fa-envelope-o fa-fw",
                                    "password"=>"fa fa-key fa-fw",
                                    "secret"=>"fa fa-key fa-fw",
                                    "date"=>"fa fa-calendar",
                                    "default"=>"fa fa-check-square-o"
                                  );

    /**
     * Holds the DbConnection
     *
     * @var DbConnection
     */
    public $DbConnection   = null;
    public $assert_message = "Class instance isn't loaded";

    public function __construct($id, $table_name = "", DbConnection $DbConnection = null)
    {
        if( !is_integer($id) ) 
        {
            throw new InvalidArgumentException("id isn't an integer");
        }

        if( !is_string($table_name) || $table_name == "") 
        {
            throw new InvalidArgumentException("table_name isn't an string " . $table_name);
        }

        if( !isset($DbConnection) ) 
        {
            $DbConnection = DbConnection::getInstance("_root");
        }

        if( get_class($DbConnection)!== 'DbConnection') 
        {
            throw new InvalidArgumentException("DbConnection isn't a DbConnection");
        }

        $this->id           = $id;
        $this->table_name   = $table_name;
        $this->field_id     = $table_name . "_id";
        $this->DbConnection = $DbConnection;
        
    }

    public static function getInstance($id, $table_name = "", DbConnection $DbConnection = null)
    {
       return self::__getInstance((int)$id, $table_name, $DbConnection);
    }

    public static function getNewInstance($id, $table_name = "", DbConnection $DbConnection = null)
    {
        $Row = new Row((int)$id, $table_name, $DbConnection);
    	  return $Row ;
    }

    private static function __getInstance($id, $table_name = "", DbConnection $DbConnection = null)
    {
        if( !isset(self::$_instances[$table_name][$id]) )
        {
            $Row = new Row((int)$id, $table_name, $DbConnection);
            self::$_instances[$table_name][$id] = $Row;
        }

        return self::$_instances[$table_name][$id];
    }

    public static function getIdByFieldValue($table, $field, $value, DbConnection $DbConnection = null)
    {
        $DbConnection = ($DbConnection == null) ? DbConnection::getInstance("_root") : $DbConnection ;
        $sql = "SELECT " . $table . "." . $table . "_id FROM " . $table . " WHERE " . $table . "." . $field . " = '" . $value . "'" ;
        
        return $DbConnection->getValue($sql);
    }

    public function setInstance(Row $Row)
    {
    	  self::$_instances[$table_name][$id] = $Row;
    }

    public function getDbInstanceName()
    {
     return $this->DbConnection->getInstanceName();
    }

    public function getTableName()
    {
        return $this->table_name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return true;
    }

    public function getIdField()
    {
        return $this->field_id;
    }

    public function setIdField($field)
    {
        $this->field_id = $field;
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

    /** 
    * Save the data previously prepare
    * @return bool
    */
    public function save()
    {
        $return = true;
        
        if( !$id = $this->DbConnection->save($this->table_name, $this->data, $this->id) ) 
        {
            $return = false;
            $this->error = $this->DbConnection->getError();
        } 

        if(!$this->loaded)
        {
            $this->loaded = true;
        }

        if( !$this->id ) 
        {
            $this->setId($id);
            $this->data[$this->field_id] = $id;
        }

        return $return;
    }

    



  // ------------------------------ //////////// --------------------------------- //
  // ------------------------ LOAD & DATA FUNCTIONS ------------------------------ //
  // ----------------------------- //////////// ---------------------------------- //
  public function load()
  {
    $strip_slashes = true;

    if ( $this->__loadData() )
    {
      foreach($this->data AS $field => $value)
      {
          if($strip_slashes)
          {
              //$value = stripslashes($value);
              $value = stripcslashes($value);
              //$value = str_replace('\"', '"', $value);
          }

          $this->data[$field] = $value;
      }

      
      $this->assert_message = "CLASS_INSTANCE_LOADED";
      return true;
    }
    return false;
  }

  private function __loadData()
  {
    if ( $this->id != 0 )
    {
      $sql = "SELECT * FROM `{$this->table_name}` WHERE `{$this->field_id}` = {$this->id}";

      $data = $this->DbConnection->getRow($sql);
      
      if($data)
      {
          $this->data = $data;
          $this->loaded = true;
          return true;
      }

      return false;

    } else {
        $this->data = array();
        $this->loaded = true;
        return true;
    }

    return false;
  }

    public function active()
    {
        $this->data["active"] = "1";
        return $this->save();
    }

    public function inactive()
    {
       $this->data["active"] = "0";
       return $this->save();
    }

    public function delete()
    {
        $this->data["active"] = "-1";
        return $this->save();
    }

    public function isActive()
    {
          $this->assertLoaded();
          return ( $this->data["active"] == "1" ) ? true : false;
    }

    public function getActiveStatus()
    {
        $this->assertLoaded();
        
        switch ($this->data["active"]) 
        {
            case "-1":
                $active_status = "Delete";
                break;

            case "0":
                $active_status = "Inactive";
                break;

            case "1":
                $active_status = "Active";
                break;
        }
        
        $status = ($this->data["status"]) ? $this->data["status"] : $active_status;

        return $status;
    }



  public function erease()
  {
    $sql = "DELETE FROM `$this->table_name` WHERE `$this->field_id`=$this->id LIMIT 1;";
    if( !$this->DbConnection->executeQuery($sql) ) 
    {
      return false;
    }

    return true;
  }

  

  

  



















  
  


  // ------------------------------ //////////// --------------------------------- //
  // ------------------------ CREATE PACKAGE FUNCTIONS --------------------------- //
  // ------------------------------ //////////// --------------------------------- //
  public function assertLoadedPackage()
  {
      if ( !$this->loadedPackage ) 
      {
          if( !$this->loadPackage() )
          {
              throw new RunTimeException("Can't load package");
              throw new InvalidArgumentException("Can't load package");
              return false;
          }
      }

      return true;
  }

  public function getPackage()
  {
      $package = false;

      if( $this->assertLoadedPackage() )
      {
          $package = $this->package;
      }

      return $package;
  }

  public function getFields()
  {
      $fields = array();
      $structure = $this->DbConnection->getTableStructure($this->table_name);

      if($structure)
      {
        $fields = array_keys($structure);
      }

      return $fields;
  }

  protected function loadPackage()
  {
      $this->assertLoaded();
  
      $structure = $this->DbConnection->getTableStructure($this->table_name);
      
      if($structure)
      {
          foreach($structure AS $field)
          {
              $field_name = $field["Field"];
              $structure[$field_name]["Field"] = array();

              preg_match("/^([a-z]*)(?:\((.*)\))?\s?(.*)$/", $field["Type"], $field_type);
                
              $id = $this->getId();
              $label = str_replace("_id", "", $field_name);
              $label = str_replace("_", " ", $label);
                
              $structure[$field_name]["Row"]["name"] = $field_name;
              $structure[$field_name]["Row"]["class"] = "input-group";
              $structure[$field_name]["Row"]["table"] = $this->table_name;
              
              $structure[$field_name]["Field"] = $this->__setFieldType($field_type);
              $structure[$field_name]["Field"]["field"] = $field_name;
              $structure[$field_name]["Field"]["field_id"] = $field_name . "_" . $id;
              $structure[$field_name]["Field"]["name"] = $field_name . "_" . $id ;
              $structure[$field_name]["Field"]["placeholder"] = $label;
              $structure[$field_name]["Field"]["class"] = "form-control";
              $field_type = $structure[$field_name]["Field"]["type"];
              
              $value = ( isset($this->data[$field_name]) ) ? htmlspecialchars($this->data[$field_name]): htmlspecialchars($structure[$field_name]["Default"]) ;
              
              $structure[$field_name]["Field"]["value"] = $value ;
              
              $structure[$field_name]["Label"]["value"] =  ucwords(str_replace("_", " ", $label));
              $structure[$field_name]["Label"]["for"] = $field_name . "_" . $id;

              $structure[$field_name]["SpanIcon"]["class"] = "input-group-addon";
              
              $structure[$field_name]["Icon"]["icon"] = true;
              $structure[$field_name]["Icon"]["class"] = ( isset($this->common_icon[$structure[$field_name]["Field"]["field"]]) ) ? $this->common_icon[$structure[$field_name]["Field"]["field"]] : $this->common_icon["default"] ;
              
              $structure[$field_name]["Tooltip"]["tooltip"] = false;
              $structure[$field_name]["Tooltip"]["message"] = $label;

              $structure[$field_name]["Title"]["title"] = false;
              $structure[$field_name]["HelpMessage"]["help-message"] = false;

              $structure[$field_name]["Required"]["required"] = false;
              $structure[$field_name]["Required"]["class"] = "required";
              $structure[$field_name]["Required"]["message"] = "*";

              if($field_name == "password")
              { 
                  $structure[$field_name]["Field"]["value"] = "";
                  $structure[$field_name]["Field"]["type"] = "password";
                  $structure[$field_name]["Field"]["data-method"] = "{'protect': {'method': 'spiderCryption'}}";
              }       
                
              if( strpos($field_name, "date") !== false  )  
              { 
                  $structure[$field_name]["Icon"]["class"] = "fa fa-calendar";
                  $structure[$field_name]["Field"]["class"] = "form-control date";
                  $structure[$field_name]["Field"]["readonly"] = "readonly";
                  $structure[$field_name]["Field"]["data-method"] = "transform_date";
                  $structure[$field_name]["Field"]["value"] = ($value) ? date("m/d/Y H:i:s", $value): date("m/d/Y H:i:s");
              }
                
              if( (strpos($field_name, "active") !== false) || (strpos($field_name, "secret") !== false) || (strpos($field_name, "_id") !== false)) 
              { 
                  $structure[$field_name]["Row"]["class"] = "hide";
                  $structure[$field_name]["Field"]["type"] = "hidden";
              }

              if( strpos($field_name, "mail") !== false ) 
              { 
                  $structure[$field_name]["Field"]["placeholder"] = "e-mail";
                  $structure[$field_name]["Field"]["validate"] = "mail";
              }

              if( ($field_type == "select") || ($field_type == "radio") || ($field_type == "checkbox") ) 
              { 
                  $structure[$field_name]["Icon"]["icon"] = false;
                  $structure[$field_name]["Row"]["class"] = "form-group";
              }

              $structure[$field_name]["Field"]["data-id"] = $id;
              $structure[$field_name]["Field"]["data-row"] = $this->table_name;
              $structure[$field_name]["Field"]["data-field"] = $field_name;
              $structure[$field_name]["Field"]["data-name"] = $field_name;
              
              $structure[$field_name]["Field"]["data-validate"] = "{'type': '" . $structure[$field_name]["Field"]["validate"] . "', 'message': 'The " . $label . " is incorrect. Please try again'}";
          }
      }  

      $this->loadedPackage = true;
      $this->package = $structure;

      return true;
  }

  /**
     * This private function extract type information from field.
     * @param $field_type array field properties
     * @return $field array set values
     * @property $field_type[0] The whole string. ie: int(11) unsigned
     * @property $field_type[1] The type ie: int
     * @property $field_type[2] The type parameters ie: 11
     * @property $field_type[3] Extra ie: unsigned
     **/
    protected function __setFieldType($field_type)
    { 
        $field = array();

        switch($field_type[1])
        {
            case "char":
            case "varchar":
                if( $field_type[2] <= 160 ) 
                {
                    $field["type"] = "text";
                    $field["maxlength"] = $field_type[2];
                    //$field["size"] = round( $field_type[2]*.35 );
                } else {
                    $field["cols"] = "60";
                    $field["rows"] = "6"; 
                    $field["type"] = "textarea";                
                }
                $field["validate"] = "text"; 
                break;
            
            case "int":
            case "double":
                $field["type"] = "text";
                $field["maxlength"] = $field_type[2];
                $field["validate"] = "integer"; 
                break;

            case "text":
                $field["cols"] = "60";
                $field["rows"] = "6";
                $field["type"] = "textarea";
                $field["validate"] = "text";
                break;

            case "date":
                $field["type"] = "date";
                $field["Default"] = date("m/d/Y");
                $field["class"] = "date";
                $field["maxlength"] = 10;
                $field["readonly"] = "readonly"; 
                $field["validate"] = "date";
                break;

            case "set":
            case "enum":  
                $field["type"] = "select";
                $field["validate"] = "select";

                if($field_type[2] == "'0','1'") 
                {
                    $field["validate"] = "integer";
                    $items = array("1"=> "Yes", "0"=> "No");
                } else if($field_type[2] == "'-1','0','1'") {
                    $field["validate"] = "integer";
                    $items = array("1"=> "Active", "0"=> "Inactive", "-1"=> "Delete");
                } else {
                    /** Retrive and parse Options **/
                    $items = array();
                    $params  = explode("','", $field_type[2]);
                    $params[0] = substr($params[0], 1); //remove the first quote
                    $params[count($params)-1] = substr($params[count($params)-1], 0, -1);//remove the second quote
                    $items = array_combine($params, $params);//creates a createCombox compatible array
                }

                if ( count($items) <= 4 ) 
                {
                    $field["type"] = "radio";
                    $field["validate"] = "text";
                }

                $field["Items"] = $items;
                break;    
        } 

        return $field;
    }



    // ------------------------------ //////////// --------------------------------- //
    // ------------------------ MOVE FIELDS FUNCTIONS ------------------------------ //
    // ------------------------------ //////////// --------------------------------- //

    /**
     * Moves the given field after another field
     * @param string $field The field to move
     * @param string $target The field second the $field will be located
     * @return bool true on success and false otherwise
     */
    public function moveField($field, $target, $position = "after")
    {
        return $this->__moveField($field, $target, $position);
    }

    /**
     * Moves the given field after another field
     * @param string $field The field to move
     * @param string $after_field The field after the $field will be located
     * @return bool true on success and false otherwise
     */
    public function moveFieldAfter($field, $after_field)
    {
        return $this->__moveField($field, $after_field, "after");
    }

    /**
     * Moves the given field before another field
     * @param string $field The field to move
     * @param string $after_field The field after the $field will be located
     * @return bool true on success and false otherwise
     */
    public function moveFieldBefore($field, $before_field)
    {
        return $this->__moveField($field, $before_field, "before");
    }

    /**
     * Moves the given field to the start of the form
     * @param string $field the field to be moved
     * @return bool true in success and false otherwise
     */
    public function moveFieldToFirst($field)
    {
        return $this->__moveField($field, "", "first");
    }

    /**
     * Moves the given field to the start of the form
     * @param string $field the field to be moved
     * @return bool true in success and false otherwise
     */
    public function moveFieldToStart($field)
    {
        return $this->__moveField($field, "", "start");
    }

    /**
     * Moves the given field to the end of the form
     * @param string $field the field to be moved
     * @return bool true in success and false otherwise
     */
    public function moveFieldToEnd($field)
    {
        return $this->__moveField($field, "", false);
    }

    /**
     * Moves the given field before or after another field
     * @param string $field The field to move
     * @param string $after_field The field after the $field will be located
     * @param string $position after or before
     * @return bool true on success and false otherwise
     **/
    private function __moveField($field, $target, $position = "after")
    {
        $field_data = $this->__getFieldData($field);
        unset($this->package[$field]);

        return $this->__insertField($field, $field_data, $position, $target ); //true;
    }

    
    /**
     * Insert a Field after or before the given target this function is private
     * @param string $field_name How will be named the field
     * @param array $field_data a complete field array
     * @param string $target The name of the field after or before we'll
     *                       insert the new field.
     * @param string $position 'after' or 'before', Default: 'after'
     * @return bool true on success false otherwise
     **/
    private function __insertField($field_name, $field_data, $position = "after", $target = false)
    {
        $new_fields = array();

        if( $this->assertLoadedPackage() )
        {
            if( $this->package )
            {   
                if( $target )
                {
                   foreach( $this->package AS $key => $values ) 
                   {
                        $new_fields[$key] = $values;
                        if($key == $target)
                        {
                            if( $position == "after" )
                            {
                                $new_fields[$field_name] = $field_data;
                            } else if ( $position == "before" ) {
                                unset($new_fields[$key]);
                                $new_fields[$field_name] = $field_data;
                                $new_fields[$key] = $values;
                            }
                        }   
                    }
                } else {
                    if($position == "first" || $position == "start")
                    {   
                        $new_fields[$field_name] = $field_data;
                        foreach( $this->package AS $key => $values ) 
                        {
                            $new_fields[$key] = $values;
                        }
                    } else {
                        $new_fields = $this->package;
                        $new_fields[$field_name] = $field_data;
                    }
                }
            }
        }

        $this->package = $new_fields;
        return true;
    }



    // ------------------------------ //////////// --------------------------------- //
    // ----------------------- SET PACKAGE'S PROPERTIES ---------------------------- //
    // ------------------------------ //////////// --------------------------------- //
  
    /**
     * Sets the given field's level property 
     * @param string $field
     * @param string $level can be: "Field", "Label", "Row", etc
     * @param mixed $property
     * @param mixed $value
     * @return bool true on success false otherwise
     */
    public function setPackageProperty($field, $level, $property, $value)
    {   
        if( !empty($field) && !empty($property) )
        {
            return $this->__setPackageProperty($field, $level, $property, $value);
        }
    }

    /**
     * Unset the package level 
     * @param string $field
     * @param string $level can be: "Field", "Label", "Row", etc
     * @param mixed $property
     * @param mixed $value
     * @return bool true on success false otherwise
     */
    public function unsetPackageLevel($field, $level)
    {   
        if( !empty($field) && !empty($level) )
        {
            if($this->assertLoadedPackage() )
            {
                unset($this->package[$field][ucfirst($level)]);
            }
        }

        return true;
    }



    // ---------------------------- FIELD --------------------------------- //
    /**
     * Sets the given field's property 
     * @param string $field
     * @param mixed $property
     * @param mixed $value
     * @return bool true on success false otherwise
     */
    public function setFieldProperty($field, $property, $value)
    {   
        if( !empty($field) && !empty($property) )
        {
            return $this->__setPackageProperty($field, "Field", $property, $value);
        }
    }

    /**
     * Unsets the field property 
     *
     * @param $field string the field name in row
     * @param $property string the property name to unset
     * @param $value string the property value optional
     * @return bool true on success false otherwise
     **/
    public function unsetFieldProperty($field, $property, $value = "")
    {
        return $this->__unsetPackageProperty($field, "Field", $property, $value);
    }

    /**
     * Sets the given rows's property 
     * @param string $field
     * @param mixed $class_name
     * @return bool true on success false otherwise
     */
    public function setFieldClass($field, $class_name)
    {
        $this->__setPackageProperty($field, "Field", "class", $class_name);
        return true;
    }
  
    /**
     * Add class for this row to be apply format
     * @param string $field the name of the field
     * @param string $class_name the name of the class
     * @return void
     **/
    public function addFieldClass($field, $class_name) 
    {
        $field_data = $this->__getFieldData($field);
        
        if($field_data)
        {
            $field_class = ($field_data["Field"]["class"]) ? $field_data["Field"]["class"] . " " : "";
            $new_class = $field_class . $class_name;
            return $this->__setPackageProperty($field, "Field", "class", $new_class);
        }

        return false;
    }

    /**
     * Disabled the field as hidden field function call
     *
     * Commonly used with the field id.
     * To really delete the field from the Form use {@link deleteField}.
     * @param string $field the name of the field to hide
     * @return bool true on success false otherwise
     */
    public function disableField($field)
    {
        return $this->hideField($field);
    }

    /**
     * Sets the field as hidden
     *
     * Commonly used with the field id.
     * To really delete the field from the Form use {@link deleteField}.
     * @param string $field the name of the field to hide
     * @return bool true on success false otherwise
     */
    public function hideField($field)
    {
        $this->__setPackageProperty($field, "Row", "class", "hide");
        $this->__setPackageProperty($field, "Field", "type", "hidden");
        $this->__setPackageProperty($field, "Field", "readonly", "readonly");
        //$this->__setFieldProperty($field, "Row", "style", "display:'none'");
      return true;
    }

    /**
     * Deletes a field from the package
     *
     * If you only wish to hide a field use {@link hideField}
     * @param string $field the name of the field to be deleted
     * @return void
     **/
    public function unsetField($field) 
    {
        if($this->assertLoadedPackage() )
        {
            unset($this->package[$field]);
        }
        
        return true; 
    }

    


    // ---------------------------- ROW --------------------------------- //
    /**
     * Sets the given field's property 
     * @param string $field
     * @param mixed $property
     * @param mixed $value
     * @return bool true on success false otherwise
     */
    public function setRowProperty($field, $property, $value)
    {   
        if( !empty($field) && !empty($property) )
        {
            return $this->__setPackageProperty($field, "Row", $property, $value);
        }
    }

    /**
     * Unsets the field property 
     *
     * @param $field string the field name in row
     * @param $property string the property name to unset
     * @param $value string the property value optional
     * @return bool true on success false otherwise
     **/
    public function unsetRowProperty($field, $property, $value = "")
    {
        return $this->__unsetPackageProperty($field, "Row", $property, $value);
    }

    /**
     * Sets the given rows's class property 
     * @param string $field
     * @param mixed $class_name
     * @return bool true on success false otherwise
     */
    public function setRowClass($field, $class_name)
    {
        $this->__setPackageProperty($field, "Row", "class", $class_name);
        return true;
    }
  
    /**
     * Add class for this row to be apply format
     * @param string $field the name of the field
     * @param string $class_name the name of the class
     * @return void
     **/
    public function addRowClass($field, $class_name) 
    {
        $field_data = $this->__getFieldData($field);
        
        if($field_data)
        {
            $field_class = ($field_data["Row"]["class"]) ? $field_data["Row"]["class"] . " " : "";
            $new_class = $field_class . $class_name;
            return $this->__setPackageProperty($field, "Row", "class", $new_class);
        }

        return false;
    }

    /**
     * Disabled the field as hidden field function call
     *
     * Commonly used with the field id.
     * To really delete the field from the Form use {@link deleteField}.
     * @param string $field the name of the field to hide
     * @return bool true on success false otherwise
     */
    public function disableRow($field)
    {
        return $this->hideField($field);
    }




    // ---------------------------- LABEL ------------------------------- //
    /**
     * Sets the given label's property 
     * @param string $field
     * @param mixed $property
     * @param mixed $value
     * @return bool true on success false otherwise
     */
    public function setLabelProperty($field, $property, $value)
    {   
        if( !empty($field) && !empty($property) )
        {
            return $this->__setPackageProperty($field, "Label", $property, $value);
        }
    }

    /**
     * Unsets the label property 
     *
     * @param $field string the field name in row
     * @param $property string the property name to unset
     * @param $value string the property value optional
     * @return bool true on success false otherwise
     **/
    public function unsetLabelProperty($field, $property, $value = "")
    {
        return $this->__unsetPackageProperty($field, "Label", $property, $value);
    }

    /**
     * Sets the given label's class property
     * @param string $field
     * @param mixed $class_name
     * @return bool true on success false otherwise
     */
    public function setLabelClass($field, $class_name)
    {
        $this->__setPackageProperty($field, "Label", "class", $class_name);
        return true;
    }
  
    /**
     * Add class for this label to be apply format
     * @param string $field the name of the field
     * @param string $class_name the name of the class
     * @return void
     **/
    public function addLabelClass($field, $class_name) 
    {
        $field_data = $this->__getFieldData($field);
        
        if($field_data)
        {
            $field_class = ($field_data["Label"]["class"]) ? $field_data["Label"]["class"] . " " : "";
            $new_class = $field_class . $class_name;
            return $this->__setPackageProperty($field, "Label", "class", $new_class);
        }

        return false;
    }

    /**
     * Disabled the label as hidden label function call
     *
     * Commonly used with the field id.
     * To really delete the field from the Form use {@link deleteField}.
     * @param string $field the name of the field to hide
     * @return bool true on success false otherwise
     */
    public function hideLabel($field)
    {
        return $this->setLabelClass("hide");
    }





    // --------------------- PRIVATE PACKAGE FUNCTIONS -------------------------- //
    /**
     * Sets the given row's property value in private performance
     * @param string $row row or field to be afected
     * @param string $key space to be afected
     * @param string $property set property
     * @param mixed $value value for property
     * @return void
     **/
    private function __setPackageProperty($field, $level, $property, $value = "") 
    {
        $succes = false;
        
        if($this->assertLoadedPackage() )
        {
            foreach($this->package AS $field_key => $field_values)
            {
                if( $field_key == $field )
                {
                    if(is_array($property))
                    { 
                        $this->package[$field][ucwords($level)] = $property;   
                    } else { 
                        $this->package[$field][ucwords($level)][$property] = $value;
                    }

                    $succes = true;
                }
            }   
        }

        return $succes;
    }

    /**
     * Unsets the given rows's properties
     * @param string $row row or field to be afected
     * @param string $key space to be afected
     * @param string $property set property
     * @param mixed $value value for property
     * @return void
     **/
    private function __unsetPackageProperty($field, $level, $property, $value = "") 
    {
        $succes = false;

        if($this->assertLoadedPackage() )
        {
            foreach($this->package AS $field_key => $field_values)
            {
                if( $field_key == $field )
                {
                    if($value)
                    {
                        $new_value = $this->package[$field][ucwords($level)][$property];
                        
                        if($value != "" && (strpos($new_value, $value) !== false ) )
                        {
                            $new_value = str_replace($value, " ", $new_value);
                        }
                        
                        $this->package[$field][ucwords($level)][$property] = $new_value;
                        
                    }
                    
                    if(!$value)
                    {
                        unset($this->package[$field][ucwords($level)][$property]); 
                    }

                    $succes = true;
                }
            }   
        }

        return $succes;
    }


    private function __getFieldData($field)
    {
        $field_data = false;

        if( $this->assertLoadedPackage() )
        {
            if( $this->package )
            {
                foreach( $this->package AS $key => $values ) 
                {
                    if($key == $field)
                    {
                        $field_data = $values;
                    }
                }
            }
        }

        return $field_data;
    }











    /**
    * Gets the total rows in one table
    *
    * @return int total rows
    */
    public function getTotalRows($table_name = false, $active = "")
    {
        (!$table_name)? $table_name = $this->table_name : false ;

        return $this->DbConnection->getTotalRows($table_name, $active);
    }

    /**
    * Gets the totals info
    *
    * @return array info
    */
    public function getPages($table_name = false, $page = 0)
    { 
        (!$table_name)? $table_name = $this->table_name : false ;

        return $this->DbConnection->getPages($table_name, $page);
    }

    /**
    * Gets the total rows in one table
    *
    * @return int $limit_paginatevoid
    */
    public function getTotalPages($table_name = false, $limit_paginate = null)
    {
        (!$table_name)? $table_name = $this->table_name : false ;
        
        return $this->DbConnection->getTotalPages($table_name, $limit_paginate);
    }










  

  public function isLoaded()
  {
    return $this->loaded;
  }

  public function assertLoaded()
  {
      if ( !$this->loaded ) 
      {
          if(!$this->load() )
          {
              throw new RunTimeException($this->assert_message);
              return false;
          }
      }

      return true;
  }

  public function getData()
  {
      $this->assertLoaded();
      return $this->data;
  }

  // Javier 
  public function clearData()
  {
    unset($this->data);
    $this->loaded = false;
    $this->loadedProfile = false;
    return true;
  }

  public function reloadData()
  {
    $this->clearData();
    $this->load();

    return true;
  }

  //Ricardo
  public function setForeignData($data)
  {
      if(!$this->loaded)
      {
          $this->__loadData();
      }

      foreach($data AS $key => $value )
      {
          $this->data[$key] = $value;
      }

      return true;
  }

}