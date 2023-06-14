<?php

	class permalinkField extends textField{
		
		protected $value = false;
		protected $style = false;
		protected $other = false; 
		protected $linked_field = false; 
		
		function __construct( $name, $value = false, $linked_field = false , $style = false, $other = false  ){
			parent::__construct($name,$value);	
			 
			$this->style = $style;
			$this->other = $other;
			$this->linked_field = $linked_field;

		}
		
		function display(){
			$output = '';
			
			$output .= '<input';
				$output .= ' type="text"';
				$output .= ' style="font-family:monospace;"';
				$output .= ' name="'.$this->name.'"';
				$output .= ' id="'.$this->field_id( $this->name ).'"';
				if( $this->style ){ $output .= ' style="'.$this->style.'"'; }
				if( $this->other ){ $output .= ' '.$this->other; }
				$output .= ' value="'.htmlentities( $this->value ).'"';
			$output .= '/>';
			
			if( ($this->linked_field) && ($this->value == '') ){
				if (!is_array($this->linked_field))
					$linked_field = array($this->linked_field);

				$linked_field_selectors = implode(',', array_map(
					function($linked_field) {
						return ('#'.$linked_field);
					}, $linked_field
				));

				$output .= '<script type="text/javascript"> ';
					$output .= "$( document ).ready( function(){ ";
						$output .= "$( '$linked_field_selectors' ).keyup( function(){ ";

							$output .= 'var string = "";';

							foreach ($linked_field as $field) {
								$output .= "var perm_$field = $(\"#$field\").val().toLowerCase();" ;
								$output .= "perm_$field = perm_$field.replace(/[^a-zA-Z0-9 ]+/g, ''); ";
								$output .= "perm_$field = perm_$field.replace(/ /g, '-'); ";
							}

							$i = 0;

							foreach ($linked_field as $field) {
								$output .= "if (perm_$field)";
									$output .= "string = string + '".($i < 1 ? '' : '-')."' + perm_$field; ";

								$i++;
							}

							$output .= "$('#".$this->field_id( $this->name )."').val(string); ";
						$output .= "}); ";
					$output .= "}); ";
				$output .= "</script>";
			}
			
			
			return $output;
		}
		
		function process($data){
			return $data[$this->name];			
			
		}
		
	}