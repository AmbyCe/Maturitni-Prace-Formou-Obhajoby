<?php
	// Pro potřeby funkčnosti header('Location: ../')
	ob_start();
	// Pro potřeby autentizace
	session_start();

	define('FileOpenedThroughRequire', TRUE);

	require('../../core/src/secret/headerAndFooter.php');

	srcBuildHeader("Uživatelský panel");

	// Zobrazit stránku jen, pokud je přihlášen
	if (!isset($_SESSION['username'])) {
		header("Location: login.php");
		exit();
	}
?>

	<!-- Dashboard page style -->
	<link href="../../core/src/styles.css" rel="stylesheet">
</head>

<body class="customBody">

<?php
	// Header s informacemi o uživateli a možnost odhlášení
	require('./src/dashboardHeader.php');
	buildHeader('./logout.php', false, '../../index.php');
?>

<div class="container">
	<div class="row">
		<div class="col-1 col-lg-4"></div>
		<div class="col-10 col-lg-4">
			<div class="text-center mb-2">
				<a href="../../index.php">
					<img src="../../core/img/bigLogo.png" class="img-fluid" style="max-height: 75%; max-width: 80%;">
				</a>
			</div>
		</div>
		<div class="col-1 col-lg-4"></div>
	</div>
</div>

<div class="container" style="font-family: 'Roboto', sans-serif;">
	<!-- Nadpis -->
	<div class="text-center text-light" style="font-weight: 700;">
		<span class="fs-1">Uživatelský panel</span>
	</div>

	<hr>

	<!-- Informace o uživateli -->
	<div class="row position-lg-relative">
		<div class="col-12 col-lg-4 text-center" style="background-color: #0d1129;">
			<img class="pt-4 pb-4" src="https://visage.surgeplay.com/full/160/<?php echo(username_to_uuid(validate($_SESSION['username']))) ?>">
		</div>
		<div class="col-12 col-lg-8 text-light" style="background-color: #0d1129;">
			<div class="pt-4">
				<span class="fs-4" style="font-weight: 500;">Vítej <?php echo(validate($_SESSION['username'])) ?></span><br />

				<ul class="text-secondary">
					<li><small><strong>Hodnost:</strong> <span style="color: <?php echo(permissionToRankcolor($_SESSION['permissions'])) ?>"><?php echo(permissionToRankname($_SESSION['permissions'])) ?></span></small></li>
					<li><small><strong>Hráčské UUID:</strong> <?php echo(username_to_uuid(validate($_SESSION['username']))) ?></small></li>
					<li><small><strong>ID uživatele:</strong> #<?php echo($_SESSION['id']) ?></small></li>
				</ul>
			</div>
		</div>
	</div>

	<hr>

	<!-- Administrace -->
	<div class="text-light" style="font-weight: 500;">
		<span class="fs-3"><i class="bi bi-person-lock"></i> Administrace</span>
	</div>

	<div class="row text-center pt-4 pb-4" style="background-color: #0d1129;">
		<div class="col-12 col-lg-6">
			<a href="./pages/writeArticle.php" class="btn btn-primary buttonC buttonC-2"><i class="bi bi-pencil"></i> Napsat nový článek</a>
		</div>
		<div class="col-12 col-lg-6">
			<a href="./pages/administerArticles.php" class="btn btn-primary buttonC buttonC-4"><i class="bi bi-pencil-square"></i> Správa článků</a>
		</div>
	</div>

	<hr>

	<!-- Soukromé zprávy -->
	<div class="text-light" style="font-weight: 500;">
		<span class="fs-3"><i class="bi bi-envelope"></i> Soukromé zprávy</span>
	</div>

	<div class="text-center pt-4 pb-4 d-lg-flex flex-lg-row-reverse" style="background-color: #0d1129;">
		<a href="" class="btn btn-primary buttonC buttonC-2 me-lg-4"><i class="bi bi-pencil"></i> Nová soukromá zpráva</a>
	</div>

	<hr>
</div>

<?php
	srcBuildFooter();
	// Pro potřeby funkčnosti header('Location: ../')
	ob_end_flush();
?>