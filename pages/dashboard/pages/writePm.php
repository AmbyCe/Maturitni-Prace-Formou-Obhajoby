<?php
	// Pro potřeby funkčnosti header('Location: ../')
	ob_start();
	// Pro potřeby autentizace
	session_start();

	define('FileOpenedThroughRequire', TRUE);

	require('../../../core/src/secret/headerAndFooter.php');
	require('../../../core/src/secret/databaseConnect.php');

	srcBuildHeader("Nová soukromá zpráva");

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
			toolbar: 'fontsize forecolor | bold italic underline strikethrough bullist numlist | alignleft aligncenter alignright | link image | subscript superscript | undo redo selectall removeformat',
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
		html: "Soukromá zpráva s předmětem <b>" + postTitle + "</b> byla odeslána",
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

<body class="customBody">

<?php
	// Header s informacemi o uživateli a možnost odhlášení
	require('../src/dashboardHeader.php');
	buildHeader('../logout.php', true, '../dashboard.php');




	// Pokud byl odeslán formulář s článkem
	if (isset($_POST['submit'])) {
		if ($_POST['title'] != "" && $_POST['text'] != "") {
			$author = $_SESSION['id'];
			$reciever = $_POST['reciever'];
			$title = validate($_POST['title']);
			$text = $_POST['text'];

			if (strlen($title) > 40) {
				echo('<script>postError("Předmět zprávy je příliš dlouhý - má: <strong>' . strlen($title) . ' znaků</strong>");</script>');
			} else {
				if (strlen($text) > 7500) {
					echo('<script>postError("Obsah zprávy je příliš dlouhý - má: <strong>' . strlen($text) . ' znaků</strong>");</script>');
				} else {
					$sql = "INSERT INTO privatemessages (fromUser, toUser, title, content) VALUES ('$author', '$reciever', '$title', '$text')";
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
		<span class="fs-1">Nová soukromá zpráva</span>
	</div>

	<hr>

	<div class="mb-2">
		<small class="text-light"><i class="bi bi-info-circle text-info"></i> V předmětu nelze použít HTML, BBCODE a jiné. Je taktéž limitován na <strong>40 znaků</strong>.</small><br />
		<small class="text-light"><i class="bi bi-info-circle text-info"></i> Obsah soukromé zprávy je limitován na <strong>7 500 znaků</strong>.</small>
	</div>

	<hr>
</div>

<div class="container">
	<form method="POST">
		<div class="row mb-3">
			<label for="enterReciever" class="col-lg-2 col-form-label text-light">Příjemce zprávy:</label>
			<div class="col-lg-10">
				<select name="reciever" class="form-select text-light" style="background-color: #222f3e; border: 0;">
					<?php
						$sql2 = "SELECT id, username FROM users ORDER BY id ASC";
						$result2 = mysqli_query($conn, $sql2);

						if (mysqli_num_rows($result2) >= 1) {
							while ($row2 = mysqli_fetch_assoc($result2)) {
								echo('<option value="' . $row2['id'] . '">' . $row2['username'] . '</option>');
							}
						}
					?>
				</select>
			</div>
		</div>

		<div class="row mb-3">
			<label for="enterTitle" class="col-lg-2 col-form-label text-light">Předmět zprávy:</label>
			<div class="col-lg-10">
				<input type="text" name="title" class="form-control text-light" style="background-color: #222f3e; border: 0;" id="enterTitle">
			</div>
		</div>

		<label for="editor" class="text-light">Obsah soukromé zprávy:</label>
		<textarea name="text" id="editor"></textarea>

		<div class="mt-3 text-end">
			<button type="submit" name="submit" value="1" class="btn btn-primary buttonC buttonC-2"><i class="bi bi-send"></i> Odeslat zprávu</button>
		</div>
	</form>

	<hr>
</div>

<?php
	srcBuildFooter();
	// Pro potřeby funkčnosti header('Location: ../')
	ob_end_flush();
?>