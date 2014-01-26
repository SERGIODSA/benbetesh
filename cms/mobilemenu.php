<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "userfn10.php" ?>
<?php
	ew_Header(TRUE);
	$conn = ew_Connect();
	$Language = new cLanguage();

	// Security
	$Security = new cAdvancedSecurity();
	if (!$Security->IsLoggedIn()) $Security->AutoLogin();
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $Language->Phrase("MobileMenu") ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="<?php echo ew_jQueryFile("jquery.mobile-%v.min.css") ?>">
<link rel="stylesheet" type="text/css" href="<?php echo EW_PROJECT_STYLESHEET_FILENAME ?>">
<link rel="stylesheet" type="text/css" href="phpcss/ewmobile.css">
<script type="text/javascript" src="<?php echo ew_jQueryFile("jquery-%v.min.js") ?>"></script>
<script type="text/javascript">

	//$(document).bind("mobileinit", function() {
	//	jQuery.mobile.ajaxEnabled = false;
	//	jQuery.mobile.ignoreContentEnabled = true;
	//});

</script>
<script type="text/javascript" src="<?php echo ew_jQueryFile("jquery.mobile-%v.min.js") ?>"></script>
<meta name="generator" content="PHPMaker v10.0.3">
</head>
<body>
<div data-role="page">
	<div data-role="header">
		<h1><?php echo $Language->ProjectPhrase("BodyTitle") ?></h1>
	</div>
	<div data-role="content">
<?php $RootMenu = new cMenu("RootMenu", TRUE); ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(15, $Language->MenuPhrase("15", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(10, $Language->MenuPhrase("10", "MenuText"), "ss_indexlist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(6, $Language->MenuPhrase("6", "MenuText"), "noticiaslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(4, $Language->MenuPhrase("4", "MenuText"), "_menulist.php", 14, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(31, $Language->MenuPhrase("31", "MenuText"), "", 14, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(3, $Language->MenuPhrase("3", "MenuText"), "marcaslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(29, $Language->MenuPhrase("29", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(32, $Language->MenuPhrase("32", "MenuText"), "benbeteshlist.php", 29, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(11, $Language->MenuPhrase("11", "MenuText"), "ss_nosotroslist.php", 29, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(5, $Language->MenuPhrase("5", "MenuText"), "nosotroslist.php", 29, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(7, $Language->MenuPhrase("7", "MenuText"), "oficinaslist.php", 29, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(1, $Language->MenuPhrase("1", "MenuText"), "form_contactolist.php", 29, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(49, $Language->MenuPhrase("49", "MenuText"), "", 29, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(34, $Language->MenuPhrase("34", "MenuText"), "form_empleolist.php", 49, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(35, $Language->MenuPhrase("35", "MenuText"), "trabajolist.php", 49, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(-1, $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
	</div><!-- /content -->
</div><!-- /page -->
</body>
</html>
<?php

	 // Close connection
	$conn->Close();
?>
