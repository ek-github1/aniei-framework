<?php require SPIDERFRAME . "/vendors/fpdf/fpdf.php";

/**
 * Class UserAddress Extend of FPDF, simplyfing data access and modificatalogion
 * Holds the {@link UserAddress} model
 * @package spiderFrame
 * @author Ismael Cortés <may.estilosfrescos@gmail.com>
 * @copyright Copyright (c) 2010, Ismael Cortés <may.estilosfrescos@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package spiderFrame
 */
class Pdf extends FPDF {
    protected static $_instances = array();
    public $_array_var  = array();
    public $file_name   = "";
    public $actual_page = 0;
    public $date = "";
    public $title = "";
    public $page_indicator = "";

    public function __construct($file_name, $page_orientation = 'P'){
        parent::__construct($page_orientation);
        $this->file_name = $file_name;
        $this->AliasNbPages();
        //$this->actual_page = 1;
  	}

	public static function getInstance($file_name, $page_orientation = 'P'){
	 	if ( !isset(self::$_instances[$file_name]) || !(self::$_instances[$file_name] instanceof self)){
	      //$Pdf = new Pdf();
	      self::$_instances[$file_name] = new self($file_name, $page_orientation);
	    }
	    return self::$_instances[$file_name];
	}

	public static function getNewInstance($file_name){
       $Pdf = new Pdf();
       self::$_instances[$file_name] = $Pdf;

       return $Pdf;
    }

    public function setTitleHeader($title){
        $this->title = $title;
    }

    public function setDateHeader($date){
        $this->date = $date;
    }

    public function setPageFooter($page){
        $this->page_indicator = $page;
    }

    public function setFontFamily($family_font, $size_font = 0, $style_font = ''){
        $this->SetFont($family_font, $style_font, $size_font);
    }

    /*-------------------------------------*/
    /*--------- LAYOUT FUNCTIONS ---------*/
    /*-----------------------------------*/

    public function Header(){
        $this->SetY(15);
        $this->SetTextColor(0,0,0);
        $this->SetFont('Arial','',10);
        $this->Cell(0,10,$this->date,0,0,'L');
        $this->Ln();
        $this->SetFont('Arial','B',12);
        $this->Cell(0,10,$this->title,0,0,'C');
        $this->Ln();

        //$this->pages[$page] .= $data;
    }

    public function setContent($data, $color_header, $text_color = null, $padding = 4){
        //$this->pages[$page] .= $data;
        ($text_color == null)? $text_color = array("R" => 255, "G" => 255, "B" => 255) : false;
        foreach ($data AS $title => $information) {
            $this->setTitle($title, $color_header, $text_color, $padding);
            $this->setInformation($information);
        }
    }

    public function setTitle($title, $color = null, $text_color = null, $padding = -1 , $align = 'L'){
        ($color != null)? $this->SetFillColor($color["R"], $color["G"], $color["B"]) : $this->SetFillColor(255,255,255);
        ($text_color != null)? $this->SetTextColor($text_color["R"], $text_color["G"], $text_color["B"]) : $this->SetTextColor(0,0,0);
        $this->Ln($padding);
        $this->Cell(0, 10, $title, 0, 1, $align, true);
        // Line break
        $this->Ln($padding);
    }

    public function writeInformation($data, $with = 47, $border = 0, $text_color = null, $color = null){
        ($color != null)? $this->SetFillColor($color["R"], $color["G"], $color["B"]) : $this->SetFillColor(255,255,255);
        ($text_color != null)? $this->SetTextColor($text_color["R"], $text_color["G"], $text_color["B"]) : $this->SetTextColor(0,0,0);
        $this->Cell($with, 6, $data, $border, 0, 'C', true);
    }

    public function setInformation($information, $columns = 4, $width = 47, $color = null, $border = 0){
        $stack_data = array();
        $text_color = array("R" => 255, "G" => 255, "B" => 255);

        foreach ($information AS $key => $value) {
            ($color != null) ? $this->writeInformation($key, $width, $border, $text_color, $color) : $this->writeInformation($key, $width, $border);
            (!empty($value)) ? array_push($stack_data, $value) : array_push($stack_data, "---");
            if(sizeof($stack_data) == $columns){
                $this->Ln(6);
                while(sizeof($stack_data) > 0){
                    $temp_data = array_shift($stack_data);
                    $this->writeInformation($temp_data, $width, $border);
                }
                $this->Ln(12);
            }
        }
        if(sizeof($stack_data) != 0){
            $this->Ln(6);
            while(sizeof($stack_data) > 0){
                $temp_data = array_shift($stack_data);
                $this->writeInformation($temp_data, $width, $border);
            }
            $this->Ln(12);
        }
    }

    public function Footer(){
        //$this->pages[$page] .= $data;
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','',8);
        $this->SetTextColor(0,0,0);
        // Page number
        $this->Cell(0,10,$this->page_indicator . ' ' . $this->PageNo().'/{nb}',0,0,'C');
    }

    public function setParagraphSpace($number = null){
        ($number == null) ? $this->Ln() : $this->Ln($number);
    }

    public function setParagraph($text = "", $width = 0, $height = 5, $border = 0 , $align = 'J', $print_background = false){
        $this->MultiCell($width, $height, $text, $border, $align, $print_background);
    }

    public function setElement($element, $text = ""){
        $first = true;
        foreach ($element AS $key => $value) {
            if($first){
                $first = false;
                (!empty($value)) ? $text .= $key . ": " . $value : $first = true;
            } else {
                (!empty($value)) ? $text .= ", " . $key . ": " . $value : false;
            }
        }
        $text .= ".";
        $this->setParagraph($text);
    }

    public function assing($var_name, $value){
        $this->_array_var[$var_name] = $value;
    }

