<?php
	$wceplugins = "";
	$wceexplugins = '';
	$wseprescript = '';
	$wcecodesnippetlang = '';
	$wcesmilelist = '';
	$wcesmilepath = '';
	$wcesmileycolumns = $result['wce_smileycolumns'];
	if ($wcesmileycolumns !== '') {
		$wcesmileycolumns = 'CKEDITOR.config.smiley_columns = ' .$wcesmileycolumns;
	}
	$kvobtn = 0;
	$bar1 = '';
	if ($result['btn_undo'] == 1) { $bar1 = $bar1 . '"Undo",'; $kvobtn++; }
	if ($result['btn_redo'] == 1) { $bar1 = $bar1 . '"Redo","-",'; $kvobtn++; }
	if ($result['btn_pastetext'] == 1) { $bar1 = $bar1 . '"PasteText",'; $kvobtn++; }
	if ($result['btn_pasteword'] == 1) { $bar1 = $bar1 . '"PasteFromWord",'; $kvobtn++; }
	if ($bar1!='') { $bar1 = '{name: "bar1", items: ['.$bar1 .  ' ] }, '; }
	$barfont = '';
	if ($result['box_fontsize'] == 1) { $barfont = $barfont . '"FontSize",'; $kvobtn++; }
	if ($result['box_font'] == 1) { $barfont = $barfont . '"Font","-",'; $kvobtn++; }
	if ($result['btn_justifyleft'] == 1) { $barfont = $barfont . '"JustifyLeft",'; $kvobtn++; }
	if ($result['btn_justifycenter'] == 1) { $barfont = $barfont . '"JustifyCenter",'; $kvobtn++; }
	if ($result['btn_justifyright'] == 1) { $barfont = $barfont . '"JustifyRight",'; $kvobtn++; }
	if ($result['btn_justifyblock'] == 1) { $barfont = $barfont . '"JustifyBlock"'; $kvobtn++; }
	if ($barfont!='') { $barfont = '{name: "barfont", items: [' . $barfont .  ' ] }, '; }
	$bar2 = '';
	if ($result['btn_bold'] == 1) { $bar2 = $bar2 . '"Bold",'; $kvobtn++; }
	if ($result['btn_italic'] == 1) { $bar2 = $bar2 . '"Italic",'; $kvobtn++; }
	if ($result['btn_underline'] == 1) { $bar2 = $bar2 . '"Underline",'; $kvobtn++; }
	if ($result['btn_strikethrough'] == 1) { $bar2 = $bar2 . '"Strike"'; $kvobtn++; }
	if ($bar2!='') { $bar2 = '{name: "bar2", items: ['.$bar2 .  ' ] } ,'; }
	$bar3 = '';
	if ($bar3!='') { $bar3 = '{name: "bar3", items: ['.$bar3 .  ' ] } ,'; }
	$bar4 = '';
	if ($result['btn_forecolor'] == 1) { $bar4 = $bar4 . '"TextColor",'; $kvobtn++; }
	if ($result['btn_backcolor'] == 1) { $bar4 = $bar4 . '"BGColor"'; $kvobtn++; }
	if ($bar4!='') { $bar4 = '{name: "bar4", items: ['.$bar4 .  ' ] } ,'; }
	$bar5 = '';
	if ($result['btn_link'] == 1) { $bar5 = $bar5 . '"Link","Unlink","Anchor","-",'; $kvobtn++; }
	if ($result['btn_image'] == 1) { $bar5 = $bar5 . '"Image",'; $kvobtn++; }
	if ($result['btn_emoticons'] == 1) { $bar5 = $bar5 . '"Smiley","-",'; $kvobtn++; 
		if ($result['btn_arkemoticons'] == 1) {
			$wcesmilepath = plugins_url( '/ark-wysiwyg-comment-editor/plugins/');
			$wcesmilelist = '"arkemoticons/img/01.gif","arkemoticons/img/02.gif","arkemoticons/img/03.gif","arkemoticons/img/04.gif","arkemoticons/img/05.gif",
			"arkemoticons/img/06.gif","arkemoticons/img/07.gif","arkemoticons/img/08.gif","arkemoticons/img/09.gif","arkemoticons/img/10.gif",
			"arkemoticons/img/11.gif","arkemoticons/img/12.gif","arkemoticons/img/13.gif","arkemoticons/img/14.gif","arkemoticons/img/15.gif",
			"arkemoticons/img/16.gif","arkemoticons/img/17.gif","arkemoticons/img/18.gif","arkemoticons/img/19.gif","arkemoticons/img/20.gif",
			"arkemoticons/img/21.gif","arkemoticons/img/22.gif","arkemoticons/img/23.gif","arkemoticons/img/24.gif","arkemoticons/img/25.gif",
			"arkemoticons/img/26.gif","arkemoticons/img/27.gif","arkemoticons/img/28.gif","arkemoticons/img/29.gif","arkemoticons/img/30.gif",
			"arkemoticons/img/31.gif","arkemoticons/img/32.gif","arkemoticons/img/33.gif","arkemoticons/img/34.gif","arkemoticons/img/35.gif",
			"arkemoticons/img/36.gif","arkemoticons/img/37.gif","arkemoticons/img/38.gif","arkemoticons/img/39.gif","arkemoticons/img/40.gif",
			"arkemoticons/img/41.gif","arkemoticons/img/42.gif","arkemoticons/img/43.gif","arkemoticons/img/44.gif","arkemoticons/img/45.gif",
			"arkemoticons/img/46.gif","arkemoticons/img/47.gif"';
		}
		if ($result['btn_arkemoticonssk'] == 1) {
			if ($wcesmilepath == '') {$wcesmilepath = plugins_url( '/ark-wysiwyg-comment-editor/plugins/');} 
			if ($wcesmilelist !== '') {$wcesmilelist = $wcesmilelist .',';}
			$wcesmilelist = $wcesmilelist .'"arkemoticonssk/img/01.gif","arkemoticonssk/img/02.gif","arkemoticonssk/img/03.gif","arkemoticonssk/img/04.gif",
			"arkemoticonssk/img/05.gif","arkemoticonssk/img/06.gif","arkemoticonssk/img/07.gif","arkemoticonssk/img/08.gif","arkemoticonssk/img/09.gif",
			"arkemoticonssk/img/10.gif","arkemoticonssk/img/11.gif","arkemoticonssk/img/12.gif","arkemoticonssk/img/13.gif","arkemoticonssk/img/14.gif",
			"arkemoticonssk/img/15.gif","arkemoticonssk/img/16.gif","arkemoticonssk/img/17.gif","arkemoticonssk/img/18.gif","arkemoticonssk/img/19.gif",
			"arkemoticonssk/img/20.gif","arkemoticonssk/img/21.gif","arkemoticonssk/img/22.gif"';
		}
		if ($result['btn_arkkbabe'] == 1) {
			if ($wcesmilepath == '') {$wcesmilepath = plugins_url( '/ark-wysiwyg-comment-editor/plugins/');} 
			if ($wcesmilelist !== '') {$wcesmilelist = $wcesmilelist .',';}
			$wcesmilelist = $wcesmilelist .'"arkkbabe/img/01.gif","arkkbabe/img/02.gif","arkkbabe/img/03.gif","arkkbabe/img/04.gif","arkkbabe/img/05.gif",
			"arkkbabe/img/06.gif","arkkbabe/img/07.gif","arkkbabe/img/08.gif","arkkbabe/img/09.gif","arkkbabe/img/10.gif","arkkbabe/img/11.gif",
			"arkkbabe/img/12.gif","arkkbabe/img/13.gif","arkkbabe/img/14.gif","arkkbabe/img/15.gif","arkkbabe/img/16.gif","arkkbabe/img/17.gif",
			"arkkbabe/img/18.gif","arkkbabe/img/19.gif","arkkbabe/img/20.gif","arkkbabe/img/21.gif","arkkbabe/img/22.gif","arkkbabe/img/23.gif",
			"arkkbabe/img/24.gif","arkkbabe/img/25.gif","arkkbabe/img/26.gif","arkkbabe/img/27.gif","arkkbabe/img/28.gif","arkkbabe/img/29.gif",
			"arkkbabe/img/30.gif"';
		}
		if ($wcesmilepath !== '') {
			$wcesmilepath = 'CKEDITOR.config.smiley_path = "'.$wcesmilepath .'";';
		}
		if ($wcesmilelist == '') {
		$wcesmilelist = '"regular_smile.png", "sad_smile.png", "wink_smile.png", "teeth_smile.png", "confused_smile.png", "tongue_smile.png",
		"embarrassed_smile.png", "omg_smile.png", "whatchutalkingabout_smile.png", "angry_smile.png", "angel_smile.png", "shades_smile.png",
		"devil_smile.png", "cry_smile.png", "lightbulb.png", "thumbs_down.png", "thumbs_up.png", "heart.png",
		"broken_heart.png", "kiss.png", "envelope.png"';
		}
	}
	if ($result['btn_hr'] == 1) { $bar5 = $bar5 . '"HorizontalRule",'; $kvobtn++; }	
	if ($bar5!='') { $bar5 = '{name: "bar5", items: ['.$bar5 .  ' ] } ,'; }
	$bar6 = ''; 
	if ($result['btn_blockquote'] == 1) { $bar6 = $bar6 . '"Blockquote","-",'; $kvobtn++; }
	if ($result['btn_code'] == 1) { $bar6 = $bar6 . '"Source"'; $kvobtn++; }
	if ($bar6!='') { $bar6 = '{name: "bar6", items: ['.$bar6 .  ' ] } ,'; }
	// Вторая панель (это чисто эмпирически - всё может быть в одну панель)
	$bar7 = ''; 
	if ($result['btn_bullist'] == 1) { $bar7 = $bar7 . '"BulletedList",'; $kvobtn++; }
	if ($result['btn_numlist'] == 1) { $bar7 = $bar7 . '"NumberedList"'; $kvobtn++; }
	if ($bar7!='') { $bar7 = '{name: "bar7", items: ['.$bar7 .  ' ] } ,'; }
	$bar8 = ''; 
	if ($result['btn_table'] == 1) { $bar8 = $bar8 . '"Table",'; $kvobtn++; }
	if ($bar8!='') { $bar8 = '{name: "bar8", items: ['.$bar8 .  ' ] }, '; }
	// Extra плагины
	$bar9 = '';
	$addexpl = '';
	if ($result['btn_video'] == 1) { $bar9 = $bar9 . '"VideoEmbed",'; $kvobtn++; 
	$addexpl = 'CKEDITOR.plugins.addExternal("videoembed","' .plugins_url( '/plugins/videoembed/',__FILE__ ). 'plugin.js");';	
	$wceexplugins = 'videoembed,';
	}
	if ($bar9!='') { $bar9 = '{name: "bar9", items: ['.$bar9 .  ' ] }, '; }
	$bar10 = '';
	if ($result['btn_codesnippet'] == 1) { $bar10 = $bar10 . '"CodeSnippet",'; $kvobtn++; 
	$wceexplugins = $wceexplugins .'codesnippet';
	$wseprescript = 'hljs.initHighlightingOnLoad();';
	$wcecodesnippetlang = 'CKEDITOR.config.codeSnippet_languages = {
				javascript: "JavaScript",
				php: "PHP",
				css: "CSS",
				html: "HTML",
				ini: "INI",
				xhtml: "XHTML",
				xml: "XML"
			};';
	}

	if ($bar10!='') { $bar10 = '{name: "bar10", items: ['.$bar10 .  ' ] }, '; }

	// Формируем тулбары
	/*$toolbar = $bar1 . $barfont. $bar2 . $bar3 . $bar4 . $bar5 . $bar6 . $bar7 . $bar8 . $bar9 . $bar10;*/
	$toolbar = $bar1 . $bar2 . $bar3 . $bar4 . $bar5 . $bar6 . $bar7 . $bar8 . $barfont . $bar9 . $bar10 ;
	/*$toolbar = '["' . $toolbar . '"]';*/
	//echo $toolbar;
	// формируем external_plugins
	if ($wceexplugins != '') {
	$wceexplugins = 'CKEDITOR.config.extraPlugins = "' . $wceexplugins . '"; ';
	}
	// Формируем языковые настройки
	/*if ($result['wce_lang']=="english") { $wcelang = '';} else {$wcelang = 'language_url : "'.plugins_url( '/js/ru.js',__FILE__ ) .'",';}*/
	switch ($result['wce_lang']) {
	case "english":
		$wcelang = '';
		break;
	case "русский":
		$wcelang = 'language_url : "'.plugins_url( '/js/ru.js',__FILE__ ) .'",';
		break;
	case "french":
		$wcelang = 'language_url : "'.plugins_url( '/js/fr_FR.js',__FILE__ ) .'",';
		break;
	}
	// Фиксированная ширина редактора
	$wcewidth = '';
	if ($result['wce_widthfix']=="1") { $wcewidth = 'width: '.$result['wce_width'].',';} 
	$wcecontentcss = '';
	if ($result['wce_edtbquotestyle'] == 1) {
		$wcecontentcss = 'content_css : "'.plugins_url( '/plugins/arkbquote/css/arkbquote.css',__FILE__ ) .'",';
	}
	$ckeskin = '';
	switch ($result['cke_skin']) {
	case "0":
		$ckeskin = '';
		break;
	case "1":
		$ckeskin = 'icy_orange';
		break;
	case "2":
		$ckeskin = 'kama';
		break;		
	case "3":
		$ckeskin = 'moonocolor';
		break;
	case "4":
		$ckeskin = 'moono-dark';
		break;		
	case "5":
		$ckeskin = 'office2013';
		break;
	}
	if ($ckeskin == '') {
		$skinurl = '';
	} else { 
	$skinurl =', { skin: "'.$ckeskin.','.plugins_url( '/ckeditor/skins/'.$ckeskin,__FILE__ ) .'/" }';
	}
	// Свои стили
	if ($result['cke_css'] == 1) {
	$ckcontentcss =plugins_url( '/css/myckeditor.css',__FILE__ );
	} else {$ckcontentcss ='';}

	
	$myeditor = ' 
		<script src="//cdn.ckeditor.com/4.11.3/full-all/ckeditor.js"></script>
        <script>
			'.$addexpl.'
			'.$wseprescript.'
			CKEDITOR.replace( "comment"'.$skinurl.');
			'.$wceexplugins.'
			'.$wcecodesnippetlang.'
			CKEDITOR.config.disableNativeSpellChecker = false;
			CKEDITOR.config.removePlugins = "scayt,contextmenu";
			CKEDITOR.config.contentsCss = "'.$ckcontentcss.'";
			/*CKEDITOR.config.skin = "moonocolor";*/
			/*CKEDITOR.config.allowedContent = true;*/
			/*CKEDITOR.config.uiColor = "#000000";*/
			'.$wcesmileycolumns.'
			'.$wcesmilepath .'
			CKEDITOR.config.smiley_images = ['.$wcesmilelist .'];
			CKEDITOR.config.toolbar = ['.$toolbar.'];
        </script>
	';
	echo $myeditor;
?>