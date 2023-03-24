<?php
	// Pro potřeby funkčnosti header('Location: ../')
	ob_start();
	// Pro potřeby autentizace
	session_start();

	define('FileOpenedThroughRequire', TRUE);

	require('../../core/src/secret/headerAndFooter.php');
	require('../../core/src/secret/databaseConnect.php');

	srcBuildHeader("Odhlášení");

	// Zobrazit stránku jen, pokud je přihlášen
	if (isset($_SESSION['username'])) {
		session_unset();
		session_destroy();
	} else {
		header("Location: login.php");
		exit();
	}
?>

	<!-- Login page style -->
	<link href="../../core/src/styles.css" rel="stylesheet">
</head>

<script>
function logoutSuccess() {
	Swal.fire({
		title: "Úspěšné odhlášení!",
		text: "Nyní můžeš bezpečně opustit stránky",
		icon: "success",
		confirmButtonColor: "#198754",
		confirmButtonText: "Otevřít hlavní stránku"
	}).then((result) => {
		if (result.isConfirmed) {
			window.location.href = "../../index.php";
		} else if (result.isDismissed) {
			window.location.href = "../../index.php";
		}
	})
}
</script>

<body class="customBody">
	<script>logoutSuccess()</script>
	<div class="container">

		<div class="row">
			<div class="col-1 col-lg-4"></div>
			<div class="col-10 col-lg-4">
				<div class="text-center mb-2">
					<img src="../../core/img/bigLogo.png" class="img-fluid" style="max-height: 75%; max-width: 80%;">
				</div>
			<div class="col-1 col-lg-4"></div>
		</div>

	</div>

<?php
	srcBuildFooter();
	// Pro potřeby funkčnosti header('Location: ../')
	ob_end_flush();
?>