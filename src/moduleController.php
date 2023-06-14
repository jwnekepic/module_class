<?php



class newModule extends adminController{
	
	function __construct($base_url){
		
		parent::__construct($base_url);		 
		
		/* Module Settings */ 
		$this->setPermission("allow_add", true);
		$this->setPermission("allow_edit", true);
		$this->setPermission("allow_delete", true);
		$this->setPermission("allow_order", true);
		$this->setPermission("allow_duplicate", true);
		$this->setPermission("include_editor", false); 
		
		/* Database Settings */
		$this->setDB("m_new_module");
		
		/* Listing Settings */
		$this->setColumn("Name","name",true,"0",function($text){
			return $text;
		});
		$this->setColumn("Date","date",false,"0", "formatDate");
		$this->setColumn("Status","status",false,"125","formatStatus");	
		
		$this->addSection("Manage","form");	
		 
			$this->addField("Manage","name", new textField('name',''));
			$this->addField("Manage","permalink", new permalinkField('permalink','','name'));
			$this->addField("Manage","color", new colorField('color',''));
 			$this->addField("Manage","date",new datetimeField('date',''));
			$this->addField("Manage","image",new imageField('image',''));
			$this->addField("Manage","description",new textareaField('description',''));
			$this->addField("Manage","code",new codeField('code',''));
			
			$options = Array('0' => 'Hidden/Draft', '1' => 'Visible/Published' );
			$this->addField("Manage","status",new selectField('status',$options));				
		$this->addSection("Content","form");	
			$this->addField("Content","content",new editorField('content',''),99999,false);

	}


	
}