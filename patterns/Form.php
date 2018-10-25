<?php
 
 /**
 * Holds {@link Page} class that shows a form to edit a {@link Row}
 * @author Arturo Osorio <arosbar@gmail.com>
 * @author Ismael Cortés <may.estilosfrescos@gmail.com>
 * @copyright Copyright (c) 2010, Ismael Cortés <may.estilosfrescos@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package spiderFrame
 **/

class Form extends Template
{ 									
  	/**
	 * The form properties
	 * @var array
	 */
	private $_form_properties = array();
	
	/**
   	 * Construct a {@link Edit} page
	 * @param string $page_name the page name to be shown
	 * @param string $template by default it uses Edit.tpl.php
	 * @return Edit
	 **/
 	public function __construct($page_name="", $layout="")
  	{
  		$form_id = strtolower(str_replace(" ", "_", $page_name));
		$this->setFormProperty("id", "spider-form-" . $form_id);
	  	$this->setFormProperty("name", "spider-form-" . $form_id);
	  	
	  	parent::__construct($page_name, $layout);
  		//parent::addJsLink(SPIDERFRAME . "/js/spider-tools/data_row.js");
  		parent::addCssLink(SPIDERFRAME . "/css/spider/default/spider-forms.css");
	  	parent::setTemplate(SPIDERFRAME . "/patterns/templates/form.tpl.php", true);

	  	if(isset($_SESSION["token"]))
	  	{
	  		$this->setFormProperty("data-token", $_SESSION["token"]);
	  	}
  	}
	
 	
	public function setFormTitle($title) 
	{
		$this->assign("__form_title", $title);
		return true;
	}

	public function setFormDetail($detail) 
	{
		$this->assign("__form_detail", $detail);
		return true;
	}

	public function setFormDataUrl($url) 
	{
		return $this->setFormProperty("data-url", $url);
	}

	public function setFormProperty($property, $value) 
	{
		$this->_form_properties[$property] = $value;
		return true;
	}
  	
	/**  ****************** Display Functions *******************  **/
	
	/** 
	 * Display the selected template with the given data and customization
   	 * @return void
     **/
  	
	public function display() 
	{
		$this->assign("__form_properties", $this->_form_properties);
	    parent::display(); 
	}
	
	public function getHTML()
  	{
	    parent::getHTML();
  	}
}