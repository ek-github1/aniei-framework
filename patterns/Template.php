<?php
 
 /**
 * Holds {@link Template} class that shows a page by template
 * @author Ismael Cortés <may.estilosfrescos@gmail.com>
 * @copyright Copyright (c) 2010, Ismael Cortés <may.estilosfrescos@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package spiderFrame
 **/
class Template
{
    /**
     * Holds the relative path to the layout
     * @var string
     */
    protected $_layout = "";

    /**
     * Holds the relative path to the template
     * @var string
     */
    protected $_template = "";

    /**
     * Holds the relative path to the footer template
     * @var string
     */
    protected $_footer = "";
    protected $_aside = "";


    /**
     * Holds the main menu template
     * @var string
     */
    protected $_main_menu = "";
    protected $_secondary_menu = "";
    
    protected $page_name = "";
    

    /**
     * Holds the relative path to the layout
     * @var string
     */
    protected $_display_variable = false;
    
    /**
     * Holds the variables to be passed to the template as $Data object
     * @var array
     */
    protected $_variables = array();
    
    /**
     * Script to run on ready script page load
     * @var array
     */
    protected $_on_ready     = array();
    
    /**
     * CSS file that belongs to a controller
     * @var array
     */
    protected $_css_files = array();
    
    /**
     * CSS file that belongs to a controller
     * @var array
     */
    protected $_jquery_theme = "smoothness";
    
    /**
     * Js file that belongs to a controller
     * @var array
     */
    protected $_js_files = array();
    
    protected $_section = "";
    protected $_app     = "";
    protected $_token   = "";
    protected $_message = array();
    
    protected $_content     = "";
    protected $_actions     = array();
    protected $_html_contents   = array();
    protected $_general_actions = array();

    /**
     * Holds the rows's configuration structure 
     * Amount fields and rows
     *
     * _rows[i][j] {
     *   + field {}: specific field and properties
     *   + label: The label.
     *   + value: The default value.
     *   + help:  Little text to be show next to the field.
     *   + error_message: If set this message will be show in red below the field.
     *   + type: text, hidden, radio, select, textarea, date
     *   + parameters {}: type specific parameter.
     *   + input_parameters {}: Auto parsed input parameters.
     *   + validation: Function to be applied as validation, the function must get a
     *                 string and return true for success or false for invalid input.
     * }
     * @var array
     **/
    protected $_rows = array();
    
    public function __construct($page_name="", $layout="default")
    { 
        $this->setPageName($page_name);
        $this->setLayout($layout);
        // $this->setSection();
        // $this->setApp();
         
        // $this->setAside();
        // $this->setFooter();
        // $this->setMainMenu();
        // $this->setSecondaryMenu(); 
        // $this->setTemplate(SPIDERFRAME . "/patterns/templates/template.tpl.php", true); 
    }

    
    /**
     * Sets the layout file to be used.
     *
     * Take for granted that the file is under the relative path "templates/" and
     * has a "tpl.php" extension, unless you set $full_path to true
     * @param string    $layout The name of the layout to be used
     * @param bool        $full_path overrides the naming convention and allows you to set any file
     * @return void
     */
    public function setLayout($layout, $full_path = false)
    {    
        if ($full_path == true)
        {    
            if ( file_exists($layout) )
            {
                  $this->_layout = $layout;
            }
        } else {
            //@todo Fix to this dinamic path
            if(file_exists("subcore/layouts/" . $layout . "_layout.tpl.php"))
            { 
                //echo "<br>" . $layout;
                $this->_layout = "subcore/layouts/" . $layout . "_layout.tpl.php";
            } else if( file_exists(TO_ROOT . "/subcore/layouts/" . $layout . "_layout.tpl.php") ) {
                //echo "<br>" . $layout;
                $this->_layout = TO_ROOT . "/subcore/layouts/" . $layout . "_layout.tpl.php";
            } else if( file_exists(SPIDERFRAME . "/layouts/" . $layout . "_spider_layout.tpl.php") ) {
                //echo "<br>" . $layout;
                $this->_layout = SPIDERFRAME . "/layouts/" . $layout . "_spider_layout.tpl.php";
            } else {
                //echo "<br>" . $layout;
                $this->_layout = SPIDERFRAME . "/layouts/default_spider_layout.tpl.php";
            } 
        }
        //echo "<br>" . $this->_layout;
        return true;
    }


