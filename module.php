<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	$path = explode( '/admin', dirname(__FILE__) );
  
	global $messages;
	global $settings;
	
		  
	require "editor.php";
 	require "fields.php";
 

	require "src/adminController.php";  
	require "src/moduleController.php";  
 	
 
	class newModuleNew extends newModule{
		function __construct($base_url){
			
			parent::__construct($base_url);		

			
			$this->setPermission("allow_order", true); 
			$this->setSearch('name');

			$this->addField("Manage","toggle", new toggleField('toggle',Array(0,1), 1));
			
 			
			$this->addField("Manage","tag", new tagField('tag',''), 2);
			$this->addField("Manage","category", new categoryField('category','pages'), 2);


 		} 
    
	}
	
	$newModule = new newModuleNew("?a=" . $_GET['a']);


	if($_GET['act'] == "edit" || $_GET['act'] == "add"){
		
 		if(isset($_POST['edit_sub'] ) ){ 
			$newModule->process($_GET['act'],$_POST); 
			 show_messages(); 
			  
		}
		
		echo $newModule->buildManage($_GET['i'],$_GET['act']);
		
		echo '<script>var ajax_url = "'.returnURL(0).'/admin/modules/new";</script>';
		echo ajax_section( "ajax" );

	}else if($_GET['act'] == "delete" && isset($_GET['i'])){

		$newModule->deleteItem($_GET['act'],$_GET['i']);
		show_messages(); 
	 
		echo $newModule->buildList(0);	

	}else if($_GET['act'] == "duplicate" && isset($_GET['i'])){

		$newModule->duplicateItem($_GET['act'],$_GET['i']);
		show_messages(); 
	 
		echo $newModule->buildList(0);	

	}else if($_GET['act'] == "order" && isset($_GET['i']) && isset($_GET['o'])){
			
		$newModule->orderItem($_GET['act'],$_GET['i'],$_GET['o']);
		show_messages(); 
	 
		echo $newModule->buildList(0);		
		
	}else{
		
 		if(isset($_POST['add_sub'])){
		 
			$newModule->process($_GET['act'],$_POST);
			 show_messages(); 
		}
		
		echo $newModule->buildList(0);
	}

?>

 