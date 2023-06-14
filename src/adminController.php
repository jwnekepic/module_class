<?php

	class adminController{
		
		protected $allow_add 			= false;
		protected $allow_edit 		= false;
		protected $allow_delete 		= false;
		protected $allow_order 		= false;
		protected $allow_duplicate 	= false;
		protected $include_editor 	= false;
		protected $filters 			= Array( 'sb', 'd', 's', 'p', 'debug' );
		protected $page_limit 		= 10;
		protected $order 				= '`id` DESC';
		protected $columns 			= Array();
		protected $search 			= Array( 'id' );
		protected $item_name 			= "Default";
		protected $item_name_plural 	= "Defaults";
		protected $log_item 			= 'name';
		protected $database 			= Array();
		protected $fields 			= Array();
		protected $sections 			= Array();
		protected $base_url 			= '';
		protected $order_filter 		= '';
		protected $order_db 			= 0; 
		protected $show_pagination		= 1; 
		protected $sorted_desc 	= '<span class="fa fa-sort-alpha-desc" title="Sorted descendingly (Z to A)"></span>';
		protected $sorted_asc 	= '<span class="fa fa-sort-alpha-asc" title="Sorted ascendingly (A to Z)"></span>';
		
		
		
		function __construct($base_url){ 
		
			$this->base_url = $base_url;
			 

						
		}
		
		function getSearch(){

			$addl_query = '';
			
			if( $_GET['f_c'] ){
				if( $addl_query != '' ){ $addl_query .= " AND "; }
				$addl_query .= " `category` = '".$_GET['f_c']."' ";
			} 
		 
			$search_query = '';

			if( $_GET['s'] ){
				
				foreach( $this->search as $column ){
					if( $search_query != '' ){ $search_query .= ' OR '; }
					$search_query .= " `".$column."` LIKE '%".$_GET['s']."%' ";
				}
				
				$search_query = " ( ".$search_query." ) ";
				
			}
			
			
			if( $addl_query ){
				if( $search_query ){
					$search_query = 'WHERE '.$addl_query.' AND '.$search_query;
				} else {
					$search_query = 'WHERE '.$addl_query;
				}
			} else {
				if( $search_query ){
					$search_query = 'WHERE '.$search_query;
				}
			}
			
			return $search_query;
		}
		
		function getOrder(){
			
			if( $this->allow_order ){
				$this->order = '`order` ASC';
			}
			
			if( $_GET['sb'] ){
				if( $_GET['d'] ){
					$order = '`'.$_GET['sb'].'` '.strtoupper( $_GET['d'] );
				} else {
					$order = '`'.$_GET['sb'].'` ASC';
				}
			}else{
				$order = $this->order;
			}	
			
			return $order;
		}		
	
		function formatText($text){
			return $text;
		}
		
		function formatDate($text){
			return date('m/d/Y',$text);
		}		
		
		function formatStatus($status){
			
			if($status == 1){
				return '<span class="tc_green">Active</span>';
			}else if($status == 0){
				return '<span class="tc_red">Inactive</span>'; 
			}else{
				return "Other";
			}
			
		}
		
		function allowEditListing($info){
			$html = '';
			
			if($this->allow_edit == true){
				
				$html .= '<td align="center">';
					$html .= '<a href="'. $this->base_url.'&act=edit&i='.$info['id'].'">Edit</a>';
				$html .= '</td>';				
				
			}
			
			return $html;
			
		}
		
		function allowDuplicateListing($info){
			$html = '';
			
			if($this->allow_duplicate == true){
				
				$html .= '<td align="center">';
					$html .= '<a href="'. $this->base_url.'&act=duplicate&i='.$info['id'].'">Duplicate</a>';

 				$html .= '</td>';				
				
			}
			
			return $html;
			
		}
		
		function allowDeleteListing($info){
			$html = '';
			
			if($this->allow_delete == true){ 
				
				$html .= '<td align="center">';
					$html .= '<a onclick="return delete_item( '. $info['id'].' );" href="#">Delete</a>';
					
 				$html .= '</td>';				
				
			}
			
			return $html;
			
		}
		
		function allowOrderListing($info,$db){
			$html = '';
			if($this->allow_order){
				$html .= '<td align="center">';
					$html .= '<select name="order_'.$info['id'].'" onchange="order_item( \''.$info['id'].'\', this.options[this.selectedIndex].value );">';
					
						$count = reorder_count($this->database[$db],$this->order_filter);
						for( $i = 1; $i <= $count; $i++ ){
							$html .= '<option value="'.$i.'" ';
							if( $info['order'] == $i ){ $html .= 'selected'; }
							$html .= '>'.$i.'</option>';
						}
					
					$html .= '</select>';
				$html .= '</td>';	
			}
			
			return $html;
		}
				
		function buildTitles(){
			$html = '<tr  class="category">';
	
			
				foreach($this->columns AS $key=>$val){
					 
					$sort_img = '';
					 
					if( $val['name'] && $val['sort'] == true ){

						$url = $this->base_url;
						if( $this->filter_list ){
							$url .= filter_string( $this->filter_list, 'sb,d', false );
						}
						
						$sort_by = $val['name'];
						$sort_dir = 'asc';
						
						$url .= '&sb='.$sort_by;
						
						if( $_GET['sb'] == $sort_by ){
							if( $_GET['d'] == 'asc' ){
								$sort_img = $this->sorted_asc;
								$sort_dir = 'desc';
							} else {
								$sort_img = $this->sorted_desc;
								$sort_dir = 'asc';
							}
						}
						
						$url .= '&d='.$sort_dir;
						
						$title = '<a href="'.$url.'">'.$val['title'].'</a>';
						
					}else{
						$title = $val['title'];
					}						
					
					
					if($val['width'] != 0){
						$style = "width: " . $val['width'] . 'px';
					}
					
					$html .= '<td style="'.$style.'">';
						$html .= $title .' '.$sort_img;
					$html .= '</td>';
					
				}
				
				if($this->allow_order){
					$html .= '<td style="width:150px;" align="center">';
						$html .= "Order";
					$html .= '</td>';
				}
				if($this->allow_edit){
					$html .= '<td style="width:75px;" align="center">';
						$html .= "Edit";
					$html .= '</td>';
				}
				if($this->allow_duplicate){
					$html .= '<td style="width:75px;" align="center">';
						$html .= "Duplicate";
					$html .= '</td>';
				}
				
				
				if($this->allow_delete){
					$html .= '<td style="width:75px;" align="center">';
						$html .= "Delete";
					$html .= '</td>';
				}
				
			$html .= '</tr>';
			
			return $html;
		}
		
		function buildListTitleSection(){
			
			$html = '<div class="ca title_box">';

				$html .='<div class="l">';
					$html .='<h1>Manage '.$this->item_name_plural.'</h1>';
				$html .='</div>';
 				if($this->allow_add){
					$html .='<div class="l">';
						$html .='<? if( $allow_add ){ ?>';
							$html .='<input type="button" value="Add '.$this->item_name.'" onclick="window.location = \''. $this->base_url . '&act=add' .'\'; return false;">';
						$html .='<? } ?>';
					$html .='</div>';	
				}
 
				$html .='<div class="r">';
				$html .='</div>';

			$html .='</div>';

			return $html;
			
		}
		
		function buildKeepFilters(){
			$filters = '';
			
			foreach($this->filters AS $filter){
				if($filter != 'p'){
					if($_GET[$filter]){
						$filters .= '&' . $filter . '=' . $_GET[$filter] ;
					}
				}
			}
			
			return $filters;
			
		}
		
		function buildListPagination($location = "top"){
			
			$html = '';
			
			$html .= '<table class="page_nav">';
				$html .= '<tr>';
					$html .= '<td class="left">';         	
						
						if($location == "top"){
							$html .= '<form onsubmit="return false;">';
								
								$html .= '<input type="text" id="search_field" value="" placeholder="Search..." size="30">';
								
								$html .= '&nbsp;';
								
								$html .= '<input type="submit" value="Search" onclick="if( $(\'#search_field\').val() != \'\') { window.location=\'' .$this->base_url . '&amp;s=\' + $(\'#search_field\').val(); }">';
								
								if($_GET['s']){
									$html .= '&nbsp;&nbsp;<input type="button" value="Clear Search" onclick="window.location = \''.$this->base_url.'\';">';
								}
															
							$html .= '</form>';
						}
											
										
					$html .= '</td>';
					$html .= '<td class="right">';
						
						$html .= 'No records to display';
										
						$html .= '&nbsp;&nbsp;';
			
						$html .= '<input type="button" value="Refresh" title="Refresh" onclick="window.location=\''.$this->base_url . $this->buildKeepFilters().'\';">'; 
			
						$html .= '&nbsp;&nbsp;';
						
						if($this->getPage() == 1){
							$html .= '<input type="button" value="«" title="First Page" disabled="">';
						}else{
							$html .= '<input type="button" value="«" title="First Page" onclick="window.location = \''.$this->base_url . $this->buildKeepFilters() .'\';">';
						} 
			
						$html .= '&nbsp;&nbsp;';
						if($this->getPage() == 1){
							$html .= '<input type="button" value="‹" title="Previous Page" disabled="">';
						}else{
							$html .= '<input type="button" value="‹" title="Previous Page" onclick="window.location = \''.$this->base_url . $this->buildKeepFilters().'&p=' . ($this->getPage() - 1).'\';">';
						}
						
			
						$html .= '&nbsp;&nbsp;';
					
						$html .= 'Page ';
						$html .= '
							<select name="page" onchange="window.location = \''.$this->base_url.''. $this->buildKeepFilters() .'&p=\' + this.options[this.selectedIndex].value;">';
							for($i = 1;$i <= $this->getPageLimit(); $i++){
								if($i == $this->getPage()){
									$html .= '<option value="'.$i.'" selected="selected">'.$i.'</option>';
								}else{
									$html .= '<option value="'.$i.'">'.$i.'</option>';
								}
								
							}
							              
						$html .= '</select>'; 
						$html .= 'of ' . $this->getPageLimit();            
						$html .= '&nbsp;&nbsp;';
						
						if($this->getPage() == $this->getPageLimit()){
							$html .= '<input type="button" value="›" title="Next Page" disabled="">';
						}else{
 							$html .= '<input type="button" value="›" title="Next Page" onclick="window.location = \''.$this->base_url . $this->buildKeepFilters() .'&p='.($this->getPage() + 1) .'\';">';
						}
						
						
						$html .= '&nbsp;&nbsp;';
						
						if($this->getPageLimit() == $this->getPage()){
							$html .= '<input type="button" value="»" title="Last Page" disabled="">';
						}else{
							$html .= '<input type="button" value="»" title="Last Page" onclick="window.location = \''.$this->base_url . $this->buildKeepFilters() .'&p='.$this->getPageLimit() .'\';">';
						}
						
 			
					$html .= '</td>';
				$html .= '</tr>';
			$html .= '</table>';
			
			return $html;
		}
		
		function buildListHeader(){
				$html = '<thead>';
					$html .= '<tr>';
						$html .= '<td colspan="200">';
							$html .= 'Current '.$this->item_name_plural.'';
						$html .= '</td>';
					$html .= '</tr>';
				$html .= '</thead>';
				$html .= '<tbody>';
			return $html;
		}
		
		function buildListColumn($info,$val){
			

			$html .= '<td>';
			
				if($this->log_item == $val['name']){
					$html .= '<b>';
				}
					

				 
				if(is_callable($val['format'])){
					
					$html .= $val['format']($info[$val['name']]);
					
				}else if(method_exists($this, $val['format'])){
					$html .= $this->{$val['format']}($info[$val['name']]);
				}else{
					$html .= $val['name'];
				}
			 

			 
				
				
				if($this->log_item == $val['name']){
					$html .= '</b>';
				} 
			$html .= '</td>';	

			return $html;
		}
		
		function countRecords(){
			$stmt = "SELECT `id` FROM `".$this->database[0]."` ".$this->getSearch()." ORDER BY ".$this->getOrder()." ";
			
			$query = mysql_query($stmt);
			
			$count = mysql_num_rows($query);
			
			return $count;
			
		}
		
		function getPage(){
			$current = intval( $_GET['p'] );
			
			if($current < 1){
				$current = 1;
			} 
			
			return $current;
		}
		
		function getPageLimit(){
			
			$pages = ceil( $this->countRecords() / $this->page_limit );
			return $pages;
		}
		
		function getQueryLimit(){
			
			
			if($this->getPage() == 1){
				$limit = '0, ' . $this->page_limit;
			}else{
				$limit = ($this->page_limit * ($this->getPage() - 1)) .',' . $this->page_limit;
			}
			
			return $limit;
			
		}

		function listQuery(){  
	 
			return mysql_query("SELECT * FROM `".$this->database[0]."` ".$this->getSearch()." ORDER BY ".$this->getOrder()." LIMIT " . $this->getQueryLimit());
		}
		
		function buildList($db){
 			$html = '';
			
			
 			$html .= '
				<script type="text/javascript">
					var base_url = "'.$this->base_url.'"; 
				</script>
			';
			
			$html .= $this->buildListTitleSection();
	
			$qr = $this->listQuery();
			if($this->show_pagination){
				$html .= $this->buildListPagination();
			}
 
			$html .= '<table class="table">';

				$html .= $this->buildListHeader();
				$html .= $this->buildTitles();
				
				while($info = mysql_fetch_assoc($qr)){
					
					if($info['status'] == 0){
						$html .= '<tr class="inactive">';
					}else{
						$html .= '<tr class="active">';
					}
					
					foreach($this->columns AS $key=>$val){
						
						$html .= $this->buildListColumn($info,$val);

						
					}
					$html .= $this->allowOrderListing($info,$this->order_db);
					$html .= $this->allowEditListing($info);
					$html .= $this->allowDuplicateListing($info);
					$html .= $this->allowDeleteListing($info);
					 
					$html .= '</tr>';
					
				}
			
				$html .= '<tbody>';
			$html .= '<table>';
			if($this->show_pagination){
				$html .= $this->buildListPagination("bottom");
			}
			
			return $html;
			
		}
		
		function duplicateItem($act,$id){
		
			$record = get_item( $id, $this->database[0] );
			
			if( ($this->allow_duplicate) && ($record['id']) ){
				
				unset( $record['id'] );
				$base_name = $record[$this->log_item];
				$record[$this->log_item] .= ' (Duplicate)';
				
				if( $this->allow_order ){
					$record['order'] = 999999;
				}
				
				mysql_query( "INSERT INTO `".$this->database[0]."` ".query_build_set( $record ) );
				$new_record = mysql_insert_id();
				
				if( $this->allow_order ){
					reorder_all( $this->database[0], $this->order_filter );
				}
				
				log_action( 'Duplicated '.$item.' "'.$base_name.'"' );
				redirect( $this->base_url.'&act=edit&i='.$new_record.'&duplicated=1' );
				
				die();
				
			} else {
				log_message(
					'Could not locate '.$item.' you attempted to duplicate or your do not have permission to duplicate this '.$item.'.',
					'error',
					'Error'
				);
			}
			
		
		}
		
		function orderItem($act,$id,$order){

			$record = get_item( $id, $this->database[0] );
			 
			if( ($this->allow_order) && ($record['id']) ){
				reorder_one( $this->database[0], $record['id'], $order , $this->order_filter);
				log_action( 'Reordered '.$item.' "'.$record[$this->log_item].'"' );
				log_message(
					'The '.$item.' "'.$record[$this->log_item].'" has been reordered successfully.',
					'success',
					$item_capital.' Reordered'
				);
			} else {
				log_message(
					'Could not locate '.$item.' you attempted to reorder or your do not have permission to reorder this '.$item.'.',
					'error',
					'Error'
				);
			}
			
	
		}
		
		function deleteItem($act,$id){
			
		 
			$record = get_item( $id, $this->database[0] );

			if( ($record['id']) && ($this->allow_delete) ){
				
				$query = mysql_query( "DELETE FROM `".$this->database[0]."` WHERE `id` = '".$record['id']."' LIMIT 1" );
				
				log_action( 'Deleted '.$item.' "'.$record[$this->log_item].'"' );
				log_message(
					'The '.$item.' "'.$record[$this->log_item].'" has been deleted successfully.',
					'success',
					$item_capital.' Deleted'
				);
				
				if( $allow_order ){
					reorder_all( $this->database[0], $this->order_filter );
				}
				
			} else {
				log_message(
					'Could not locate '.$item.' you attempted to delete or your do not have permission to delete this '.$item.'.',
					'error',
					'Error'
				);
			}
			
			
		}
		
		function processPrepData($act,$data){
  			if( (isset($data['edit_sub'])) || (isset($data['add_sub'])) ){
			
				$id = $data['id'];
				
				$values = Array();
				// Set the table values for add / edit.
				foreach($this->sections AS $key=>$section){
					foreach($section['fields'] AS $key2=>$field){ 
					
						if($field['obj']){
							 
							$values[$field['name']] = $field['obj']->process($data);
						}else{
							$values[$field['name']] = $data[$field['name']];
						}
							 
					}
				}
		 

			}	
			
			return $values;
		}
		
		function process($act,$data){
			
			$values = $this->processPrepData($act,$data);
			
			// Processing for when "Save" has been clicked on Add page.
 			if( isset($data['add_sub']) ){
				
				if( $this->allow_add ){
				
					if( $this->allow_add ){
						$values['order'] = 999999;
					}

					mysql_query( "INSERT INTO `".$this->database[0]."` ".query_build_set( $values ) );
					$new_record = mysql_insert_id();
					
					create_revision( $new_record, $this->database[0] );
					log_action( 'Added '.$item.' "'.$values[$this->log_item].'"' );
					
					log_message(
						'The '.$item.' "'.$values[$this->log_item].'" has been added successfully. <a href="'. $this->base_url.'&act=edit&i='.$new_record.'">Click here to edit the new '.$item.'</a>',
						'success',
						$item_capital.' Added'
					); 
			
					if( $this->allow_add ){
						reorder_all( $this->database[0], $this->order_filter );
					}
				
				} else {
					log_message(
						'You do not have permission to add a '.$item.'.',
						'error',
						'Error'
					);
				}

			}
	
			// Processing for when "Save" has been clicked on Edit page.
			if( isset($data['edit_sub']) ){
				
				$record = get_item( $data['id'], $this->database[0] );
			 
 				
				if( ($record['id']) && ($this->allow_edit) ){
				
				 
			
					mysql_query( "UPDATE `".$this->database[0]."` ".query_build_set( $values )." WHERE `id` = '".$data['id']."' LIMIT 1" );
					
					create_revision( $data['id'], $this->database[0] );
					log_action( 'Edited '.$item.' "'.$values[$this->log_item].'"' );
					log_message(
						'The '.$item.' "'.$values[$this->log_item].'" has been edited successfully.',
						'success',
						$item_capital.' Edited'
					);
				
				} else {
					log_message(
						'Could not locate '.$item.' you attempted to edit or your do not have permission to edit this '.$item.'.',
						'error',
						'Error'
					);
				}
				
			}			
			
		}
		
		function hidden($field,$record){
			return '<input type="hidden" name="'.$field['name'].'" value="'.$record[$field['name']].'"/>';
		}
		
		function text($field,$record){
			return '<input type="text" name="'.$field['name'].'" value="'.$record[$field['name']].'"/>';
		}
		
		function textarea($field,$record){
			return '<textarea name="'.$field['name'].'">'.$record[$field['name']].'</textarea>';
		}
		
		function status($field,$record){
			$html = '';
			$html .= '<select name="'.$field['name'].'">';
				if($record[$field['name']] == 0){
					$html .= '<option selected value="0">Hidden/Draft</option>';
					$html .= '<option value="1">Published/Active</option>';					
				}else{
					$html .= '<option value="0">Hidden/Draft</option>';
					$html .= '<option selected value="1">Published/Active</option>';					
				}

			$html .= '</select>';
			
			return $html;
		}
		
		function simple_date($field,$record){
 			$html = '';
			$html .= '<input type="text" name="'.$field['name'].'" value="'.date("m/d/Y",$record[$field['name']]).'"/>';
			return $html;
		}
		
		function process_text($val){
			return $val;
		}
		function process_date($val){
 			return strtotime($val);
		}

		
		function buildManageTitle($act){

			$html ='<div class="ca title_box">';

				$html .='<div class="l">';
					if($act == "edit"){
						$html .='<h1>Add New '.$this->item_name.'</h1>';
					}else{
						$html .='<h1>Edit '.$this->item_name.'</h1>';
					}
					
				$html .='</div>';
				
				$html .='<div class="l">';
					 
				$html .='</div>';
				
				$html .='<div class="r">';
				$html .='</div>';
				$html .='<input type="button" value="Return to all News Entries" onclick="window.location = \''.$this->base_url.'\';">';
				
			$html .='</div>';	
			
			return $html;
			
		}
		
		function buildManage($id,$act){
		 
			if($act == "edit"){
				$record = get_item( $id, $this->database[0] );
			}
		
			$html = '';
			$html .= $this->buildManageTitle($act);
			
			
				
			if($_GET['act'] == 'edit'){
				$html .= '<form id="page_editor" method="post" enctype="multipart/form-data" action="'. $this->base_url . '&act='.$act.'&i='.$id . '" >';					
			}else{
				$html .= '<form id="page_editor" method="post" enctype="multipart/form-data" action="'. $this->base_url . '" >';							
			}

				$html .= $this->hidden( Array('name' => 'id'), $record );
			
				
				
				foreach($this->sections AS $key=>$val){
					
					$html .= '<table class="form" id="section_'.$this->section_id($val['class']).'">';
						$html .= '<thead>';
							$html .= '<tr>';
								$html .= '<td colspan="2">'.$val['title'].'</td>';
							$html .= '</tr>'; 
						$html .= '</thead>';
						$html .= '<tbody>	';
							foreach($val['fields'] AS $fk => $field){
								
							 
								$html .= '<tr id="section_'.$this->section_id($val['class']).'_'.$this->section_id($field['name']).'">';
								
									if($field['cols']){
										$html .= '<td class="left" colspan="1"><b>'.$field['name'].'</b></td>';
									}
									
									if($field['cols']){
										$html .= '<td class="right" colspan="1">';
									}else{
										$html .= '<td class="right" colspan="2">';
									}
									
										
										if($field['obj']){
									 
											$field['obj']->set($record[$field['name']]);
											
											$html .= $field['obj']->display(); //($field,$record); 
										} 
									
									$html .= '</td>';
								  
								$html .= '</tr>'; 									
							 
			
							}								
						
						$html .= '</tbody>	';
					$html .= '</table>	'; 
					$html .= '&nbsp;';
				}				
				
				
				$html .= '<div>';
				
					if($act == 'edit'){
						
						$html .= '<input type="submit" name="edit_sub" value="Save '.$this->item_name.'" >'; 				
					}else{
						
						$html .= '<input type="submit" name="add_sub" value="Save '.$this->item_name.'" >';	
						
					}

					
				$html .= '</div>';
						

			$html .='</form>';	
				

			return $html;			
			
		}
				
		function setColumn($title,$name,$sort = false,$width = 0,$format = "formatText"){
			
			$column = Array(
				'title' => $title,
				'name' => $name,
				'sort' => $sort,
				'width' => $width,
				'format' => $format
			);
			array_push($this->columns,$column);
			
		}
		
		function setFields($field_type,$field_name,$field_db,$field_custom_html = NULL, $field_custom_save = NULL){
			
			$field = Array($field_type,$field_name,$field_db,$field_custom_html,$field_custom_save);
			
		}
		
		function addSection($title,$class,$fn = NULL){
			
			$section = Array('title' => $title, 'class' => $class, 'fields' => Array());
			array_push($this->sections, $section);
			
		}
		
		function addField($section,$name,$obj,$order = 99999,$cols = true){
			 
			$field = Array('name' => $name, 'obj' => $obj, 'cols' => $cols);
			
			foreach($this->sections AS $key=>$val){
				
				if($this->sections[$key]['title'] == $section){
					
					$new = Array();
					$placed = false;
					
					for($i = 1; $i <= count( $this->sections[$key]['fields']); $i++){
						
						if($order == $i){
							array_push($new,$field);
							$placed = true;
						}
						if($this->sections[$key]['fields'][$i-1]){
							array_push($new,$this->sections[$key]['fields'][$i-1]);
						}
						
						
					}
					
					if($placed == false){
						array_push($new,$field);
					}
					
					$this->sections[$key]['fields'] = $new;
				}
			}
 			
		}
		
		function section_id($value){
			
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
		
				
		function setDB($db){
			
			array_push($this->database,$db);
		}
		
		function setFilter($filter){
 
			array_push($this->filters,$filter);
			
		}
		
		function setLogItem($name){
			
			$this->log_item = $name;
			
		}
		
		function setName($name){
			
			$this->item_name = $name;
			
		}
		
		function setNamePlural($name){
			
			$this->item_name_plural = $name;
			
		}
		function setShowPagination($pagination){
			
			$this->show_pagination = $pagination;
			
		}
		
		function setOrder($order = '`id` DESC'){
			
			$this->order = $order;
			
		}
		
		function setPageLimit($limit = 25){
			
			$this->page_limit = $limit;
			
		} 
		
		function setPermission($option, $val){
			
			$this->$option = $val;
			
		}
		
		function setSearch($column){
			
			array_push($this->search,$column);
			
		}		
			 
 		function setOrderDB($order_db){
			
			array_push($this->order_db,$order_db);
			
		}
		
		function setOrderFilter($filter){
			
			$this->order_filter = $filter;
			
		}
		
		function unsetSearch($column){
			
			foreach($this->search AS $key=>$val){
				
				if($val == $column){
					unset($this->search[$key]);
				}
				
			}
			
		}
		
		function unsetFilter($filter){
			
			foreach($this->filters AS $key=>$val){
				
				if($val == $filter){
					unset($this->filters[$key]);
				}
				
			}
			
		}
		
	}



