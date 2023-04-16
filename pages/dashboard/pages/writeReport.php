<?php
	// Pro potřeby funkčnosti header('Location: ../')
	ob_start();
	// Pro potřeby autentizace
	session_start();

	define('FileOpenedThroughRequire', TRUE);

	require('../../../core/src/secret/headerAndFooter.php');
	require('../../../core/src/secret/databaseConnect.php');

	srcBuildHeader("Nový report");

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

function postSuccess(postTitle) {
	Swal.fire({
		title: "Úspěch!",
		html: "Report s předmětem <b>" + postTitle + "</b> byl odeslán",
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

function reasonChanged(reportReasonElement) {
	if (reportReasonElement.value == 1) {
		document.getElementById('reportTitle').innerText = "Jméno hráče a prohřešek:";
		document.getElementById('titleHint').innerHTML = "<small><em><strong>Příklad:</strong> _AmbY_ / Nadávky v chatu</em></small>";
		document.getElementById('subjectHint').innerHTML = "<small><em>Popiš prohřešek hráče, případnou lokaci a důkazy.</em></small>";
	} else if (reportReasonElement.value == 2) {
		document.getElementById('reportTitle').innerText = "Tvůj nick a důvod banu:";
		document.getElementById('titleHint').innerHTML = "<small><em><strong>Příklad:</strong> _AmbY_ / Cheating</em></small>";
		document.getElementById('subjectHint').innerHTML = "<small><em>Popiš okolnosti banu, případně přilož důkazy.</em></small>";
	} else if (reportReasonElement.value == 3) {
		document.getElementById('reportTitle').innerText = "Nick administrátora a důvod:";
		document.getElementById('titleHint').innerHTML = "<small><em><strong>Příklad:</strong> _AmbY_ / Zneužívání práv</em></small>";
		document.getElementById('subjectHint').innerHTML = "<small><em>Popiš prohřešek administrátora a přilož důkazy.</em></small>";
	} else if (reportReasonElement.value == 4) {
		document.getElementById('reportTitle').innerText = "Tvůj nick a problém s VIP:";
		document.getElementById('titleHint').innerHTML = "<small><em><strong>Příklad:</strong> _AmbY_ / Nelze aktivovat VIP</em></small>";
		document.getElementById('subjectHint').innerHTML = "<small><em>Popiš problém s VIP výhodami, případně přilož důkazy.</em></small>";
	} else {
		document.getElementById('reportTitle').innerText = "Předmět reportu:";
		document.getElementById('titleHint').innerHTML = "";
		document.getElementById('subjectHint').innerHTML = "";
	}
}
</script>

<body class="customBody">

<?php
	// Header s informacemi o uživateli a možnost odhlášení
	require('../src/dashboardHeader.php');
	buildHeader('../logout.php', true, '../dashboard.php');




	// Pokud byl odeslán formulář s reportem
	if (isset($_POST['submit'])) {
		if ($_POST['title'] != "" && $_POST['text'] != "") {
			$author = $_SESSION['id'];
			$title = validate($_POST['title']);
			$text = $_POST['text'];

			if (strlen($title) > 40) {
				echo('<script>postError("Předmět reportu je příliš dlouhý - má: <strong>' . strlen($title) . ' znaků</strong>");</script>');
			} else {
				if (strlen($text) > 10000) {
					echo('<script>postError("Obsah reportu je příliš dlouhý - má: <strong>' . strlen($text) . ' znaků</strong>");</script>');
				} else {
					$sql = "INSERT INTO reports (author, subject, reportText) VALUES ('$author', '$title', '$text')";
					$result = mysqli_query($conn, $sql);
					unset($_POST['submit']);
					echo('<script>postSuccess("'. $title . '")</script>');
				}
			}

		} else {
			echo('<script>postError("Předmět a/nebo obsah zprávy nebyl zadán");</script>');
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
		<span class="fs-1">Nový report</span>
	</div>

	<hr>

	<div class="mb-2">
		<small class="text-light"><i class="bi bi-info-circle text-info"></i> V předmětu nelze použít HTML, BBCODE a jiné. Je taktéž limitován na <strong>40 znaků</strong>.</small><br />
		<small class="text-light"><i class="bi bi-info-circle text-info"></i> Obsah reportu je limitován na <strong>10 000 znaků</strong>.</small><br />
		<small class="text-light"><i class="bi bi-info-circle text-info"></i> Důkazy nahrávejte na: <a href="https://ctrlv.cz" target="blank_" class="link-underline-info text-info">ctrlv.cz</a>, <a href="https://www.youtube.com" target="blank_" class="link-underline-info text-info">www.youtube.com</a> a <a href="https://easyupload.io" target="blank_" class="link-underline-info text-info">easyupload.io</a>.</small>
	</div>

	<hr>
</div>

<div class="container">
	<form method="POST">
		<div class="row mb-3">
			<label for="enterReciever" class="col-lg-3 col-form-label text-light">Důvod reportu:</label>
			<div class="col-lg-9">
				<select name="reportReason" class="form-select text-light" style="background-color: #222f3e; border: 0;" onchange="reasonChanged(this)">
					<?php
						$sql2 = "SELECT id, title FROM reportreasons ORDER BY id ASC";
						$result2 = mysqli_query($conn, $sql2);

						if (mysqli_num_rows($result2) >= 1) {
							while ($row2 = mysqli_fetch_assoc($result2)) {
								echo('<option value="' . $row2['id'] . '" id="reportReason">' . $row2['title'] . '</option>');
							}
						}
					?>
				</select>
			</div>
		</div>

		<div class="row mb-3">
			<label for="enterTitle" id="reportTitle" class="col-lg-3 col-form-label text-light">Jméno hráče a prohřešek:</label>
			<div class="col-lg-9">
				<input type="text" name="title" class="form-control text-light" style="background-color: #222f3e; border: 0;" id="enterTitle">
			</div>
			<span class="text-end text-secondary" id="titleHint"><small><em><strong>Příklad:</strong> _AmbY_ / Nadávky v chatu</em></small></span>
		</div>

		<label for="editor" class="text-light">Obsah reportu:</label><br />
		<span class="text-secondary" id="subjectHint"><small><em>Popiš prohřešek hráče, případnou lokaci a důkazy.</em></small></span>
		<textarea name="text" id="editor"></textarea>

		<div class="mt-3 text-end">
			<button type="submit" name="submit" value="1" class="btn btn-primary buttonC buttonC-2"><i class="bi bi-send"></i> Odeslat report</button>
		</div>
	</form>

	<hr>
</div>

<?php
	srcBuildFooter();
	// Pro potřeby funkčnosti header('Location: ../')
	ob_end_flush();
?>