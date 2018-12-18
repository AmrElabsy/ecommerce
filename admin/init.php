<?php
	
	include 'connect.php';	// Connect To Database called eCommerce

	// Routs
	$tpl 	= 'includes/templates/';	//Templater Folder
	$css 	= 'layout/css/';			// CSS Folder
	$js 	= 'layout/js/';				// JavaScript Folder
	$lng	= 'includes/languages/';	// Languages Floder
	$func	= 'includes/functions/';	// Functions Folder

	// Files

	include $func . 'functions.php';
	include $lng . 'english.php';		// => includes/languages/blablabla.php
	include $tpl . 'header.inc.php';	// => includes/templates/header.php
	if (!isset($noNav))
	{
		include $tpl . 'navbar.inc.php';
	}