<?php

	class codeField extends textareaField{
		
		protected $value = false;
		protected $style = false;
		protected $other = false; 
		protected $mode = false; 
		
		function __construct( $name, $value = false, $style = false, $other = false, $mode = 'javascript' ){
			parent::__construct($name,$value);	
 			$this->style = $style;
			$this->other = $other;
			$this->mode = $mode;

		}
		
		function display(){
		 

			$output = '';
			
			$output .= '<textarea';
				$output .= ' name="'.$this->name.'"';
				$output .= ' id="'.$this->field_id( $this->name ).'"';
				$output .= ' class="code"';
				if( $this->style ){ $output .= ' style="'.$this->style.'"'; }
				if( $this->other ){ $output .= ' '.$this->other; }
			$output .= '>';
				$output .= $this->value;
			$output .= '</textarea>';
			
 
			$output .= '<script>';
				$output .= ' CodeMirror.fromTextArea(document.getElementById("'.$this->field_id( $this->name ).'"),{ ';
					$output .= ' lineNumbers: true, ';
					$output .= ' indentUnit: 4, ';
					$output .= ' mode: "'.$this->mode.'" ';
				$output .= ' }); ';
			$output .= '</script>';
			  
			return $output;
		}
		
		function process($data){
			   
			return $data[$this->name];			
			
		}
		
	}

 