<?php
	// Pro potřeby funkčnosti header('Location: ../')
	ob_start();
	// Pro potřeby autentizace
	session_start();

	define('FileOpenedThroughRequire', TRUE);

	require('../../../core/src/secret/headerAndFooter.php');
	require('../../../core/src/secret/databaseConnect.php');

	srcBuildHeader("Správa wiki");

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
function postError(text) {
	Swal.fire(
		"Chyba!",
		text, 
		"error"
	)
}

function postSuccess(text) {
	Swal.fire({
		title: "Úspěch!",
		html: "Kategorie s názvem <strong>" + text + "</strong> byla vytvořena",
		icon: "success",
		confirmButtonColor: "#198754",
		confirmButtonText: "Otevřít uživatelský panel"
	}).then((result) => {
		if (result.isConfirmed) {
			window.location.href = "../dashboard.php";
		} else if (result.isDismissed) {
			window.location.href = "../dashboard.php";
		}
	})
}



function postRemoveError(text) {
	Swal.fire(
		"Chyba!", 
		text, 
		"error"
	)
}

function postRemoveSuccess() {
	Swal.fire({
		title: "Stránka vymazána!",
		text: "Stránka byla vymazána z databáze",
		icon: "success",
		confirmButtonColor: "#198754",
		confirmButtonText: "Otevřít hlavní stranu"
	}).then((result) => {
		if (result.isConfirmed) {
			window.location.href = "../../../index.php";
		} else if (result.isDismissed) {
			window.location.href = "./administerWiki.php";
		}
	})
}
</script>

<body class="customBody">

<?php
	// Header s informacemi o uživateli a možnost odhlášení
	require('../src/dashboardHeader.php');
	buildHeader('../logout.php', true, '../dashboard.php');




	// Pokud byla odeslána tvorba nové kategorie
	if (isset($_POST['submit'])) {
		if (!($_SESSION['permissions'] >= 8)) {
			exit();
		} else {
			if ($_POST['catName'] != "") {
				$title = validate($_POST['catName']);

				$sql = "INSERT INTO wikicategories (title) VALUES ('$title')";
				$result = mysqli_query($conn, $sql);
				unset($_POST['submit']);
				echo('<script>postSuccess("'. $title . '")</script>');
			} else {
				echo('<script>postError("Název kategorie nebyl zadán");</script>');
			}
		}
	}




	// Pokud byla odeslána žádost o smazání článku
	if (isset($_GET['delete'])) {
		if (!($_SESSION['permissions'] >= 8)) {
			exit();
		} else {
			$id = validate($_GET['delete']);

			$sql = "SELECT * FROM wikipages WHERE id='$id' LIMIT 1";
			$result = mysqli_query($conn, $sql);
			if (mysqli_num_rows($result) == 1) {
				$sql2 = "DELETE FROM wikipages WHERE id='$id'";
				$result2 = mysqli_query($conn, $sql2);
				unset($_GET['delete']);
				echo('<script>postRemoveSuccess()</script>');
			} else {
				echo('<script>postRemoveError("Stránka s tímto ID nebyla nalezena")</script>');
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
		<span class="fs-1">Správa stránek wiki</span>
	</div>

	<hr class="d-none d-lg-block">

	<!-- Vytvoření nové kategorie -->
	<form method="POST">
		<div class="row">
			<label for="enterCatName" class="col-lg-3 col-form-label text-light">Název kategorie:</label>
			<div class="col-lg-9">
				<input type="text" name="catName" class="form-control text-light" style="background-color: #222f3e; border: 0;">
			</div>
		</div>

		<div class="mt-3 text-center">
			<button type="submit" name="submit" value="1" class="btn btn-primary buttonC buttonC-2"><i class="bi bi-cloud-upload"></i> Vytvořit novou kategorii</button>
		</div>
	</form>

	<hr class="d-none d-lg-block">

	<!-- Legenda ke sloupcům -->
	<div class="row">
		<div class="col-lg-1 text-truncate text-warning d-none d-lg-block">
			<strong>ID</strong>
		</div>

		<div class="col-lg-10 text-truncate text-warning d-none d-lg-block">
			<strong>Nadpis</strong>
		</div>

		<div class="col-lg-1 text-truncate text-warning d-none d-lg-block">
			<strong>Správa</strong>
		</div>
	</div>

	<hr>

	<?php
		$sql3 =  "SELECT id, title, content FROM wikipages ORDER BY id DESC";
		$result3 = mysqli_query($conn, $sql3);

		if (mysqli_num_rows($result3) >= 1) {
			while ($row3 = mysqli_fetch_assoc($result3)) {
				echo('
					<div class="row ms-2 ms-lg-0 me-2 me-lg-0">
						<div class="col-12 col-lg-1 text-center text-lg-start text-truncate text-light">
							<strong>' . $row3['id'] . '.</strong>
						</div>

						<div class="col-12 col-lg-10 text-center text-lg-start text-truncate text-light">
							' . $row3['title'] . '
						</div>

						<div class="col-12 col-lg-1 row text-light text-center text-lg-end">
							<div class="col-6 text-center text-lg-end">
								<a href="../../../pages/wikiPage.php?id=' . $row3['id'] . '" class="btn btn-sm text-light buttonC buttonC-1 me-0 me-lg-1">
									<i class="bi bi-search"></i>
								</a>
							</div>

							<div class="col-6 text-center text-lg-end">
								<a href="./administerWiki.php?delete=' . $row3['id'] . '" class="btn btn-sm text-light buttonC buttonC-3">
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