    /**
     * Sets the template file to be used.
     *
     * Take for granted that the file is under the relative path "templates/" and
     * has a "tpl.php" extension, unless you set $full_path to true
     * @param string $template The name of the template to be used
     * @param bool $full_path overrides the naming convention and allows you to set any file
     * @return void
     */
    public function setTemplate($template, $full_path = false)
    { 
        if($full_path === true)
        { 
            if (file_exists($template)) 
            { 
                  $this->_template = $template;
            } else { 
                $this->_template = SPIDERFRAME . "/patterns/templates/template.tpl.php";
                $this->setMessage("SORRY_ONE_OF_THE_TEMPLATES_YOU_ARE__LOOKING_FOR_WAS_NOT_FOUND", "failure");
            }
         } else { 
             if (file_exists($template))
             { 
                $this->_template = $template;
             } else if (file_exists(TO_ROOT . "/" . $template . ".tpl.php")){
                $this->_template = TO_ROOT . "/" . $template . ".tpl.php"; 
             } else if (file_exists($template . ".tpl.php")){
                $this->_template = $template . ".tpl.php";
             } else if (file_exists("templates/" . $template . ".tpl.php")){
                $this->_template = "templates/" . $template . ".tpl.php";
             } else if(file_exists(SPIDERFRAME . "/patterns/templates/" . $template . ".tpl.php")) { 
                $this->_template = SPIDERFRAME . "/patterns/templates/" . $template . ".tpl.php";
             } else {
                $this->assign("template_path", $template);
                $this->_template = SPIDERFRAME . "/patterns/templates/template.tpl.php";
                $this->setMessage("SORRY_ONE_OF_THE_TEMPLATES_YOU_ARE__LOOKING_FOR_WAS_NOT_FOUND", "failure");
             }
         }

         // echo "<br>" . $this->_template;
         return true;
    }
    
    

    /**
     * Sets the footer file to be used.
     *
     * Take for granted that the file is under the relative path "templates/" and
     * has a "tpl.php" extension, unless you set $full_path to true
     * @param string $footer The name of the layout to be used
     * @param bool $full_path overrides the naming convention and allows you to set any file
     * @return void
     */
    public function setFooter($footer = false, $full_path = false)
    {    
        if ($full_path === true)
        {    
            if ( file_exists($layout . "_footer.tpl.php") )
            {
                  $this->_footer = $footer . "_footer.tpl.php";
            }
        } else {
            //@todo Fix to this dinamic path
            if(file_exists("subcore/layouts/" . $footer . "_footer.tpl.php"))
            { 
                //echo "<br>" . $footer;
                $this->_footer = "subcore/layouts/" . $footer . "_footer.tpl.php";
            } else if( file_exists(TO_ROOT . "/subcore/layouts/" . $footer . "_footer.tpl.php") ) {
                //echo "<br>" . $footer;
                $this->_footer = TO_ROOT . "/subcore/layouts/" . $footer . "_footer.tpl.php";
            } else if( file_exists(TO_ROOT . "/subcore/layouts/default_footer.tpl.php") ) {
                //echo "<br>" . $footer;
                $this->_footer = TO_ROOT . "/subcore/layouts/default_footer.tpl.php";
            } else if( file_exists(SPIDERFRAME . "/layouts/" . $footer . "_spider_footer.tpl.php") ) {
                //echo "<br>" . $footer;
                $this->_footer = SPIDERFRAME . "/layouts/" . $footer . "_spider_footer.tpl.php";
            } else if( file_exists(SPIDERFRAME . "/layouts/default_spider_footer.tpl.php") ) {
                //echo "<br>" . $footer;
                $this->_footer = SPIDERFRAME . "/layouts/default_spider_footer.tpl.php";
            } 
        }
        //echo "<br>" . $this->_footer;
        return true;
    }


