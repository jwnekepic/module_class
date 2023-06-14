<?php

	class colorField extends textField{
		
		protected $value = false;
 		protected $other = false; 
		
		function __construct( $name, $value = false, $other = false ){
			parent::__construct($name,$value);	
			 
 			$this->other = $other;

		}
		
		function display(){
			$output = '';
			
			$output .= '<input';
				$output .= ' type="text"';
				$output .= ' name="'.$this->name.'"';
				$output .= ' id="'.$this->field_id( $this->name ).'"';
				$output .= ' style="width: 95px;"';
				if( $this->other ){ $output .= ' '.$this->other; }
				$output .= ' autocomplete="off"';
				$output .= ' value="'.htmlentities( $this->value ).'"';
			$output .= '/>';
			
 		
			$output .= '<script type="text/javascript">';
				$output .= '$(function() {';
					$output .= "$.minicolors.defaults.letterCase = 'uppercase';";
					$output .= "$( '#".$this->name."' ).minicolors();";
				$output .= '});';
			$output .= '</script>';
			
			return $output;
		}
		
		function process($data){
			   
			return $data[$this->name];			
			
		}
		
	}