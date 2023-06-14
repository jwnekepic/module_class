<?php


	class select2_multipleField extends select_multipleField{
		
		function __construct(  $name, $options, $selected = false, $style = false, $other = false, $explode = ',', $params = array() ){
			parent::__construct($name,$options);
			
 		

			
			if($selected){ $this->selected = $selected; }
			if($style){ $this->style = $style; }
			if($other){ $this->other = $other; }
			if($explode){ $this->explode = $explode; }
			if($params){ $this->params = $params; }

			
		}

		function set($value){
			$this->selected = $value;
		}
		
		function display(){
			
 
			if ($this->params)
				$params = array_replace(array('width' => 330), $this->params);

			$output .= '<div>';
				$output .= parent::display();
			$output .= '</div>';

			$output .= '<script type="text/javascript">';
			$output .= '$( "#'.$this->field_id( $this->name ).'" ).select2('.json_encode($params).');';
			$output .= '</script>';		
			
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