    public function get_var($var_name){
        return $this->_array_var[$var_name];
    }

    /*-------------------------------------*/
    /*---------- FILE FUNCTIONS ----------*/
    /*-----------------------------------*/

    public function setFileName($file_name = "file.pdf"){
        $this->file_name = $file_name . ".pdf";
    }

    public function downloadPdf(){
    	$this->Output('D', $this->file_name);
    }

    public function viewPdf(){
        $this->Output('I',$this->file_name);
    }

    public function getStringPdf(){
        $pdf = $this->Output('S',$this->file_name);
        return $pdf;
    }

    public function createPage(){
		$this->actual_page++;
		$this->AddPage();

		return $this->actual_page;
	}

    public function clearData($page = false){
        if(!$page){
            foreach ($this->pages AS $key => $page) {
                unset($this->pages[$key]);
            }
        } else {
            unset($this->pages[$page]);
        } 

        return true;
    }

    public function createTable($th, $td, $row_color = "", $column_color = "", $default_style = true){
        $th_width = $this->getMaxWidth($td);

        if($default_style){
            
            (!empty($row_color))? $this->SetFillColor($row_color["R"], $row_color["G"], $row_color["B"]) : $this->SetFillColor(255,15,15);
            $this->SetTextColor(255,255,255);
        }

        $this->setTh($th,$th_width);
        
        if($default_style)
            $this->SetTextColor(0,0,0);

        $this->setTd($td,$th_width,$column_color);
    }

    public function setTh($th,$th_width){
        # creating th heads of table
        $this->Ln();
        
        $i = 0;
        foreach ($th AS $key => $_th) {
            //$this->Cell($th_width[$key],10,$_th,0,0,'C',true);
            $this->Cell($th_width[$i],10,$_th,0,0,'C',true);
            $i++;
        }

    }
    
    public function setTd($td,$th_width, $column_color = ""){
        $_h = 1; // auxiliar
        # creating td data of table
        $this->Ln();
        foreach ($td AS $key => $_td) {
            $_i = 0; // auxiliar
            foreach ($_td AS $_key => $value) {   
                if(($_h % 2) == 0){
                    $this->SetFillColor(255,255,255);
                    $this->SetTextColor(169,169,169);
                } else {
                    $this->SetTextColor(169,169,169);
                    $this->SetFillColor(237,237,237);
                }

                if($_i == 0) {
                    
                    (!empty($column_color))? $this->SetFillColor($column_color["R"], $column_color["G"], $column_color["B"]) : $this->SetFillColor(251, 64, 75);

                    $this->SetTextColor(255,255,255);
                }

                $this->Cell($th_width[$_i],10,$value,0,0,'C',true);
                $_i++;
            }
            $this->Ln();
            $_h++;
        }
    }

    public function makeTable($data, $row_color = "", $column_color = ""){
        $th = array();
        foreach ($data AS $key => $value){
            foreach ($value AS $k => $v) {
                $th[$k] = $k; 
            }
        } 

        $this->createTable($th,$data,$row_color, $column_color);
    }

    public function getMaxWidth($td, $peserve_keys = true){
        $array = Array();
        foreach ($td AS $key => $value) {    
            //var_dump($key);
            //var_dump($value);
            foreach ($value AS $_key => $_td) {
                //var_dump($_key);
                //var_dump($_td);
                if($key == 0){
                    $array[0][$_key] = (strlen($_td) > strlen($_key)) ? strlen($_td) : strlen($_key);
                } else {
                    $size = strlen($_td);
                    ($size > $array[0][$_key])? $array[0][$_key] = $size : false;
                }
            }
        }

        foreach($array[0] AS $key => $value){
            ($value > 9)? ($value > 20)? $array[0][$key] += 30 : $array[0][$key] += 20: $array[0][$key] += 15;
        }
        
        
        if($peserve_keys){
            $array = $this->convertKeysToInt($array);
        }
        
        return $array;
    }

    public function convertKeysToInt($data){
        $converted_to_int_keys = array();

        foreach ($data[0] AS $key => $value) 
            $converted_to_int_keys[] = $value;

        unset($data);
        $data = $converted_to_int_keys;
        
        return $data;
    }

    public function setTableColors($th_style, $td_style){
        $this->table_colors["th_style"] = $th_style;
        $this->table_colors["td_style"] = $td_style;

        return $this->table_colors;
    }

    public function setGroupImages($images, $witdh = 60, $height = 50, $space = 4){
        $orientation_x = 10;
        $orientation_y = $this->GetY();
        if($orientation_y + $height > $this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AcceptPageBreak()){
			// Automatic page break
            $this->createPage();
            $orientation_y = $this->GetY();
		}

        $this->Ln(5);
        while(count($images) > 0){
            $this->putImage(array_shift($images),$orientation_x, $orientation_y, $witdh, $height);
            $orientation_x += ($witdh + $space);
        }
        $this->SetY($orientation_y + $height);
        $this->Ln(5);
	}

    public function putImage($file_path, $margin_x = 0, $margin_y = 0, $witdh = null, $height = null){
        ($margin_x == 0) ? $margin_x += $this->GetX() : false;
        ($margin_y == 0) ? $margin_y += $this->GetY() : false;

	    $this->Image($file_path , $margin_x, $margin_y, $witdh, $height);
	}

    public function setImage($file_path, $float = "left", $margin_x = 0, $margin_y = 0, $padding_y = 0){
        $float = strtolower($float);
    	$x = 5;
        $y = $this->GetY() + $padding_y;

    	switch ($float){
    		case 'center':
    			$x = 80;
    			break;
    		
    		case 'right':
    			$x = 160;
    			break;
    	}

        $x += $margin_x;
        $y += $margin_y;

	    $this->Image($file_path , $x, $y);
	}
}
