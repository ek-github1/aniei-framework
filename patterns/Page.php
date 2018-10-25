<?php

/**
 * Is a simple template display the page
 * Holds the {@link Template} pattern
 * @package spiderFrame
 * @author Ismael Cortés <may.estilosfrescos@gmail.com>
 * @copyright Copyright (c) 2010, Ismael Cortés <may.estilosfrescos@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */
class Page extends Template
{
	public function __construct($page_name = "", $template = "", $layout = "default", $full_path = false)
	{ 
		parent::__construct($page_name, $layout);
		$this->setTemplate($template, $full_path);
	}
}