    public function setAside($aside = false, $full_path = false)
    {    
        if ($full_path === true)
        {    
            if ( file_exists($layout . "_aside.tpl.php") )
            {
                  $this->_aside = $aside . "_aside.tpl.php";
            }
        } else {
            //@todo Fix to this dinamic path
            if(file_exists("subcore/layouts/" . $aside . "_aside.tpl.php"))
            { 
                //echo "<br>" . $aside;
                $this->_aside = "subcore/layouts/" . $aside . "_aside.tpl.php";
            } else if( file_exists(TO_ROOT . "/subcore/layouts/" . $aside . "_aside.tpl.php") ) {
                //echo "<br>" . $aside;
                $this->_aside = TO_ROOT . "/subcore/layouts/" . $aside . "_aside.tpl.php";
            } else if( file_exists(SPIDERFRAME . "/layouts/" . $aside . "_spider_aside.tpl.php") ) {
                //echo "<br>" . $aside;
                $this->_aside = SPIDERFRAME . "/layouts/" . $aside . "_spider_aside.tpl.php";
            } else if( file_exists(SPIDERFRAME . "/layouts/default_spider_aside.tpl.php") ) {
                //echo "<br>" . $aside;
                $this->_aside = SPIDERFRAME . "/layouts/default_spider_aside.tpl.php";
            } 
        }
        //echo "<br>" . $this->_aside;
        return true;
    }



    
    public function setMainMenu($menu_name = false)
    { 
         $this->_main_menu = $this->__setMenu($menu_name, "main");
         //echo " " . $this->_main_menu;
         return true;
    }
    
    public function setSecondaryMenu($menu_name = false)
    { 
        $this->_secondary_menu = $this->__setMenu($menu_name, "secondary");
        return true;
    }
    
    private function __setMenu($menu_name, $level)
    { 
        $menu = "";
        $full_path_menu_name      = $menu_name . "_" . $level . "_menu.tpl.php";
        $application_menu_name    = TO_ROOT . "/apps/" . $this->_app . "/subcore/navs/" . $menu_name . "_" . $level . "_menu.tpl.php";
        $section_section_name     = TO_ROOT . "/apps/" . $this->_app . "/subcore/navs/" . $this->_section . "_" . $level . "_menu.tpl.php";
        $application_app_name     = TO_ROOT . "/apps/" . $this->_app . "/subcore/navs/" . $this->_app . "_" . $level . "_menu.tpl.php";
        $application_default_app  = TO_ROOT . "/apps/" . $this->_app . "/subcore/navs/default_" . $this->_app . "_" . $level . "_menu.tpl.php";
        $subcore_default_level    = TO_ROOT . "/apps/" . $this->_app . "/subcore/navs/default_" . $level . "_menu.tpl.php";
        $subcore_default          = TO_ROOT . "/subcore/navs/default_" . $level . "_menu.tpl.php";
        $subcore_app              = TO_ROOT . "/subcore/navs/" . $this->_app . "_" . $level . "_menu.tpl.php";
        $core_app_name            = SPIDERFRAME . "/navs/" . $this->_app . "_" . $level . "_menu.tpl.php";
        $default_nav              = SPIDERFRAME . "/navs/default_" . $level . "_menu.tpl.php";
        
        //echo "<br>" . $full_path_menu_name;

        if(file_exists($full_path_menu_name))
        {
            //echo "<br>" . $full_path_menu_name;
            $menu = $full_path_menu_name;
        } else if( file_exists($application_menu_name) ) { 
            //echo $application_menu_name;
            $menu = $application_menu_name;
        } else if( file_exists($section_section_name) ) { 
            //echo $section_section_name;
            $menu = $section_section_name;
        } else if( file_exists($application_app_name) ) { 
            //echo $application_app_name;
            $menu = $application_app_name;
        } else if( file_exists($application_default_app) ) { 
            //echo $application_default_app;
            $menu = $application_default_app;
        } else if( file_exists($subcore_default_level) ) { 
            //echo $subcore_default_level;
            $menu = $subcore_default_level;
        } else if( file_exists($core_app_name) ) { 
            //echo $core_app_name;
            $menu = $core_app_name;
        } else if( file_exists($subcore_default) ) { 
            //echo $core_app_name;
            $menu = $subcore_default;
        } else if( file_exists($default_nav) ) { 
            //echo $default_nav;
            $menu = $default_nav;
        }
        
        return $menu;
    }
    
