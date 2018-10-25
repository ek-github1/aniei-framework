<?php

if($Data->__form_properties)
{ 
	$form_properties = "";
	foreach($Data->__form_properties AS $property => $value)
	{
		$form_properties .= "{$property}=\"{$value}\" ";
	}
}

//Display structure --->>>>> 
if($Data->__display_variable)
{
	echo "<pre>";
	print_r($Data->__display_variable);
	echo "</pre>";
}

/** *
	echo "<pre>";
	print_r($Data->__rows);
	echo "</pre>";
/** */
?>

<form <?php echo (isset($form_properties)) ? $form_properties : "" ; ?> >
	<h1><?php echo (isset($Data->__form_title))? $Data->__form_title : "" ; ?></h1>
	<p><?php echo (isset($Data->__form_detail))? $Data->__form_detail : "" ; ?></p>
	<?php if($Data->__rows) 
		  { 
		  	  foreach ($Data->__rows AS $__rows)
		  	  { 
		  	  	  foreach ($__rows AS $__field)
				  { 
				  	if(!empty($__field))
				  	{
					  	$row_properties 	= "";
						$field_properties 	= "";
						$label_properties 	= "";
						$icon_properties 	= "";
						$span_icon_properties 	= "";
						
						$__type 		= $__field["Field"]["type"];
						$__name 		= $__field["Field"]["name"];
					  	$__value 		= $__field["Field"]["value"];
					  	$__title_size 	= $__field["Field"]["size"];
					  	$__placeholder 	= $__field["Field"]["placeholder"];
					  	$__field_items 	= $__field["Field"]["items"];
									
						if($__field["Row"])
						{ 
							foreach($__field["Row"] AS $property => $value)
							{
								$row_properties .= ($property != "Hide") ? $property . "=\"". $value . "\" " : "";
							}
						} 
						
				  		if( !strpos($row_properties, "id"))
				  		{
				  			//$form_id = ($Data->__form_properties["id"])? $Data->__form_properties["id"] . "_" ;
			  				//$row_properties .= "id=\"" . $form_id . $__field["Field"]["field"]."\" ";
			  			}
						
						if($__field["Field"])
						{ 
							foreach($__field["Field"] AS $property => $value)
							{
								
								if( ($__type == "textarea" && $property == "value") || ($__type == "select" && $property == "value") || ($__type == "radio" && $property == "value") || ($__type == "title" && $property == "size") )
								{
									
								} else {
									$field_properties .= ($property != "items" || $property != "Items") ? $property . "=\"". $value . "\" " : "";
								}
								
								if( $__type == "title" && $property == "size")
								{
									$__title_size = $value;	
								}
							} 
						}
					  
					  	if($__field["Label"])
						{ 
							foreach($__field["Label"] AS $property => $value)
							{
								$label_properties .= ($property != "value") ? $property . "=\"". $value . "\" " : "";
							} 
						}

						if($__field["SpanIcon"])
						{ 
							foreach($__field["SpanIcon"] AS $property => $value)
							{
								$span_icon_properties .= ($property != "value") ? $property . "=\"". $value . "\" " : "";
							}
						} 

						if($__field["SpanIcon"])
						{ 
							foreach($__field["SpanIcon"] AS $property => $value)
							{
								$span_icon_properties .= $property . "=\"". $value . "\" " ;
							}
						} 

						if($__field["Icon"])
						{ 
							foreach($__field["Icon"] AS $property => $value)
							{
								$icon_properties .= ($property != "icon") ? $property . "=\"". $value . "\" " : "";
							}
						} 

						/** *
						echo "<pre>";
						//print_r($row_properties); echo "<br><br>";
						//print_r($icon_properties); echo "<br><br>";
						//print_r($field_properties); echo "<br><br>";
						//print_r($title_properties); echo "<br><br>";
						//print_r($tooltip_properties); echo "<br><br>";
						//print_r($help_message_properties); echo "<br><br>";
						echo "</pre>";
						/** */		
			  			?>	
			  			
			  			<div <?php echo $row_properties;?> >
			  				<?php if( $__field["Icon"]["icon"] == true ){ ?>
			  					<span <?php echo $span_icon_properties;?> ><i <?php echo $icon_properties;?> ></i></span>
			  				<?php } ?>

			  				<?php switch ($__type){ 
			  					default:
			  					 	?>
			  						<input <?php echo $field_properties;?> />
			  						<?php break; ?>

			  					<?php case "textarea": ?>
			  						<textarea <?php echo $field_properties;?> ><?php echo $__value;?></textarea>
					          		<?php break; ?>
					          	
					            <?php case "select": 
							        $field_properties = str_replace("size", "title", $field_properties);
							        $field_properties = str_replace("readonly", "disabled", $field_properties);
		  							$field_properties = str_replace("type=\"select\"", "", $field_properties);
							        $field_properties = str_replace("value=\"{$__value}\"", "", $field_properties);
							        ?>
									<select <?php echo $field_properties;?> ><option value=""><?php echo Functions::__Translate("Select one") ?></option></select>
									<?php break; ?>

								<?php case "radio":
								    //echo Functions::__createRadioButton($__field["Field"]["Items"], $__value, $field_properties);
								    ?>
								    <?php break; ?>
			  				<?php } // End switch ?>
			  			</div>
			  			
				<?php } // If empty rows ?>
			<?php } // foreach $_rows ?>	  		
		<?php } // foreach $Data->_rows ?>	  	
	<?php } // If $Data->_rows ?>
	

	<!--  Actions  -->
	
	<?php /** **

	if(!empty($Data->__actions)) { ?>
			<div class="actions" >
				<?php foreach($Data->__actions AS $__action)
				  	  {
					      	$Action = (object)$__action; ?>
							<input type="button" id="<?php echo $Action->id; ?>" name="<?php echo $Action->name; ?>" name="eForm_button<?php echo $Action->class; ?>" value="<?php echo $Action->value) ?>"><br />
				<?php } //end foreach Actions ?>
			</div>
	<?php } // If actions /** **/ ?>
	<input type="button" id="save" value="Accept" />


</form>


