<?php

	class dateField extends ep_field{
		
		protected $value = false;
		protected $style = false;
		protected $other = false; 
		
		function __construct( $name, $value = false, $other = false ){
			parent::__construct($name,$value);	
			 
 			$this->other = $other;

		}
		
		function display(){
			
			if( $this->value ){
				$this->value = date( 'm/d/Y', $this->value );
			} else {
				$this->value = false;
			}
			
			$output = '';
			
			$output .= '<input';
				$output .= ' type="text"';
				$output .= ' name="'.$this->name.'"';
				$output .= ' id="'.$this->field_id( $this->name ).'"';
				$output .= ' style="width: 85px; text-align: center;"';
				$output .= ' autocomplete="off"';
				if( $this->other ){ $output .= ' '.$this->other; }
				$output .= ' value="'.htmlentities( $this->value ).'"';
			$output .= '/>';
			
			
			$output .=  '<script type="text/javascript">';
				$output .=  '$(function() {';
					$output .=  "$( '#".$this->field_id( $this->name )."' ).datepicker();";
				$output .=  '});';
			$output .=  '</script>';
			
			return $output;
		}
		
		function process($data){

			return strtotime( $data[$this->name]);
		
			
		}
		
	}