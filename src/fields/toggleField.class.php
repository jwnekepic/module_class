<?php

	class toggleField extends ep_field{
		
		protected $value = 0; 
		protected $options = false;  
		
		function __construct( $name, $options, $value = '1', $onclick = false ){
			parent::__construct($name,$value);	
			  
 			$this->options = $options;

		}
		
		function display(){

			$output = '';
			 
			if( $this->value == '1' ){
				$class = 'toggle_on';
			} else {
				$class = 'toggle_off';
			}
			
			if( $this->onclick ){
				$onclick = " field_toggle( '".field_id( $name )."' ); ".$this->onclick;
			} else {
				$onclick = " field_toggle( '".field_id( $name )."' ); ";
			}
			
			$output .= '<div';
				$output .= ' class="toggle_field '.$class.'"';
				$output .= ' id="toggle_field_'.$this->field_id( $this->name ).'"';
				$output .= ' data-off-value="'.htmlentities( $this->options[0] ).'"';
				$output .= ' data-on-value="'.htmlentities( $this->options[1] ).'"';
			$output .= '>';
			
				$output .= '<input';
					$output .= ' type="hidden"';
					$output .= ' name="'.$this->name.'"';
					$output .= ' id="'.$this->field_id( $this->name ).'"';
					$output .= ' value="'.htmlentities( $this->value ).'"';
				$output .= '>';
				
				$output .= '<div';
					$output .= ' class="ilb rel toggle"';
					$output .= ' onclick="'.$this->onclick.'"';
 				$output .= '>';
					$output .= '<div class="abs inner"></div>';
				$output .= '</div>';
				
				//$output .= '<div class="ilb label">'.$this->options[$this->value].'</div>';
			
			$output .= '</div>';
			
			$output .= '<script>';
				$output .= '$("#toggle_field_'.$this->field_id( $this->name ).'").click(function(){';
					$output .= 'if($(this).hasClass("toggle_off")){';
						$output .= '$(this).removeClass("toggle_off");';
						$output .= '$(this).addClass("toggle_on");';
						$output .= '$("#'.$this->field_id( $this->name ).'").val(  $(this).data( "on-value" ));';
					$output .= '}else{';
						$output .= '$(this).removeClass("toggle_on");';
						$output .= '$(this).addClass("toggle_off");';	
						$output .= '$("#'.$this->field_id( $this->name ).'").val(  $(this).data( "off-value" ));';						
					$output .= '}';
				$output .= '});';
			$output .= '</script>';
			
 
			
			return $output;
		}
		
		function process($data){
			   
			return $data[$this->name];			
			
		}
		
	}