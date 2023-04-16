<?php
	// Pro potřeby autentizace
	session_start();

	define('FileOpenedThroughRequire', TRUE);

	require('../../../core/src/secret/headerAndFooter.php');
	require('../../../core/src/secret/databaseConnect.php');

	srcBuildHeader("Detail reportu");

	// Zobrazit stránku jen, pokud je přihlášen
	if (!isset($_SESSION['username'])) {
		header("Location: ../login.php");
		exit();
	}
?>

	<!-- Dashboard page style -->
	<link href="../../../core/src/styles.css" rel="stylesheet">

	<!-- Wysiwyg editor -->
	<script src="https://cdn.tiny.cloud/1/vebjvn78l37yjzk6fvqwpxurzv10jo2ya0zo7ws9rlheice9/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
	<script>
		tinymce.init({
			selector: 'textarea#editor',
			skin: 'bootstrap',
			plugins: 'lists, link, image',
			toolbar: 'fontsize forecolor | bold italic underline strikethrough bullist numlist | alignleft aligncenter alignright | link image | undo redo selectall removeformat',
			color_default_foreground: 'white',
			font_size_formats: '8pt 10pt 12pt 14pt 18pt',
			menubar: false,
			skin: 'oxide-dark',
  			content_css: 'dark'
		});
	</script>
</head>

<script>
function postError(text) {
	Swal.fire(
		"Chyba!",
		text, 
		"error"
	)
}

