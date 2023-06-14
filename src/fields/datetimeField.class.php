<?php

	class datetimeField extends ep_field{
		
		protected $value = false;
		protected $style = false;
		protected $other = false; 
		
		function __construct( $name, $value = false, $step = 15, $other = false){
			parent::__construct($name,$value);	
			 
 			$this->step = $step;
 			$this->other = $other;

		}
		
		function display(){
 			
			if( $this->value ){
				$value = date( 'm/d/Y', $this->value );
			} else {
				$value = false;
			}
			
			$output = '';
			
			$output .= '<input';
				$output .= ' type="text"';
				$output .= ' name="'.$this->name.'"';
				$output .= ' id="'.$this->field_id( $this->name ).'"';
				$output .= ' style="width: 85px; text-align: center;"';
				$output .= ' autocomplete="off"';
				if( $this->other ){ $output .= ' '.$this->other; }
				$output .= ' value="'.htmlentities( $value ).'"';
			$output .= '/>';
			
			
			$output .=  '<script type="text/javascript">';
				$output .=  '$(function() {';
					$output .=  "$( '#".$this->field_id( $this->name )."' ).datepicker();";
				$output .=  '});';
			$output .=  '</script>';
			
			if( $this->value ){
				$value = date( 'g:ia', $this->value );
			} else {
				$value = false;
			}
			
			$output .= ' at ';
			
			$output .= '<input';
				$output .= ' type="text"';
				$output .= ' name="'.$this->name.'_time"';
				$output .= ' id="'.$this->field_id( $this->name ).'_time"';
				$output .= ' style="width: 70px; text-align: center;"';
				$output .= ' autocomplete="off"';
				if( $this->other ){ $output .= ' '.$this->other; }
				$output .= ' value="'.htmlentities( $value ).'"';
			$output .= '/>';
			
		 
		
			$output .= '<script type="text/javascript">';
				$output .= '$(function() {';
					$output .= "$( '#".$this->field_id( $this->name )."_time' ).timepicker({ 'step': ".$this->step." });";
				$output .= '});';
			$output .= '</script>';			
			
			
			return $output;
		}
		
		function process($data){

  			return strtotime( $data[$this->name] .' ' . $data[$this->name . '_time']);
		
			
		}
		
	}