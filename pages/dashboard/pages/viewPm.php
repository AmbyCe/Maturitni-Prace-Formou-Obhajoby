<?php
	// Pro potřeby autentizace
	session_start();

	define('FileOpenedThroughRequire', TRUE);

	require('../../../core/src/secret/headerAndFooter.php');
	require('../../../core/src/secret/databaseConnect.php');

	srcBuildHeader("Detail soukromé zprávy");

	// Zobrazit stránku jen, pokud je přihlášen
	if (!isset($_SESSION['username'])) {
		header("Location: ../login.php");
		exit();
	}
?>

	<!-- Dashboard page style -->
	<link href="../../../core/src/styles.css" rel="stylesheet">
</head>

<body class="customBody" style="font-family: 'Roboto', sans-serif;">

<?php
	// Header s informacemi o uživateli a možnost odhlášení
	require('../src/dashboardHeader.php');
	buildHeader('../logout.php', true, '../dashboard.php');



	// Pokud je v GETu validní zpráva + načtení informací o ní
	if (isset($_GET['id'])) {
		$msgId = intval($_GET['id']);
		$sql = "SELECT m.msgId as msgId, m.fromUser as fromUser, m.toUser as toUser, m.timeCreated as timeCreated, m.title as title, m.content as content, m.msgReaded as msgReaded, u.username as author FROM privatemessages m, users u WHERE u.id = m.fromUser AND m.msgId = '$msgId' LIMIT 1";
		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) == 1) {
			$row = mysqli_fetch_assoc($result);

			if ($row['fromUser'] == $_SESSION['id'] || $row['toUser'] == $_SESSION['id']) {
				$msgTitle = $row['title'];
				$msgCreatedAt = $row['timeCreated'];
				$msgText = $row['content'];
				$msgAuthor = $row['author'];

				// Nastavení nadpisu stránky a přečtení
				if ($row['fromUser'] == $_SESSION['id']) {
					$pageTitle = "Detail odeslané zprávy";
				} else {
					$pageTitle = "Detail přijaté zprávy";

					// Nastavit jako přečteno, pokud je příjemcem zprávy
					if ($row['msgReaded'] == NULL) {
						$sql2 = "UPDATE privatemessages SET msgReaded = 1 WHERE msgId = '$msgId'";
						$result2 = mysqli_query($conn, $sql2);
					}
				}
			} else {
				$msgTitle = "Cizí zprávu si nepřečteš!";
				$msgCreatedAt = "2000-01-01 00:00:00";
				$msgText = "Zprávu, kterou jsi nevytvořil, nebo nepřijal si nepřečteš - bohužel :/";
				$msgAuthor = "Notch";
				$pageTitle = "Chyba! Cizí zpráva";
			}
		} else {
			$msgTitle = "Zpráva s tímto ID nebyla nalezena!";
			$msgCreatedAt = "2000-01-01 00:00:00";
			$msgText = "K zprávě, která nebyla nalezena není žádný obash - bohužel :/";
			$msgAuthor = "Notch";
			$pageTitle = "Chyba! Neexistující zpráva";
		}
	} else {
		$msgTitle = "ID zprávy nebylo zadáno!";
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
					<a href="../../../index.php">
						<img src="../../../core/img/bigLogo.png" class="img-fluid" style="max-height: 75%; max-width: 80%;">
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
		<div class="card mt-3" style="background: linear-gradient(#141e30, #243b55); border: 0; color: white;">
			<div class="card-body">
				<h4 class="card-title text-light"><?php echo($msgTitle) ?></h4>
				<h6 class="card-subtitle text-muted small" style="color: #6c757d!important;"><?php echo($msgCreatedAt) ?></h6>

				<hr class="text-light">

				<p class="card-text text-light text"><?php echo($msgText) ?></p>

				<hr class="text-light">

				<div class="row">
					<div class="col-6 my-auto"></div>
					<div class="col-6 text-end text-light text-opacity-50 my-auto">
						<img src="http://cravatar.eu/avatar/<?php echo($msgAuthor) ?>/24.png" class="rounded-2 w-auto h-auto me-1"> <?php echo($msgAuthor) ?>
					</div>
				</div>
			</div>
		</div>

		<!-- Zpět do uživatelského panelu -->
		<div class="mt-2 text-center text-lg-end">
			<a href="../pages/writePm.php" class="btn btn-primary buttonC buttonC-2 m-2 m-lg-0 me-lg-1"><i class="bi bi-pencil"></i> Nová soukromá zpráva</a>
			<a href="../dashboard.php" class="btn btn-primary buttonC buttonC-1"><i class="bi bi-speedometer2"></i> Zpět do uživatelského panelu</a>
		</div>

		<hr>
	</div>

<?php
	srcBuildFooter();
?>