function postSuccess() {
	Swal.fire({
		title: "Úspěch!",
		html: "Odpověď byla odeslána a report uzavřen",
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
</script>

<body class="customBody" style="font-family: 'Roboto', sans-serif;">

<?php
	// Header s informacemi o uživateli a možnost odhlášení
	require('../src/dashboardHeader.php');
	buildHeader('../logout.php', true, '../dashboard.php');



	// Pokud byl odeslán formulář s odpovědí
	if (isset($_POST['submit'])) {
		$reportId = intval($_GET['id']);
		if ($_POST['text'] != "") {
			$author = intval($_SESSION['id']);
			$text = $_POST['text'];

			$sql3 = "INSERT INTO reportresponses (reportId, text, author) VALUES ('$reportId', '$text', '$author')";
			$sql4 = "UPDATE reports SET solved = 1, solvedBy = '$author' WHERE id = '$reportId'";
			$result3 = mysqli_query($conn, $sql3);
			$result4 = mysqli_query($conn, $sql4);
			unset($_POST['submit']);
			echo('<script>postSuccess()</script>');

		} else {
			echo('<script>postError("Obsah odpovědi nebyl zadán");</script>');
		}
	}




	// Pokud je v GETu validní report + načtení informací o něm
	if (isset($_GET['id'])) {
		$reportId = intval($_GET['id']);
		$sql = "SELECT r.id as id, u.username as author, r.author as rAuthor, r.dateCreated as dateCreated, r.subject as rSubject, r.reportText as reportText, r.solved as solved, r.solvedBy as solvedBy FROM reports r, users u WHERE u.id = r.author AND r.id = '$reportId' LIMIT 1";
		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) == 1) {
			$row = mysqli_fetch_assoc($result);

			if ($row['rAuthor'] == $_SESSION['id'] || $_SESSION['permissions'] >= 4) {
				$reportTitle = $row['rSubject'];
				$reportCreatedAt = $row['dateCreated'];
				$reportText = $row['reportText'];
				$reportAuthor = $row['author'];
				$reportSolved = $row['solved'];

				// Získat jméno administrátora, co to vyřešil
				$reportSolvedBy = $row['solvedBy'];
				if ($row['solved'] != NULL) {
					$sql2 = "SELECT username FROM users WHERE id = '$reportSolvedBy'";
					$result2 = mysqli_query($conn, $sql2);
					$row2 = mysqli_fetch_assoc($result2);

					$reportSolvedByName = $row2['username'];
				}

				// Nastavení nadpisu stránky
				$pageTitle = "Detail reportu";

				$blockShowingPage = false;
			} else {
				$reportTitle = "Cizí report si nepřečteš!";
				$reportCreatedAt = "2000-01-01 00:00:00";
				$reportText = "Report, který jsi nevytvořil si nepřečteš - bohužel :/";
				$reportAuthor = "Notch";
				$pageTitle = "Chyba! Cizí report";

				$blockShowingPage = true;
			}
		} else {
			$reportTitle = "Report s tímto ID nebyl nalezen!";
			$reportCreatedAt = "2000-01-01 00:00:00";
			$reportText = "K reportu, který nebyl nalezen není žádný obash - bohužel :/";
			$reportAuthor = "Notch";
			$pageTitle = "Chyba! Neexistující report";

			$blockShowingPage = true;
		}
	} else {
		$reportTitle = "ID reportu nebylo zadáno!";
		$reportCreatedAt = "2000-01-01 00:00:00";
		$reportText = "K žádnému ID nemáme žádný obsah - bohužel :/";
		$reportAuthor = "Notch";
		$pageTitle = "Chyba! Nebylo zadáno ID";

		$blockShowingPage = true;
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
		<!-- Nadpis stránky -->
		<div class="text-center text-light" style="font-weight: 700;">
			<span class="fs-1"><?php echo($pageTitle); ?></span>
		</div>

		<hr>

		<!-- Report -->
		<div class="card mt-3" style="background: linear-gradient(#141e30, #243b55); border: 0; color: white; overflow-x: auto;">
			<div class="card-body">
				<h4 class="card-title text-light"><?php echo($reportTitle) ?></h4>
				<h6 class="card-subtitle text-muted small" style="color: #6c757d!important;"><?php echo($reportCreatedAt) ?></h6>

				<hr class="text-light">

				<p class="card-text text-light text"><?php echo($reportText) ?></p>

				<hr class="text-light">

				<div class="row">
					<div class="col-6 my-auto"></div>
					<div class="col-6 text-end text-light text-opacity-50 my-auto">
						<img src="http://cravatar.eu/avatar/<?php echo($reportAuthor) ?>/24.png" class="rounded-2 w-auto h-auto me-1"> <?php echo($reportAuthor) ?>
					</div>
				</div>
			</div>
		</div>

		<?php
			// Zablokovat zobrazování zbytku stránky, pokud nemá mít přístup
			if ($blockShowingPage != true) {
		?>
			<!-- Stav reportu -->
			<div class="card mt-2 mb-2" style="background: linear-gradient(#141e30, #243b55); border: 0; color: white;">
				<div class="pt-2 pb-2 text-center">
					<?php
						if ($reportSolved == NULL) {
							if ($_SESSION['permissions'] >= 4) {
								echo('<span class="badge text-bg-danger"><i class="bi bi-headset"></i> Report vyžaduje vyřešení</span>');
							} else {
								echo('<span class="badge text-bg-light"><i class="bi bi-headset"></i> Report je v řešení</span>');
							}
						} else {
							echo('<span class="badge text-bg-success"><i class="bi bi-check-circle"></i> Report byl vyřešen administrátorem ' . $reportSolvedByName . '</span>');
						}
					?>
				</div>
			</div>

			<!-- Odpověď od administrátora -->
			<?php
				if ($reportSolved != NULL) {
					$sql5 = "SELECT reportId, text, author FROM reportresponses WHERE reportId = '$reportId'";
					$result5 = mysqli_query($conn, $sql5);
					$row5 = mysqli_fetch_assoc($result5);

					// Získat jméno autora z ID
					$authorOfResponse = $row5['author'];
					$sql6 = "SELECT username FROM users WHERE id = '$authorOfResponse'";
					$result6 = mysqli_query($conn, $sql6);
					$row6 = mysqli_fetch_assoc($result6);
			?>
				<div class="card" style="background: linear-gradient(#141e30, #243b55); border: 0; color: white; overflow-x: auto;">
					<div class="card-body">
						<h4 class="card-title text-light"><i class="bi bi-reply"></i> Odpověď administrátora</h4>

						<hr class="text-light">

						<p class="card-text text-light text"><?php echo($row5['text']) ?></p>

						<hr class="text-light">

						<div class="row">
							<div class="col-6 my-auto"></div>
							<div class="col-6 text-end text-light text-opacity-50 my-auto">
								<img src="http://cravatar.eu/avatar/<?php echo($row6['username']) ?>/24.png" class="rounded-2 w-auto h-auto me-1"> <?php echo($row6['username']) ?>
							</div>
						</div>
					</div>
				</div>

			<!-- Odpovědět na report (administrátoři) -->
			<?php
				} else {
					if ($_SESSION['permissions'] >= 4) {
			?>
				<div class="card" style="background: linear-gradient(#141e30, #243b55); border: 0; color: white; overflow-x: auto;">
					<div class="card-body">
						<form method="POST">
							<textarea name="text" id="editor"></textarea>

							<hr class="text-light">

							<div class="row">
								<div class="col-12 text-center">
									<button type="submit" name="submit" value="1" class="btn btn-primary buttonC buttonC-2"><i class="bi bi-send"></i> Odeslat odpověď a vyřešit report</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			<?php
					}
				}
			?>
		<?php
			}
		?>

		<!-- Zpět do uživatelského panelu -->
		<div class="mt-2 text-center text-lg-end">
			<a href="../dashboard.php" class="btn btn-primary buttonC buttonC-1"><i class="bi bi-speedometer2"></i> Zpět do uživatelského panelu</a>
		</div>

		<hr>
	</div>

<?php
	srcBuildFooter();
?>