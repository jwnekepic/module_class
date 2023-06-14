



<script src="https://james.ewsdev.site/admin/editor/tinymce.min.js"></script>
<script type="text/javascript">
	
	function file_manager( field_name, url, type, win ){
		
		var editor 	= tinymce.activeEditor;
		var w		= ( window.innerWidth * .75 );
		var h		= ( window.innerHeight * .75 );
		var browser	= '';
		browser		+= 'https://james.ewsdev.site/admin/files/index.php?';
		browser		+= '&callback=' + field_name;
		browser		+= '&type=' + type;
		//browser	+= '&directory=/uploads/forms';
		if( url ){
			browser	+= '&file=' + url;
		}
		
		tinymce.activeEditor.windowManager.open(
			{
				title: 'File Manager',
				file: browser,
				width: w,
				height: h,
				resizable: !0,
				maximizable: !0,
				inline: 1
			}
		);
		
	}
	
	tinymce.init({
		selector: 					'textarea.editor',
		theme: 						'modern',
		plugins: 					[
										'advlist autolink lists link image charmap hr anchor pagebreak',
										'searchreplace wordcount visualblocks visualchars code fullscreen',
										'media nonbreaking table contextmenu directionality',
										'emoticons paste textcolor colorpicker textpattern imagetools',
										'youtube vimeo shortcode grid soundcloud codemirror'
									],
		fontsize_formats:			'8px 10px 12px 14px 16px 18px 20px 24px 36px 48px 72px',
		toolbar1: 					'styleselect fontsizeselect forecolor backcolor | bold italic underline strikethrough superscript subscript | alignleft aligncenter alignright alignjustify',
		toolbar2: 					'undo redo | bullist numlist outdent indent | grid | link anchor image youtube vimeo soundcloud shortcode | code fullscreen',
		image_advtab: 				true,
		content_css: 				['//css.ewsapi.com/icons/icons.min.css?1685974188','//css.ewsapi.com/reset/reset.min.css?1685974188','//css.ewsapi.com/global/global.v4.css?1685974188','https://james.ewsdev.site/sources/css/stylesheet.css?1685974188','https://james.ewsdev.site/sources/css/editor.css?1685974188'],
		extended_valid_elements: 	'*[*]',
		valid_children : 			'+body[style]',
		verify_html: 				false,
        link_class_list: 			[
										{ title: 'Standard Link', value: '' },
										{ title: 'Button', value: 'btn' }
									],
		removed_menuitems: 			'newdocument',
		height: 					'500px',
        // forced_root_block: 		'',
		link_list: 					'https://james.ewsdev.site/admin/sources/php/link_list.php',
		imagetools_toolbar: 		'rotateleft rotateright | imageoptions',
        codemirror: 				{
										indentOnInit: true,
										fullscreen: false,
										config: { mode: 'application/x-httpd-php', lineNumbers: true },
										width: 800,
										height: 600,
										saveCursorPosition: true,
										cssFiles: [ 'theme/neat.css', 'theme/elegant.css' ],
										jsFiles: [ 'mode/clike/clike.js', 'mode/php/php.js', ]
									},
        file_browser_callback: 		function( field_name, url, type, win ){
										file_manager( field_name, win.document.getElementById( field_name ).value, type, win );
									}
	});
	
</script>