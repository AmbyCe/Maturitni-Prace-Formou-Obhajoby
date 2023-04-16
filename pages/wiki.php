<?php
	// Pro potřeby funkčnosti header('Location: ../')
	ob_start();
	// Pro potřeby autentizace
	session_start();

	define('FileOpenedThroughRequire', TRUE);

	require('../core/src/secret/headerAndFooter.php');
	require('../core/src/secret/databaseConnect.php');

	srcBuildHeader("Wiki");
?>

	<!-- Dashboard page style -->
	<link href="../core/src/styles.css" rel="stylesheet">

</head>

<body class="customBody">

	<?php
		// Header s informacemi o uživateli a možnost odhlášení
		require('./dashboard/src/dashboardHeader.php');
		buildHeader('./dashboard/logout.php', true, './dashboard/dashboard.php');
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

	<div class="container" style="font-family: 'Roboto', sans-serif;">
		<!-- Nadpis -->
		<div class="text-center text-light" style="font-weight: 700;">
			<span class="fs-1">Znalostní báze - Wiki</span>
		</div>

		<hr>

		<?php
			// Získání kategorií wiki
			$wikiCategories = array();
			$sql = "SELECT id, title FROM wikicategories";
			$result = mysqli_query($conn, $sql);

			if (mysqli_num_rows($result) > 0) {
				while ($row = mysqli_fetch_assoc($result)) {
					array_push($wikiCategories, array($row['id'], $row['title']));
				}
			}

			// Získání jednotlivých stránek
			$wikiPages = array();
			$sql2 = "SELECT id, title, category FROM wikipages";
			$result2 = mysqli_query($conn, $sql2);

			if (mysqli_num_rows($result2) > 0) {
				while ($row2 = mysqli_fetch_assoc($result2)) {
					array_push($wikiPages, array($row2['category'], $row2['id'], $row2['title']));
				}
			}
		?>

		<div class="row">
			<?php
				$countShownCategories = 1;

				// Vykreslení kategorií s názvy článků
				foreach ($wikiCategories as $wikiCat) {
					if ($countShownCategories > 3) {
						$countShownCategories = 1;
						echo('<hr class="mt-3">');
					}
					$countShownCategories++;
					echo('
						<div class="col-12 col-lg-4">
							<h1 class="h4 fw-bold mb-3 mt-0" style="color: #f9f9fa;">' . $wikiCat[1] . '</h1>
							<div class="list-group">
					');

					foreach ($wikiPages as $wikiPag) {
						if ($wikiPag[0] == $wikiCat[0]) {
							echo('<a class="list-group-item list-group-item-action m-1" style="background-color: #0d1129; color: #9293b5; border: 0;" href="wikiPage.php?id=' . $wikiPag[1] . '">' . $wikiPag[2] . '</a>');
						}
					}

					echo('
							</div>
						</div>
					');
				}
			?>
		</div>

		<hr>
	</div>
<?php
	srcBuildFooter();
?>