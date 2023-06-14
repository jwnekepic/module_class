<?php


	class hiddenField extends ep_field{
		
		protected $value = false;
		
		function __construct($name, $value = false ){
			parent::__construct($name,$value);	
  
		}
		
		function display(){
			$output = '';
			
			$output .= '<input';
				$output .= ' type="hidden"';
				$output .= ' name="'.$this->name.'"'; 
				$output .= ' value="'.htmlentities( $this->value ).'"';
			$output .= '/>';
			
			return $output;
		}
		
		function process($data){
			   
			return $data[$this->name];		
			
		}
		
	}