    public function assign($variable, $value)
    {
        $this->_variables[$variable] = $value;
    }

    /**
     * Sets the page name depending of the layout it might be translated
     * @param string $page_name
     * @return void
     */
    public function setPageName($page_name) 
    {
        $this->assign("__page_name", $page_name);
        $this->page_name = $page_name;
    }
    
    /**
     * Sets the page title depending of the layout it might be translated
     * @param string $page_title
     * @return void
     */
    public function setPageTitle($page_title = true) 
    {
        if($page_title == true)
        {
            $page_title = $this->page_name;
        }
      
        $this->assign("__page_title", $page_title);
    }

    
    
    /** 
     * Sets the javascript code to be run when the pase document ready loading
     * 
     * @param string $function javascript
     * @param string $function parameters
     * @return void
     */    
    public function setOnReady()
    {
        $__args = func_get_args();
        $__function = array_shift($__args);
     
        $this->_on_ready[][$__function] = ($__args) ? $__args=str_replace("}'", "}",(str_replace("'{", "{", "'" . implode("','",$__args) . "'"))) : "";
    }
    
    /** 
     * Sets message for page
     * 
     * @param string $message
     * @param string $level 'info', 'success', 'failure', 'atention'
     * @return void
     */    
    public function setMessage($message, $level = "info")
    {
        $this->_message["level"] = $level;
        $this->_message["message"] = $message;
    }
    
    /**
     * Sets the jQuery theme UI
     * @param string $jquery_theme
     * @return void
     */
    public function setJQueryTheme($jquery_theme) 
    {
        $this->_jquery_theme = $jquery_theme;
    }
    
    /** 
     * Sets file link css
     * 
     * @param string $full_path
     * @return void
     */    
    public function addCssLink($full_path, $media="screen")
    {
        $__array = explode(".", $full_path); 
        $__extension = strtolower(end($__array));
        
        if($__extension == "css")
        {
            $this->_css_files[]= array("file" => $full_path, "media" => $media);
        }
    }
    
    /** 
     * Sets file link javascript
     * 
     * @param string $full_path
     * @return void
     */    
    public function addJsLink($full_path)
    { 
        $__array = explode(".", $full_path); 
        $__extension = strtolower(end($__array));
    
        if($__extension == "js")
        {
            $this->_js_files[] = $full_path;
        }
    }
    
    public function setTable(Table $Table) 
    {
        $this->_html_contents[]["Tables"][] = $Table;
    }
  
    public function setForm(Form $Form)
    {
        $this->_html_contents[]["Forms"][] = $Form;
    }
  
    public function setDetail(Detail $Detail)
    {
        $this->_html_contents[]["Details"][] = $Detail;
    }
    
    public function setTab(Tabs $Tabs)
    {
        $this->_html_contents[]["Tabs"][] = $Tabs;
    }
    
    public function setGalery(Galery $Galery)
    {
        $this->_html_contents[]["Galery"][] = $Galery;
    }
  
