<?php
	// Pro potřeby funkčnosti header('Location: ../')
	ob_start();
	// Pro potřeby autentizace
	session_start();

	define('FileOpenedThroughRequire', TRUE);

	require('../../../core/src/secret/headerAndFooter.php');
	require('../../../core/src/secret/databaseConnect.php');

	srcBuildHeader("Správa článků");

	// Zobrazit stránku jen, pokud je přihlášen
	if (!isset($_SESSION['username'])) {
		header("Location: ../login.php");
		exit();
	}

	if (!($_SESSION['permissions'] >= 8)) {
		header("Location: ../dashboard.php");
		exit();
	}
?>

	<!-- Dashboard page style -->
	<link href="../../../core/src/styles.css" rel="stylesheet">

</head>

<script>
function postRemoveError(text) {
	Swal.fire(
		"Chyba!", 
		text, 
		"error"
	)
}

function postRemoveSuccess() {
	Swal.fire({
		title: "Článek vymazán!",
		text: "Článek byl vymazán z databáze",
		icon: "success",
		confirmButtonColor: "#198754",
		confirmButtonText: "Otevřít hlavní stranu"
	}).then((result) => {
		if (result.isConfirmed) {
			window.location.href = "../../../index.php";
		} else if (result.isDismissed) {
			window.location.href = "./administerArticles.php";
		}
	})
}
</script>

<body class="customBody">

<?php
	// Header s informacemi o uživateli a možnost odhlášení
	require('../src/dashboardHeader.php');
	buildHeader('../logout.php', true, '../dashboard.php');



	// Pokud byla odeslána žádost o smazání článku
	if (isset($_GET['delete'])) {
		if (!($_SESSION['permissions'] >= 8)) {
			exit();
		} else {
			$id = validate($_GET['delete']);

			$sql = "SELECT * FROM news WHERE id='$id' LIMIT 1";
			$result = mysqli_query($conn, $sql);
			if (mysqli_num_rows($result) == 1) {
				$sql2 = "DELETE FROM news WHERE id='$id'";
				$result2 = mysqli_query($conn, $sql2);
				unset($_GET['delete']);
				echo('<script>postRemoveSuccess()</script>');
			} else {
				echo('<script>postRemoveError("Článek s tímto ID nebyl nalezen")</script>');
			}
		}
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

<div class="container" style="font-family: 'Roboto', sans-serif;">
	<!-- Nadpis -->
	<div class="text-center text-light" style="font-weight: 700;">
		<span class="fs-1">Správa článků</span>
	</div>

	<hr class="d-none d-lg-block">

	<!-- Legenda ke sloupcům -->
	<div class="row">
		<div class="col-lg-1 text-truncate text-warning d-none d-lg-block">
			<strong>ID</strong>
		</div>

		<div class="col-lg-2 text-truncate text-warning d-none d-lg-block">
			<strong>Čas vytvoření</strong>
		</div>

		<div class="col-lg-6 text-truncate text-warning d-none d-lg-block">
			<strong>Nadpis</strong>
		</div>

		<div class="col-lg-2 text-truncate text-warning d-none d-lg-block">
			<strong>Autor</strong>
		</div>

		<div class="col-lg-1 text-truncate text-warning d-none d-lg-block">
			<strong>Správa</strong>
		</div>
	</div>

	<hr>

	<?php
		$sql =  "SELECT n.id as id, n.title as title, n.timeCreated as timeCreated, u.username as author FROM news n, users u WHERE u.id = n.author ORDER BY id DESC";
		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) >= 1) {
			while ($row = mysqli_fetch_assoc($result)) {
				echo('
					<div class="row ms-2 ms-lg-0 me-2 me-lg-0">
						<div class="col-12 col-lg-1 text-center text-lg-start text-truncate text-light">
							<strong>' . $row['id'] . '.</strong>
						</div>

						<div class="col-12 col-lg-2 text-center text-lg-start text-truncate text-light" style="color: #6c757d!important;">
							<strong>' . $row['timeCreated'] . '</strong>
						</div>

						<div class="col-12 col-lg-6 text-center text-lg-start text-truncate text-light">
							' . $row['title'] . '
						</div>

						<div class="col-12 col-lg-2 text-center text-lg-start text-truncate text-light" style="color: #6c757d!important;">
							' . $row['author'] . '
						</div>

						<div class="col-12 col-lg-1 row text-light text-center text-lg-end">
							<div class="col-6 text-center text-lg-end">
								<a href="../../../pages/fullArticle.php?id=' . $row['id'] . '" class="btn btn-sm text-light buttonC buttonC-1 me-0 me-lg-1">
									<i class="bi bi-search"></i>
								</a>
							</div>

							<div class="col-6 text-center text-lg-end">
								<a href="./administerArticles.php?delete=' . $row['id'] . '" class="btn btn-sm text-light buttonC buttonC-3">
									<i class="bi bi-trash"></i>
								</a>
							</div>
						</div>
					</div>

					<hr>
				');
			}
		}
	?>
</div>