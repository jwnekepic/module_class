<?php


	class select2Field extends selectField{
		
		function __construct( $name, $options, $selected = false, $style = false, $other = false ){
			parent::__construct($name,$options);
			
 		

			
			if($selected){ $this->selected = $selected; }
			if($style){ $this->style = $style; }
			if($other){ $this->other = $other; }

			
		}

		function set($value){
			$this->selected = $value;
		}
		
		function display(){
			
			$output = parent::display();
			 
			
			$output .= '<script type="text/javascript">';
				$output .= "$( '#".$this->field_id( $this->name )."' ).select2();";
			$output .= '</script>';			
			
			return $output;
		}
		
		function process($data){
			   
			return $data[$this->name];			
			
		}
		
	}