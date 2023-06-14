<?php


	class selectField extends ep_field{
		
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
			$output = '';
		 
			$output .= '<select';
				$output .= ' name="'.$this->name.'"';
				$output .= ' id="'.$this->field_id( $this->name ).'"';
				if( $style ){ $output .= ' style="'.$this->style.'"'; }
				if( $other ){ $output .= ' '.$this->other; }
			$output .= '>';
			
			if( is_array($this->value) ){
				foreach( $this->value as $key => $value ){
					
					$output .= '<option';
						$output .= ' value="'.htmlentities( $key ).'"';
						if( strval($key) == strval($this->selected) ){ $output .= ' selected'; }
					$output .= '>';
						$output .= $value;
					$output .= '</option>';
					
				}
			}
			
			$output .= '</select>';
			
			return $output;
		}
		
		function process($data){
			   
			return $data[$this->name];			
			
		}
		
	}