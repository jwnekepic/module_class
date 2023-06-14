<?php


	class select_multipleField extends selectField{
		
		function __construct( $name, $options, $selected = array(), $style = false, $other = false, $explode = ',' ){
			 
			parent::__construct($name,$options);
			
 
			
			if($selected){ $this->selected = $selected; }
			if($style){ $this->style = $style; }
			if($other){ $this->other = $other; }
			if($explode){ $this->explode = $explode; }

			
		}

		function set($value){
			$this->selected = $value;
		}
		
		function display(){
			 
			if (!is_array($this->selected)) {

				if ($this->explode) {
					$selected = explode($this->explode, $this->selected);

					foreach ($selected as $key => $item) {
						unset($selected[$key]);
						$selected[$item] = $item;
					}
				} else
					$selected = array($this->selected => $this->selected); 
			}else{
				$selected = $this->selected; 
			}
  			 
			
			if ($this->value && !is_array($this->value) && $this->explode) {
				$options = explode($this->explode, $this->value);

				foreach ($options as $key => $item) {
					unset($options[$key]);
					$options[$item] = $item;
				}
			} elseif (!$options)
				$options = array();

			$output = '';

			$output .= '<select';
			$output .= ' name="'.$this->name.'[]"';
			$output .= ' id="'.$this->field_id( $this->name ).'"';
			if( $this->style ){ $output .= ' style="'.$this->style.'"'; }
			if( $this->other ){ $output .= ' '.$this->other; }
			$output .= ' multiple';
			$output .= '>';

			if( is_array($this->value) ){
				foreach( $this->value as $key => $value ){
					 
					$output .= '<option';
					$output .= ' value="'.htmlentities( $key ).'"';
					if( key_exists($key, $selected)){ $output .= ' selected'; }
					$output .= '>';
					$output .= $value;
					$output .= '</option>';

				}
			}

			$output .= '</select>';
 
			
			return $output;
		}
		
		function process($data){
				  
			$result = '';
 			if( is_array($data[$this->name]) ){
				foreach( $data[$this->name] as $value ){
					if( $result != '' ){ $result .= ','; }
					$result .= $value;
				}
			}
			
 			return $result; 
			
		}
		
	}