    /**
     * Jumps to the given url, sending a message.
     * @param string $url 
     * @param string $message
     * @param string $level might be: info, warning, error, success 
     * @return false
     */
    public function goToPage($url = "index.php", $message = "", $level = "info") 
    {
        if ( $message ) 
        {
            $this->setMessage($message, $level);
        }

        header("location: " . $url);
        die();
    }
    
    public function loadMainVariables()
    {  
        $this->assign("__rows", $this->_rows);
        $this->assign("__token", $this->_token);
        $this->assign("__message", $this->_message);
        $this->assign("__on_ready", $this->_on_ready);
        $this->assign("__js_files", $this->_js_files);
        $this->assign("__css_files", $this->_css_files);
        $this->assign("__jquery_theme", $this->_jquery_theme);
        $this->assign("__display_variable", $this->_display_variable);

      return true;
    }
    
    /**
     * Shows the given template
     *
     * Converts the $variables array into $Data object and sets any message that may
     * be in the $Data and finally calls the given template
     * @return void
     */
    public function display()
    {
        $this->loadMainVariables(); 
        $_secondary_menu = ($this->_secondary_menu) ? self::runTemplate($this->_secondary_menu, $this->_variables) : "";
        $_main_menu = ($this->_main_menu) ? self::runTemplate($this->_main_menu, $this->_variables) : "";
        $_aside = ($this->_aside) ? self::runTemplate($this->_aside, $this->_variables) : "";
        $_footer = ($this->_footer) ? self::runTemplate($this->_footer, $this->_variables) : "";
        $_content = self::runTemplate($this->_template, $this->_variables);
        
        $Data = (object)$this->_variables;
        extract($this->_variables);
        
        include $this->_layout;
    }
    
    /**
     * Shows the given template
     *
     * Converts the $variables array into $Data object and sets any message that may
     * be in the $_SESSION and finally calls the given template
     * @return void
     */
    public function getHTML($layout = false)
    {
        $this->loadMainVariables();
        $_secondary_menu = ($this->_secondary_menu) ? self::runTemplate($this->_secondary_menu, $this->_variables) : "";
        $_main_menu = ($this->_main_menu) ? self::runTemplate($this->_main_menu, $this->_variables) : "";
        $_footer = ($this->_footer) ? self::runTemplate($this->_footer, $this->_variables) : "";
        $_content = self::runTemplate($this->_template, $this->_variables);
        
          if($layout)
          {
                $Data = (object)$this->_variables;
                extract($this->_variables);

                ob_start();
                include $this->_layout;
                $_content = ob_get_clean();
                //ob_end_clean();
          } 

          return $_content;
    }

    /**
     * Run the Template in the cleanest enviroment posible
     * @param unknown_type $template
     * @param unknown_type $data
     * @return unknown_type
     */
    protected static function runTemplate($template, $data) 
    {
        $Data = (object)$data; /// @todo Backwards Compatibility Remove Before Release
        extract($data);
     
        ob_start();
        include $template;
        $template_content = ob_get_contents();
        ob_end_clean();
        
        return $template_content;
    } 



    public function setSection($section = false)
    {
        if(!$section)
        {
           $this->_section = $this->getSection();
        } else {
           $this->_section = $section;
        }
    }
    
    public function getSection()
    {
        $_sections = explode("/",$_SERVER["PHP_SELF"]);
        $_section = $_sections[count($_sections)-1];
        $_section = explode(".", $_section);
        $_section = $_section[0];
        return $_section;
    }
    
    public function setApp($app = false)
    {
      if(!$app)
      {
        $this->_app = $this->getApp();
      } else {
        $this->_app = $app;
      }
    }
    
    public function getApp()
    {
        $_app = false;
        $_app_path = explode("/",$_SERVER["PHP_SELF"]);
        
        for( $i=count($_app_path) -1; $i > 0; $i-- )
        {   
            if(!$_app)
            { 
                $_app = ( strpos($_app_path[$i], ".") )? $_app_path[$i-1] : false;
            }
        }

        return $_app;
    }
    
