<?php

	class textField extends ep_field{
		
		protected $value = false;
		protected $style = false;
		protected $other = false; 
		
		function __construct( $name, $value = false, $style = false, $other = false ){
			parent::__construct($name,$value);	
			 
			$this->style = $style;
			$this->other = $other;

		}
		
		function display(){
			$output = '';
			
			$output .= '<input';
				$output .= ' type="text"';
				$output .= ' name="'.$this->name.'"';
				$output .= ' id="'.$this->field_id( $this->name ).'"';
				if( $this->style ){ $output .= ' style="'.$this->style.'"'; }
				if( $this->other ){ $output .= ' '.$this->other; }
				$output .= ' value="'.htmlentities( $this->value ).'"';
			$output .= '/>';
			
			return $output;
		}
		
		function process($data){
			   
			return $data[$this->name];			
			
		}
		
	}