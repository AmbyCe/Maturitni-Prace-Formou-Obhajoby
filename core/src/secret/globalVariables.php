<?php
	// Nedovolit přístup k souboru přímou cestou, jedině pokud na něj odkazuje jiný soubor
	if(!defined('FileOpenedThroughRequire')) {
		die('Přímý přístup k souborům webu není možný.');
	}



	// Nastavení proměnných využívaných napříč celým projektem
	define('globalVariables_pageTitleStart', 'UniverseMC.cz');
?>