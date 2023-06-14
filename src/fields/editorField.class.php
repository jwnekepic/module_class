<?php

	class editorField extends textareaField{
		
		protected $value = false;
		protected $style = false;
		protected $other = false; 
		
		function __construct( $name, $value = false, $class = false, $style = false, $other = false ){
			parent::__construct($name,$value);	
 			$this->style = $style;
 			$this->class = $class;
			$this->other = $other;
			
		}
		
		function display(){
		 

			$output = '';
			
			$output .= '<textarea';
				$output .= ' name="'.$this->name.'"';
				$output .= ' id="'.$this->field_id( $this->name ).'"';
				if( $this->style ){ $output .= ' style="'.$this->style.'"'; }
				if( $this->class ){ $output .= ' class="'.$this->class.'"'; }else{ $output .= ' class="editor"'; }
				if( $this->other ){ $output .= ' '.$this->other; }
			$output .= '>';
				$output .= $this->value;
			$output .= '</textarea>';
			  
			return $output;
		}
		
		function process($data){
			   
			return $data[$this->name];			
			
		}
		
	}

 