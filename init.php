<?php
		
	// Seission Data

	$ssnuser = '';
	if (isset($_SESSION['user']))
	{
		$ssnuser 	= $_SESSION['user'];
	}


	include 'admin/connect.php';	// Connect To Database called eCommerce

	if (isset($_SESSION['user']))
	{
		$stmt = $con->prepare('SELECT * FROM members WHERE UserName = ?');
		$stmt->execute(array($ssnuser));
		$user = $stmt->fetch();
		$ssnid = $user['UserID'];
	}

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
	
