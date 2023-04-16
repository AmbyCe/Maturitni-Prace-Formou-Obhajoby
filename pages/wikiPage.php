<?php
	// Pro potřeby autentizace
	session_start();

	define('FileOpenedThroughRequire', TRUE);

	require('../core/src/secret/headerAndFooter.php');
	require('../core/src/secret/databaseConnect.php');

	srcBuildHeader("Stránka wiki");
?>

	<!-- Dashboard page style -->
	<link href="../core/src/styles.css" rel="stylesheet">
</head>

<body class="customBody" style="font-family: 'Roboto', sans-serif;">

<?php
	// Header s informacemi o uživateli a možnost odhlášení
	require('./dashboard/src/dashboardHeader.php');
	buildHeader('./dashboard/logout.php', true, './dashboard/dashboard.php');



	// Pokud je v GETu validní stránka wiki + načtení informací o ní
	if (isset($_GET['id'])) {
		$wikiId = intval($_GET['id']);
		$sql = "SELECT id, title, content FROM wikipages WHERE id = '$wikiId' LIMIT 1";
		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) == 1) {
			$row = mysqli_fetch_assoc($result);

			$wikiTitle = $row['title'];
			$wikiContent = $row['content'];

			// Nastavení nadpisu stránky
			$pageTitle = $wikiTitle;

		} else {
			$msgTitle = "Wiki s tímto ID nebyla nalezena!";
			$msgCreatedAt = "2000-01-01 00:00:00";
			$msgText = "K wiki, která nebyla nalezena není žádný obash - bohužel :/";
			$msgAuthor = "Notch";
			$pageTitle = "Chyba! Neexistující stránka wiki";
		}
	} else {
		$msgTitle = "ID wiki nebylo zadáno!";
		$msgCreatedAt = "2000-01-01 00:00:00";
		$msgText = "K žádnému ID nemáme žádný obsah - bohužel :/";
		$msgAuthor = "Notch";
		$pageTitle = "Chyba! Nebylo zadáno ID";
	}
?>

	<div class="container">
		<div class="row">
			<div class="col-1 col-lg-4"></div>
			<div class="col-10 col-lg-4">
				<div class="text-center mb-2">
					<a href="../index.php">
						<img src="../core/img/bigLogo.png" class="img-fluid" style="max-height: 75%; max-width: 80%;">
					</a>
				</div>
			</div>
			<div class="col-1 col-lg-4"></div>
		</div>
	</div>

	<div class="container">
		<!-- Nadpis -->
		<div class="text-center text-light" style="font-weight: 700;">
			<span class="fs-1"><?php echo($pageTitle); ?></span>
		</div>

		<hr>

		<!-- Zpráva -->
		<div class="card mt-3" style="background: linear-gradient(#141e30, #243b55); border: 0; color: white; overflow-x: auto;">
			<div class="card-body">
				<p class="card-text text-light text"><?php echo($wikiContent) ?></p>
			</div>
		</div>

		<!-- Zpět do uživatelského panelu -->
		<div class="mt-2 text-center text-lg-end">
			<a href="./wiki.php" class="btn btn-primary buttonC buttonC-9"><i class="bi bi-body-text"></i> Zpět na rozcestník wiki</a>
			<a href="../index.php" class="btn btn-primary buttonC buttonC-1"><i class="bi bi-house"></i> Zpět na hlavní stránku</a>
		</div>

		<hr>
	</div>

<?php
	srcBuildFooter();
?>