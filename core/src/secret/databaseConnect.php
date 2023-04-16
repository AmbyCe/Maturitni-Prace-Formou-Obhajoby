<?php
	// Nedovolit přístup k souboru přímou cestou, jedině pokud na něj odkazuje jiný soubor
	if(!defined('FileOpenedThroughRequire')) {
		die('Přímý přístup k souborům webu není možný.');
	}



	// Database connect
	$db_host = "localhost";
	$db_user = "panel";
	$db_password = "M-Wu-)7M@aaCXZHF";
	$db_name = "userpanel";

	$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

	if (!$conn) {
		echo "Database connection failed!";
	}
?>