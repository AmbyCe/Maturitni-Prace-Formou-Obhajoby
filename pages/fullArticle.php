<?php
	// Pro potřeby autentizace
	session_start();

	define('FileOpenedThroughRequire', TRUE);

	require('../core/src/secret/headerAndFooter.php');
	require('../core/src/secret/databaseConnect.php');

	srcBuildHeader("Hlavní strana");
?>

	<!-- Main page style -->
	<link href="../core/src/styles.css" rel="stylesheet">
	<script src="../core/src/script.js"></script>
</head>

<body class="customBody" style="font-family: 'Roboto', sans-serif;">

<?php
	// Header s informacemi o uživateli a možnost odhlášení
	require('../pages/dashboard/src/dashboardHeader.php');
	buildHeader('../pages/dashboard/logout.php', true, '../pages/dashboard/dashboard.php');



	// Pokud je v GETu validní článek + načtení informací o něm
	if (isset($_GET['id'])) {
		$articleId = intval($_GET['id']);
		$sql = "SELECT n.id as id, n.title as title, n.timeCreated as timeCreated, n.articleText as articleText, u.username as author FROM news n, users u WHERE u.id = n.author AND n.id = '$articleId' LIMIT 1";
		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) == 1) {
			$row = mysqli_fetch_assoc($result);
			
			$articleTitle = $row['title'];
			$articleCreatedAt = $row['timeCreated'];
			$articleText = $row['articleText'];
			$articleAuthor = $row['author'];
		} else {
			$articleTitle = "Článek s tímto ID nebyl nalezen!";
			$articleCreatedAt = "2000-01-01 00:00:00";
			$articleText = "K článku, který nebyl nalezen není žádný obash - bohužel :/";
			$articleAuthor = "Notch";
		}
	} else {
		$articleTitle = "ID článku nebylo zadáno!";
		$articleCreatedAt = "2000-01-01 00:00:00";
		$articleText = "K žádnému ID nemáme žádný obsah - bohužel :/";
		$articleAuthor = "Notch";
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
		<hr>

		<!-- Článek -->
		<div class="card mt-3" style="background: linear-gradient(#141e30, #243b55); border: 0;">
			<div class="card-body">
				<h4 class="card-title text-light"><?php echo($articleTitle) ?></h4>
				<h6 class="card-subtitle text-muted small" style="color: #6c757d!important;"><?php echo($articleCreatedAt) ?></h6>

				<hr class="text-light">

				<p class="card-text text-light text"><?php echo($articleText) ?></p>

				<hr class="text-light">

				<div class="row">
					<div class="col-6 my-auto"></div>
					<div class="col-6 text-end text-light text-opacity-50 my-auto">
						<img src="http://cravatar.eu/avatar/<?php echo($articleAuthor) ?>/24.png" class="rounded-2 w-auto h-auto me-1"> <span class="d-none d-lg-inline-block"><?php echo($articleAuthor) ?></span>
					</div>
				</div>
			</div>
		</div>

		<!-- Zpět na hlavní stranu -->
		<div class="mt-2 text-end">
			<a href="../index.php" class="btn btn-primary buttonC buttonC-1"><i class="bi bi-house"></i> Zpět na hlavní stranu</a>
		</div>

		<hr>
	</div>

<?php
	srcBuildFooter();
?>