<?php
	$wceplugins = "paste, autoresize";
	$wceexplugins = '';
	$kvobtn = 0;
	$bar1 = '';
	if ($result['btn_undo'] == 1) { $bar1 = $bar1 . ' undo '; $kvobtn++; }
	if ($result['btn_redo'] == 1) { $bar1 = $bar1 . ' redo'; $kvobtn++; }
	if ($bar1!='') { $bar1 = $bar1 .  ' | '; }
	$barfont = '';
	if ($result['box_fontsize'] == 1) { $barfont = $barfont . ' fontsizeselect'; $kvobtn++; }
	if ($result['box_font'] == 1) { $barfont = $barfont . ' fontselect'; $kvobtn++; }
	if ($barfont!='') { $barfont = $barfont .  ' | '; }
	$bar2 = '';
	if ($result['btn_bold'] == 1) { $bar2 = $bar2 . ' bold'; $kvobtn++; }
	if ($result['btn_italic'] == 1) { $bar2 = $bar2 . ' italic'; $kvobtn++; }
	if ($bar2!='') { $bar2 = $bar2 .  ' | '; }
	$bar3 = '';
	if ($result['btn_underline'] == 1) { $bar3 = $bar3 . ' underline'; $kvobtn++; }
	if ($result['btn_strikethrough'] == 1) { $bar3 = $bar3 . ' strikethrough'; $kvobtn++; }
	if ($bar3!='') { $bar3 = $bar3 .  ' | '; }
	$bar4 = '';
	if ($result['btn_forecolor'] == 1) { $bar4 = $bar4 . ' forecolor'; $kvobtn++; $wceplugins = $wceplugins . ', textcolor'; }
	if ($result['btn_backcolor'] == 1) { $bar4 = $bar4 . ' backcolor'; $kvobtn++; }
	if ($bar4!='') { $bar4 = $bar4 .  ' | '; }
	$bar5 = '';
	if ($result['btn_link'] == 1) { $bar5 = $bar5 . ' link'; $kvobtn++; $wceplugins = $wceplugins . ', link'; }
	if ($result['btn_image'] == 1) { $bar5 = $bar5 . ' image'; $kvobtn++; $wceplugins = $wceplugins . ', image'; }
	if ($bar5!='') { $bar5 = $bar5 .  ' | '; }
	$bar6 = ''; 
	if ($result['btn_blockquote'] == 1) { $bar6 = $bar6 . ' blockquote'; $kvobtn++; }
	if ($result['btn_code'] == 1) { $bar6 = $bar6 . ' code'; $kvobtn++; $wceplugins = $wceplugins . ', code'; }
	if ($bar6!='","' && $bar6!='') { $bar6 = $bar6 .  ' | '; }
	// Вторая панель (это чисто эмпирически - всё может быть в одну панель)
	$bar7 = ''; 
	if ($result['btn_bullist'] == 1) { $bar7 = $bar7 . ' bullist'; $kvobtn++; }
	if ($result['btn_numlist'] == 1) { $bar7 = $bar7 . ' numlist'; $kvobtn++; }
	if ($bar7!='') { $bar7 = $bar7 .  ' | '; }
	$bar8 = ''; 
	if ($result['btn_table'] == 1) { $bar8 = $bar8 . ' table'; $kvobtn++; $wceplugins = $wceplugins . ', table'; }
	if ($bar8!='') { $bar8 = $bar8 .  ' | '; }
	$bar9 = '';
	if ($result['btn_emoticons'] == 1) { $bar9 = $bar9 . ' emoticons'; $kvobtn++; $wceplugins = $wceplugins . ', emoticons'; }
	if ($result['btn_arkemoticons'] == 1) { $bar9 = $bar9 . ' arkemoticons'; $kvobtn++; $wceplugins = $wceplugins . ', arkemoticons'; 
	$wceexplugins = '"arkemoticons" :  "'.plugins_url( '/plugins/arkemoticons/plugin.min.js',__FILE__ ) .'"';
	}
	if ($result['btn_arkemoticonssk'] == 1) { $bar9 = $bar9 . ' arkemoticonssk'; $kvobtn++; $wceplugins = $wceplugins . ', arkemoticonssk'; 
	if ($wceexplugins != '') {$wceexplugins = $wceexplugins . ', ';}
	$wceexplugins = $wceexplugins . '"arkemoticonssk" :  "'.plugins_url( '/plugins/arkemoticonssk/plugin.min.js',__FILE__ ) .'"';
	}
	if ($result['btn_arkkbabe'] == 1) { $bar9 = $bar9 . ' arkkbabe'; $kvobtn++; $wceplugins = $wceplugins . ', arkkbabe'; 
	if ($wceexplugins != '') {$wceexplugins = $wceexplugins . ', ';}
	$wceexplugins = $wceexplugins . '"arkkbabe" :  "'.plugins_url( '/plugins/arkkbabe/plugin.min.js',__FILE__ ) .'"';
	}
	if ($bar9!='') { $bar9 = $bar9 .  ' | '; }
	$bar10 = '';
	if ($result['btn_arkbquote'] == 1) { $bar10 = $bar10 . ' arkbquote'; $kvobtn++; $wceplugins = $wceplugins . ', arkbquote'; 
	if ($wceexplugins != '') {$wceexplugins = $wceexplugins . ', ';}
	$wceexplugins = $wceexplugins . '"arkbquote" :  "'.plugins_url( '/plugins/arkbquote/plugin.min.js',__FILE__ ) .'"';
	}
	if ($result['btn_preview'] == 1) { $bar10 = $bar10 . ' preview'; $kvobtn++; $wceplugins = $wceplugins . ', preview'; }
	if ($bar10!='') { $bar10 = $bar10 .  ' | '; }
	// Формируем тулбары
	$toolbar = $bar1 . $barfont. $bar2 . $bar3 . $bar4 . $bar5 . $bar6 . $bar7 . $bar8 . $bar9 . $bar10;
	$toolbar = '["' . $toolbar . '"]';
	//echo $toolbar;
	// формируем external_plugins
	if ($wceexplugins != '') {
	$wceexplugins = 'external_plugins :  { ' . $wceexplugins . ' }, ';
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
	case "deutsch":
		$wcelang = 'language_url : "'.plugins_url( '/js/de_DE.js',__FILE__ ) .'",';
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
	if ($result['tiny_skin'] == 0) {
		$skinurl = '';
	} else { 
	$skinurl ='skin_url: "'.plugins_url( '/tinymce/skins/'.$result['tiny_skin'],__FILE__ ) .'",';
	}
	$myeditor = '
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/tinymce/4.8.3/tinymce.min.js"></script>
		<script type="text/javascript">
		tinymce.init({
			selector: "textarea#comment",
			' . $skinurl . '
			' . $wcecontentcss . '
			valid_elements : "*[*],a[href|target=_blank]",
			' . $wcewidth . '
			' . $wcelang . '
			menubar : false,
			fontsize_formats :  "8pt 10pt 12pt 14pt 18пт 24pt 36pt",
			statusbar : false,
			convert_urls : false,
			browser_spellcheck : true,
			paste_as_text: true,
			plugins: "' . $wceplugins . '",
			' . $wceexplugins . '
			toolbar: ' . $toolbar . '


		});
	</script>
	';
	echo $myeditor;
?>