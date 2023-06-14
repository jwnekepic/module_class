<?php

	class fileField extends ep_field{
		
		protected $value = false;
 		protected $other = false; 
		protected $upload_path = false; 
		protected $position = 'left'; 
		
		function __construct( $name, $value = false, $upload_path = '', $position = 'left', $other = false ){
 			parent::__construct($name,$value);	
			 
 			$this->other = $other;
			$this->upload_path = $upload_path;
			$this->position = $position;

		}
		
		function display(){
		
			$output = '';
			
			$output .= '<input';
				$output .= ' type="hidden"';
				$output .= ' name="c_'.$this->name.'"';
				$output .= ' id="c_'.$this->field_id( $this->name ).'"';
				$output .= ' value="'.htmlentities( $this->value ).'"';
				$output .= ' data-initial="'.htmlentities( $this->value ).'"';
			$output .= '/>';
			
			$output .= '<div>';
				$output .= '<input';
					$output .= ' type="file"';
					$output .= ' name="'.$this->name.'"';
					$output .= ' id="'.$this->field_id( $this->name ).'"';
					$output .= ' value="'.htmlentities( $this->value ).'"';
					if( $this->other ){ $output .= ' '.$this->other; }
				$output .= '/>';
			$output .= '</div>';
			
			if( $this->value ){
				
				if( substr($this->upload_path, 0, 1) != '/' ){ $upload_path = '/'.$this->upload_path; }
				if( substr($this->upload_path, -1) != '/' ){ $upload_path = $this->upload_path.'/'; }
				$file = returnURL().'/uploads'.$upload_path.$this->value;
				
				$output .= '<div';
					$output .= ' class="file_message"';
					$output .= ' id="file_message_'.$this->field_id( $this->name ).'"';
				$output .= '>';
					
					$output .= '<div class="current">';
						
						$output .= '<span>';
							$output .= 'The current file uploaded is "';
								$output .= '<a';
									$output .= ' href="'.$file.'"';
									$output .= ' target="_blank"';
								$output .= '>';
									$output .= '<b>'.$this->value.'</b>';
								$output .= '</a>';
							$output .= '".';
						$output .= '</span>';
						
						$output .= 'Select a new file to overwrite the current file or ';
						$output .= '<a';
							$output .= ' href="#"';
							$output .= ' onclick="current_file_toggle( \''.$this->field_id( $this->name ).'\' ); return false;"';
						$output .= '>';
							$output .= 'remove the current file';
						$output .= '</a>.';
					
					$output .= '</div>';
				
				
					$output .= '<div class="removed" style="display: none;">';
					
						$output .= 'The current file will be removed once you click "Save" or you can ';
						$output .= '<a';
							$output .= ' href="#"';
							$output .= ' onclick="current_file_toggle( \''.$this->field_id( $this->name ).'\' ); return false;"';
						$output .= '>';
							$output .= 'undo removing the current file';
						$output .= '</a>.';
						
					$output .= '</div>';
				
				$output .= '</div>';
				
			}
			
			$output .= '<div class="c"></div>';
			
	 
			
			return $output;
		}
		
		function process($data){
			   

			if( $this->upload_path != '' ){
				if( substr($this->upload_path, 0, 1) == '/' ){ $path = substr( $this->upload_path, 1 ); }
				if( substr($this->upload_path, -1) != '/' ){ $path .= '/'; }
			}
			
			$path_parts = explode( '/', $path );
			$path 		= BASE_DIR.'/uploads/'.$path;
			$cur_file 	= $data['c_'.$name];
			$new_file 	= basename( $_FILES[$this->name]['name'] );
			$result		= $cur_file;

			if( $new_file ) {
				
				$file 	= clean_filename( substr(time(), -6).'_'.$new_file );
				
				if( !is_dir($path) ){
					$route = BASE_DIR.'/uploads/';
					foreach( $path_parts as $part ){
						$route .= $part.'/';
						if( !is_dir($route) ){
							mkdir( $route );
							chmod( $route, 0755 );
						}
					}
				}
				
				if( move_uploaded_file($_FILES[$this->name]['tmp_name'], $path.$file) ){
					$result = $file;
				}
				
			}
			
			return $result;

		
			
		}
		
	}