    public function getScriptName()
    {
        return basename($_SERVER["SCRIPT_FILENAME"], ".php");
    }
  
    public function displayVariable($variable)
    {
        $this->_display_variable = $variable;
    }
    



    /* ---------------- <<<< Form functions >>>> --------------------------------------------------------------------------------------------------------*/
    //
    //
    /* ---------------- <<<< Form functions >>>> --------------------------------------------------------------------------------------------------------*/
    /**
     * Set the Row Object to be edited
     *
     * @param  Row $Sturdyrow the Row to be edited
     * @return void
     **/
    public function setRow(Row $Row) 
    { 
        $i = count($this->_rows);
        if($this->_rows[$i] = $Row->getPackage())
        {
            return true;
        }
        
        return false;
    }

    // ------------------------------ //////////// --------------------------------- //
    // ------------------------ MOVE FIELDS FUNCTIONS ------------------------------ //
    // ------------------------------ //////////// --------------------------------- //
    
    /**
     * Moves the given field after another field
     * @param string $field The field to move
     * @param string $after_field The field after the $field will be located
     * @return bool true on success and false otherwise
     */
    public function moveField($field, $second_field = "", $position = "after")
    {
        return $this->__moveField($field, $second_field, $position);
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
        return $this->__moveField($field, "", "");
    }


    /**
     * Moves the given field after another field
     * @param string $field The field to move
     * @param string $after_field The field after the $field will be located
     * @return bool true on success and false otherwise
     */
    public function moveRow($field, $second_field = "", $position = "after")
    {
        return $this->__moveField($field, $second_field, $position);
    }

    /**
     * Moves the given field after another field
     * @param string $field The field to move
     * @param string $after_field The field after the $field will be located
     * @return bool true on success and false otherwise
     */
    public function moveRowAfter($field, $after_field)
    {
        return $this->__moveField($field, $after_field, "after");
    }

    /**
     * Moves the given field before another field
     * @param string $field The field to move
     * @param string $after_field The field after the $field will be located
     * @return bool true on success and false otherwise
     */
    public function moveRowBefore($field, $before_field)
    {
        return $this->__moveField($field, $before_field, "before");
    }

    /**
     * Moves the given field to the start of the form
     * @param string $field the field to be moved
     * @return bool true in success and false otherwise
     */
    public function moveRowToFirst($field)
    {
        return $this->__moveField($field, "", "first");
    }

    /**
     * Moves the given field to the start of the form
     * @param string $field the field to be moved
     * @return bool true in success and false otherwise
     */
    public function moveRowToStart($field)
    {
        return $this->__moveField($field, "", "start");
    }




    /**
     * Moves the given field before or after another field
     * @param string $field The field to move
     * @param string $after_field The field after the $field will be located
     * @param string $position after or before
     * @return bool true on success and false otherwise
     **/
    private function __moveField($field, $target = "", $position = "after")
    {
        if( $field_data = $this->__getFieldData($field) )
        {   
            //echo "<pre>"; print_r($field_data); echo "</pre>";
            unset( $this->_rows[$field_data["position"]][$field] );
            return $this->__insertField($field, $field_data, $position, $target );
        }
    }

    private function __getFieldData($field_name)
    {
        $field_data = array();

        if( $this->_rows )
        {
            foreach( $this->_rows AS $key => $field_values ) 
            {
                if($field_values[$field_name]["Field"]["field"] == $field_name)
                {
                    $field_data["position"] = $key;
                    $field_data["data"] = $field_values[$field_name];
                }
            }
        }        

        return $field_data;
    }


    // ------------------------------ //////////// --------------------------------- //
    // --------------------------- SET'S PROPERTIES -------------------------------- //
    // ------------------------------ //////////// --------------------------------- //
    
