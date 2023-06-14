<?php

	class ep_field{
		protected $name = '';
		protected $value = ''; 
		
		function __construct($name,$value = ''){ 
			$this->name = $name; 	
			$this->value = $value; 	
		}
		
		function set($value){ 
	 
			$this->value = $value;
		}
		
		function get(){
			return $this->value;
		}
		
		function display(){
			
		}
		
		function process($data){
			
			return $data[$this->name];
			
		}
		
	
		protected function field_id( $value ){
			
			$value = str_replace( '[', '_', $value );
			$value = str_replace( ']', '_', $value );
			
			while( strpos($value, '__') !== false ){
				$value = str_replace( '__', '_', $value );
			}
			
			if( substr($value, 0, 1) == '_' ){
				$value = substr( $value, 1 );
			}
			
			if( substr($value, -1) == '_' ){
				$value = substr( $value, 0, (strlen($value)-1) );
			}
			
			return $value;
				
		}		
		
	}
	
 