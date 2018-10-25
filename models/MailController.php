<?php 

//	require_once SPIDERFRAME . "/vendors/phpmailer/PHPMailer.php";
	require 	 SPIDERFRAME . '/vendors/phpmailer/PHPMailerAutoload.php';

/**
 * Class MailController Extend of Row, simplyfing data access and modificatalogion
 * Holds the {@link UserAddress} model
 * @package spiderFrame
 * @author Ismael Cortés <may.estilosfrescos@gmail.com>
 * @copyright Copyright (c) 2010, Ismael Cortés <may.estilosfrescos@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package spiderFrame
 */
class MailController extends Row
{
	public $Mailer = null;
    private $template = null;
	private $start_mailer = false;

    public function __construct($mailer_id, $table_name = "app_data", DbConnection $DbConnection = null)
  	{
  	   parent::__construct( (int)$mailer_id, $table_name, $DbConnection);
  	}

    public static function getNewInstance($mailer_id, $table_name = "app_data", DbConnection $DbConnection = null)
    {
       $Row = new MailController($mailer_id, $table_name, $DbConnection);
       return $Row;
    }

  	public static function getInstance($mailer_id, $table_name = "app_data",  DbConnection $DbConnection = null)
  	{
    	if( !isset(parent::$_instances[$table_name][$mailer_id]) )
      	{
       	    $Row = new MailController((int)$mailer_id, $table_name, $DbConnection);
        	parent::$_instances[$table_name][$mailer_id] = $Row;
      	}

      	return parent::$_instances[$table_name][$mailer_id];
  	}

  	public static function getNextInstanceId()
  	{
  		//@todo Analizar cual es el siguiente mail con el que se puede enviar
  		return 1;
  	}

  	public function load()
  	{
  		parent::load(); 
  		$this->startMailer();
  	}

	public function startMailer()
	{
		$this->assertLoaded();
		// :: Create a new PHPMailer instance
		$this->Mailer = new PHPMailer;

		//:: Enable SMTP debugging
		//::  0 = off (for production use)
		//::  1 = client messages
		//::  2 = client and server messages
		// $this->Mailer->SMTPDebug = 2;
		// $Mailer->SMTPDebug = 1;
		
		//:: Set the encryption system to use - ssl (deprecated) or tls
		$this->Mailer->SMTPSecure = 'ssl';
		
		//:: Set the hostname of the mail server
		// $this->Mailer->Host = $this->data["mail_server"]; // mail.domain.com
		$this->Mailer->Host = "smtp.gmail.com"; // GMail
		//::  USE
		//::  if your network does not support SMTP over IPv6
		// $this->Mailer->Host = gethostbyname($this->data["mail_server"]); // mail.domain.com
		
		//:: Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
		// $this->Mailer->Port = $this->data["mail_port"]; // 465 Gmail
		$this->Mailer->Port = 465;
		
		//:: Tell PHPMailer to use SMTP
		$this->Mailer->IsSMTP(); // use SMTP
		
		//:: Whether to use SMTP authentication
		$this->Mailer->SMTPAuth = true;
		
		//:: Set charset encode
		$this->Mailer->CharSet = "utf-8";

		//:: Ask for HTML-friendly debug output
		// $this->Mailer->Debugoutput = 'html';

		//:: Username to use for SMTP authentication - use full email address for gmail
		$this->Mailer->Username = $this->data["mail"]; // "mail@domain.com";

		//:: Password to use for SMTP authentication
		$this->Mailer->Password = $this->data["password"];  //"your_password";

		//:: Set who the message is to be sent from
		$this->Mailer->setFrom($this->data["mail"], $this->data["app"]); // "your_mail@domain.com", "account name"

		$this->start_mailer = true;

		return true;
	}


	public function setMailFrom($mail)
	{
		$this->assertLoaded();

		if($mail)
		{
			$this->data["mail"] = $mail;
			return true;
		}

		return false;
	}

	public function setNameFrom($name)
	{
		$this->assertLoaded();

		if($name)
		{
			$this->data["app"] = $name;
			return true;
		}

		return false;
	}

	public function setFrom($mail, $name)
	{
		$return = false;
		$this->assertLoaded();

		if($mail)
		{
			$this->data["mail"] = $mail;
			$return = true;
		}

		if($name)
		{
			$this->data["app"] = $name;
			$return = true;
		}

		return $return;
	}

	public function setTo($mail, $name = false)
	{
		if($mail)
		{
			$name = (!$name)? $mail : $name;
			$this->Mailer->AddAddress($mail, $name);
			return true;
		}

		return false;
	}

	public function setMail($data)
	{
		$subject = (!empty($data["subject"])) ? $data["subject"] : "mailing";
		$mail_to = (!empty($data["mail_to"])) ? $data["mail_to"] : "";
		$name_to = (!empty($data["name_to"])) ? $data["name_to"] : "";
		$body = (!empty($data["body"])) ? $data["body"] : "";
		$html = (!empty($data["html"])) ? $data["html"] : true;
		
		$this->Mailer->AddAddress($mail_to, $name_to);
		$this->Mailer->Subject = $subject;
		$this->Mailer->Body = $body;
		$this->Mailer->IsHTML($html);
		
		return false;
	}

	public function setSubject($subject)
	{
		if($subject)
		{
			$this->Mailer->Subject = $subject;
			return true;
		}

		return false;
	}

	public function setBody($body, $html = true)
	{
		if($body)
		{
			$this->Mailer->Body = $body;
			$this->Mailer->IsHTML($html);

			//:: Read an HTML message body from an external file, convert referenced images to embedded,
			//:: convert HTML into a basic plain-text alternative body
			//$this->Mailer->msgHTML(file_get_contents('templates/test.tpl.php'), dirname(__FILE__));

			//:: Replace the plain text body with one created manually
			//$this->Mailer->AltBody = 'This is a plain-text message body';

			return true;
		}

		return false;
	}

	public function Send()
	{
		return $this->sendMail();
	}

	public function sendMail()
	{
		if(!$this->start_mailer)
		{
			$this->startMailer();
		}
		//Functions::__display($this->Mailer);
		return $this->Mailer->Send();
	}

	public function sendMailByTemplate($data)
	{
		ob_start();//:: Swift Mailer has the bad habit of sendig to much echoes
		$body = file_get_contents($this->template);
		$data["body"] = str_replace($data["search"], $data["replace"], $body);
		ob_end_clean();

		return $this->sendMail($data);
	}

	public function setTemplate($template)
	{
		
	}
}
