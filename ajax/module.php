<?php

	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	define( 'IS_AJAX', true );
	$path = explode( '/admin', dirname(__FILE__) );
	define( 'BASE_DIR', $path[0] );

	require BASE_DIR.'/sources/init/connect.php';
	require BASE_DIR.'/sources/init/global.php';
	require BASE_DIR.'/admin/sources/php/functions.php';
  
  	require BASE_DIR."/admin/modules/new/fields.php";  
	require BASE_DIR."/admin/modules/new/src/adminController.php";
	 
	 if($_GET['act'] == "edit" || $_GET['act'] == "add"){
		 require_once BASE_DIR.'/admin/sources/includes/head.php';
	 }
	


	class newAjax extends adminController{
		function __construct($base_url){
			
			parent::__construct($base_url);		

			$this->ajax_url = explode( '/admin', dirname(__FILE__) );
			$this->ajax_url = returnURL().'/admin'.$this->ajax_url[1].'/module.php';

			$this->setPermission("allow_add", true);
			$this->setPermission("allow_edit", true);
			$this->setPermission("allow_delete", true);
			$this->setPermission("allow_order", true);
			$this->setPermission("allow_duplicate", false);
			$this->setPermission("include_editor", false); 
			
			
			$this->setOrderFilter(" `parent_id` = '".$_GET['i']."' ");
 			
			/* Database Settings */
			$this->setDB("m_new_form_test");
 			
			/* Listing Settings */
			$this->setColumn("Name","name",true,"0",function($text){
				return $text;
			});
			

			$this->setColumn("Date","date",false,"0", "formatDate");
			$this->setColumn("Status","status",false,"125","formatStatus");	
			
  

			$this->addSection("Manage","form");	 
			 
				$this->addField("Manage","name", new textField('name','')); 
				$this->addField("Manage","date",new datetimeField('date',''));
				$options = Array('0' => 'Hidden/Draft', '1' => 'Visible/Published' );
				$this->addField("Manage","status",new selectField('status',$options));	

			 


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
			
			$values['parent_id'] = $data['parent_id'];
			
			return $values;
		}
		 	
		
 		function buildListHeader(){
				$html = '<thead>';
					$html .= '<tr>';
						$html .= '<td colspan="200">';
							$html .= 'Current '.$this->item_name_plural.'';
							if($this->allow_add){
								$html .= '<div class="r"><input style="margin-top: 5px;" type="button" value="Add New Form Field" class="lightbox_iframe" data-size="600x500" href="'.$this->ajax_url.'?i='.$_GET['i'].'&act=add"></div>';
							}
						$html .= '</td>';
					$html .= '</tr>';
				$html .= '</thead>';
				$html .= '<tbody>';
			return $html;
		}

		function buildList($db){
 			$html = '';
			
			
 			$html .= '
				<script type="text/javascript">
					var base_url = "'.$this->base_url.'"; 
				</script>
			';
			
	
			
			$qr = $this->listQuery();
			
  
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
 			
			return $html;
			
		}	

		function allowEditListing($info){
			$html = '';
			
			if($this->allow_edit == true){
				

				
				$html .= '<td align="center">';
					$html .= '<a class="lightbox_iframe" data-size="600x500" href="'.$this->ajax_url.'?i='.$info['parent_id'].'&act=edit&id='.$info['id'].'">';
						$html .= "Edit";
					
                         
                    $html .='</a>';
 				$html .= '</td>';				
				
			}
			
			return $html;
			
		}
		
		function allowOrderListing($info,$db){
			$html = '';
			 
			if($this->allow_order){
				$html .= '<td align="center">';
					$html .= "<select name=\"order_".$info['id']."\" 
					
					onchange=\"ajax_process( '". $this->base_url."', '". $_GET['i']."', '&act=order&id=". $info['id']."&o=' + $(this).val() ); return false;\">";
								 					
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

		function allowDeleteListing($info){
			$html = '';
			
			if($this->allow_delete == true){ 
				
				$html .= '<td align="center">';
 					 
					$html .= "<a href=\"#\" onclick=\"ajax_process('". $this->base_url ."', '". $_GET['i'] ."', '&act=delete&id=".$info['id']."', 'Are you sure you want to delete this item?' ); return false;\"> Delete </a>";
					
 				$html .= '</td>';				
				
			}
			
			return $html;
			
		}		

		function buildManageTitle(){

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
	 
				
			$html .='</div>';	
			
			return $html;
			
		}	
		
		function getSearch(){

			$addl_query = '';
			
			if( $_GET['f_c'] ){
				if( $addl_query != '' ){ $addl_query .= " AND "; }
				$addl_query .= " `category` = '".$_GET['f_c']."' ";
			} 
			
			if( $_GET['i'] ){
				if( $addl_query != '' ){ $addl_query .= " AND "; }
				$addl_query .= " `parent_id` = '".$_GET['i']."' ";
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
		
		function buildManage($id,$act){

			if($act == "edit"){
				$record = get_item( $id, $this->database[0] );
			}
	
		
			$html = '';
				$html .= $this->buildManageTitle();
				
				 
				if($_GET['act'] == 'edit'){
					$html .= '<form id="page_editor" method="post" enctype="multipart/form-data" action="'. $this->ajax_url . '?i='.$record['parent_id'].'&act='.$act.'&id='.$record['id'] . '" >';					
				}else{
					$html .= '<form id="page_editor" method="post" enctype="multipart/form-data" action="'. $this->ajax_url . '?i='.$_GET['i'].'&act='.$act.'" >';							
				}

					$html .= $this->hidden( Array('name' => 'id'), $record );
					$html .= $this->hidden( Array('name' => 'parent_id'), Array('parent_id' => $_GET['i']) );

 
				
					
					
					foreach($this->sections AS $key=>$val){
						
						$html .= '<table class="form">';
							$html .= '<thead>';
								$html .= '<tr>';
									$html .= '<td colspan="2">'.$val['title'].'</td>';
								$html .= '</tr>'; 
							$html .= '</thead>';
							$html .= '<tbody>	';
								foreach($val['fields'] AS $fk => $field){
									
								 
										$html .= '<tr>';
											$html .= '<td class="left" colspan="1"><b>'.$field['name'].'</b></td>';
											 
												$html .= '<td class="right" colspan="1">';
												
													if($field['obj']){
												 
														$field['obj']->set($record[$field['name']]); 
														$html .= $field['obj']->display();  
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
						
						$html .= '&nbsp;&nbsp;<input  type="button"  name="cancel"  value="Cancel" onclick="window.parent.lb_close();" >';

						
					$html .= '</div>';
							

				$html .='</form>';	
			$html .='';	


				

			return $html;			
			
		} 
 
	}
	
	$newModule = new newAjax("ajax");
	
	echo  '<div class="contain">';
		
		if($_GET['act'] == "edit" || $_GET['act'] == "add"){
			
			if(isset($_POST['edit_sub'] ) || isset($_POST['add_sub'])){ 
				$newModule->process($_GET['act'],$_POST); 
				 show_messages();  
				echo '<script type="text/javascript">';
					echo "window.parent.ajax_listing( 'ajax', '".$_GET['i']."' );";
				echo '</script>';					 
				 
			}
 			
			echo $newModule->buildManage($_GET['id'],$_GET['act']);
			
		
			
		}else if($_GET['act'] == "order" && isset($_GET['i']) && isset($_GET['o'])){
			
			$newModule->orderItem($_GET['act'],$_GET['id'],$_GET['o']);
	 
		}else if($_GET['act'] == "delete"){
			
			$newModule->deleteItem($_GET['act'],$_GET['id']);
			
		}else{
			
			echo $newModule->buildList(0);
			
		}
	
	echo '</div>';
	
