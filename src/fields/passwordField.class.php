<?php


	class passwordField extends ep_field{
		
		function __construct($name,$value = false, $style = false, $other = false ){
			parent::__construct($name,$value);	
			
			if($style){  $this->style = $style; }
			if($other){  $this->other = $other; }
			
		}
		
		function display(){
		 
			$output = ''; 
			
			$output .= '<input';
				$output .= ' type="hidden"';
				$output .= ' name="c_'.$this->name.'"';
				$output .= ' id="c_'.$this->field_id( $this->name ).'"';
				$output .= ' value="'.$this->value.'"';
			$output .= '/>';
			
			$output .= '<input';
				$output .= ' type="password"';
				$output .= ' name="'.$this->name.'"';
				$output .= ' id="'.$this->field_id( $this->name ).'"';
				if( $style ){ $this->style .= ' style="'.$this->style.'"'; }
				if( $other ){ $this->other .= ' '.$this->other; }
			$output .= '/>';
	
			return $output;
		}
		
		function process($data){
					
			if($data[$this->name]){
				return $data[$this->name];		
			}else{
				return $data["c_".$this->name];		
			}

		 
			
			
		}
		
	}