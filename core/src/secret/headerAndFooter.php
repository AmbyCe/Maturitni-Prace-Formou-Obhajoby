<?php
	// Nedovolit přístup k souboru přímou cestou, jedině pokud na něj odkazuje jiný soubor
	if(!defined('FileOpenedThroughRequire')) {
		die('Přímý přístup k souborům webu není možný.');
	}



	// Include
	require(__DIR__.'/globalVariables.php');



	// Vytvoření headeru - možné zavolat z každé stránky s custom titlem
	function srcBuildHeader($pageTitle) {
		echo '<!DOCTYPE HTML>
<html lang="cs">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>' . globalVariables_pageTitleStart . ' - ' . $pageTitle . '</title>
	<link rel="icon" href="' . __DIR__ . '\..\..\img\favicon.ico">

	<!-- ### Includes ### -->
	<!-- Bootstrap: --> <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
	<!-- Bootstrap Icons: --> <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
	<!-- SweetAlert2: --> <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<!-- Google ReCaptcha: --> <script src="https://www.google.com/recaptcha/api.js" async defer></script>
';
	}



	// Vytvoření footeru - možné zavolat z každé stránky
	function srcBuildFooter() {
		echo '

	<div class="text-center text-muted mb-3" style="color: #6c757d!important;">
		Andrej Ambrož V4C
	</div>

	<!-- JQuery -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

	<!-- Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.6.0/umd/popper.min.js" integrity="sha512-BmM0/BQlqh02wuK5Gz9yrbe7VyIVwOzD1o40yi1IsTjriX/NGF37NyXHfmFzIlMmoSIBXgqDiG1VNU6kB5dBbA==" crossorigin="anonymous"></script>
	
	<!-- Bootstrap -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>';
	}
?>