    /**
     * Sets the given field's level property 
     * @param string $field
     * @param string $level can be: "Field", "Label", "Row", etc
     * @param mixed $property
     * @param mixed $value
     * @return bool true on success false otherwise
     */
    public function setFieldProperty($field, $property, $value, $data_row = false)
    {   
        if( !empty($field) && !empty($property) )
        { 
            return $this->__setItemProperty($field, "Field", $property, $value, $data_row);
        }
    }

    /**
     * Sets the given field's level property 
     * @param string $row_field
     * @param string $level can be: "Field", "Label", "Row", etc
     * @param mixed $property
     * @param mixed $value
     * @return bool true on success false otherwise
     */
    public function setRowProperty($row_field, $property, $value, $data_row = false)
    {   
        if( !empty($row_field) && !empty($property) )
        {
            return $this->__setItemProperty($row_field, "Row", $property, $value, $data_row);
        }
    }

    /**
     * Sets the given field's level property 
     * @param string $field
     * @param string $level can be: "Field", "Label", "Row", etc
     * @param mixed $property
     * @param mixed $value
     * @return bool true on success false otherwise
     */
    public function setItemProperty($row_field, $level, $property, $value, $data_row = false)
    {   
        if( !empty($row_field) && !empty($property) )
        {
            return $this->__setItemProperty($row_field, $level, $property, $value, $data_row);
        }
    }

    public function setToken($value) 
    {
        $this->_token = $value;
        return true;
    }

    // --------------------- PRIVATE PACKAGE FUNCTIONS -------------------------- //
    /**
     * Sets the given row's property value in private performance
     * @param string $row_field row or field to be afected
     * @param string $key space to be afected
     * @param string $property set property
     * @param mixed $value value for property
     * @return void
     **/
    private function __setItemProperty($row_field, $level, $property, $value = "", $data_row = false) 
    {
        $succes = false;

        if( !empty($this->_rows) )
        {   
            foreach( $this->_rows AS $key => $field_values ) 
            {   
                foreach( $field_values AS $field => $values ) 
                {     
                    if( $field == $row_field )
                    {   
                        if( ($data_row != false) && ($data_row == $values["Field"]["data-row"]) )
                        {
                            if(is_array($property))
                            { 
                                $this->_rows[$key][$field][ucwords($level)] = $property;   
                            } else { 
                                $this->_rows[$key][$field][ucwords($level)][$property] = $value;
                            }
                        } else if( $data_row == false){    
                            if(is_array($property))
                            { 
                                $this->_rows[$key][$field][ucwords($level)] = $property;   
                            } else { 
                                $this->_rows[$key][$field][ucwords($level)][$property] = $value;
                            }
                        }

                        $succes = true;
                    }
                }
            }   
        }

        return $succes;
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
        $new_rows = array();

        if( $this->_rows )
        {   
            if( $target )
            {   
               foreach( $this->_rows AS $key => $field_values ) 
               {
                    foreach( $field_values AS $field => $values ) 
                    {   
                        $new_rows[$key][$field] = $values;
                        if($key == $target)
                        {
                            if( $position == "after" )
                            {
                                $new_rows[$key][$field_name] = $field_data["data"];
                            } else if ( $position == "before" ) {
                                unset($new_rows[$key][$field_name]);
                                $new_rows[$key][$field_name] = $field_data["data"];
                                $new_rows[$key][$field] = $values;
                            }
                        }
                    }
                }
            } else {
                if($position == "first" || $position == "start")
                {   
                    $new_rows[][$field_name] = $field_data["data"];
                    foreach( $this->_rows AS $key => $field_values ) 
                    {
                        foreach( $field_values AS $field => $values ) 
                        {  
                            $new_rows[$key][$field] = $values;
                        }
                    }
                } else {
                    $new_rows = $this->_rows;
                    $new_rows[][$field_name] = $field_data["data"];
                }
            }
        }
        
        $this->_rows = $new_rows;
        return true;
    }

}