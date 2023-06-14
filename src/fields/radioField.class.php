<?php

	class radioField extends ep_field{
		
		protected $value = false;
		protected $columns = false;
		protected $selected = false;
		protected $other = false; 
		
		function __construct( $name, $value, $selected = false, $columns = 1, $other = false ){
			parent::__construct($name,$value);	
			 
			$this->selected = $selected;
			$this->columns = $columns;
			$this->other = $other;

		}
		
		function display(){
	
			$output = '';
			
			if( $columns > 1 ){
				$limit = ceil( count($this->value) / $this->columns );
			} else {
				$limit = count( $this->value );
			}
			
			$total = 0;
			$count = 1;
					
			$output .= '<div class="l p_r" style="padding-top: 0;">';
				$output .= '<div class="p_r" style="padding-top: 0;">';
			
					if( is_array($this->value) ){
						foreach( $this->value as $key => $value ){
							
							$output .= '<div class="black">';
								$output .= '<label>';
									$output .= '<input';
										$output .= ' type="radio"';
										$output .= ' name="'.$this->name.'"';
										$output .= ' value="'.$key.'"';
										if( strval($key) == strval($this->selected) ){ $output .= ' checked="checked"'; }
										if( $this->other ){ $output .= ' '.$this->other; }
									$output .= '> ';
									$output .= $value;
								$output .= '</label>';
							$output .= '</div>';
							
							$total++;
							
							if( $count == $limit ){
									
									$output .= '</div>';
								$output .= '</div>';
								
								if( $total < count($this->value) ){
									$output .= '<div class="l p_r" style="padding-top: 0;">';
										$output .= '<div class="p_r" style="padding-top: 0;">';
								}
								
								$count = 1;
								
							} else {
								$count++;
							}
							
						}
					}
					
				$output .= '</div>';
			$output .= '</div>';
			
 		
			return $output;
		}
		
		function process($data){
			   
			return $data[$this->name];		
			
		}
		
	}