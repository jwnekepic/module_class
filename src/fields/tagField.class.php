<?php

	class tagField extends ep_field{
		
		protected $value = false; 
		protected $other = false; 
		
		function __construct( $name, $value = false, $other = false  ){
			parent::__construct($name,$value);	
			  
			$this->other = $other;

		}
		
		function display(){
		
			$output = '';
			
			if( $this->value ){
				$value = explode( '||', $this->value );
				unset( $value[0], $value[count($value)] );
				$value = implode( ',', $value );
			}
			
			$output .= '<input';
				$output .= ' type="text"';
				$output .= ' name="'.$this->name.'"';
				$output .= ' id="'.$this->field_id( $this->name ).'"';
				$output .= ' value="'.htmlentities( $value ).'"';
			$output .= '/>';
			
 		
			$output .= '<script type="text/javascript">';
				$output .= '$(function() {';
					$output .= "$( '#".$this->field_id( $this->name )."' ).tagsInput({ width: 'auto' });";
				$output .= '});';
			$output .= '</script>';
			
			return $output;
		}
		
		function process($data){

			$tags = $data[$this->name];
			$tags = explode( ',', $tags );
			$tags = implode( '||', $tags );
			
			if( $tags != '' ){
				$tags = '||'.$tags.'||';
			} else {
				$tags = '|'.$tags.'|';
			}
			
			return $tags; 		
			
		}
		
	}