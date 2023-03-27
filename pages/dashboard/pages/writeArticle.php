<?php
	// Pro potřeby funkčnosti header('Location: ../')
	ob_start();
	// Pro potřeby autentizace
	session_start();

	define('FileOpenedThroughRequire', TRUE);

	require('../../../core/src/secret/headerAndFooter.php');
	require('../../../core/src/secret/databaseConnect.php');

	srcBuildHeader("Nový článek");

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

	<!-- Wysiwyg editor -->
	<script src="https://cdn.tiny.cloud/1/vebjvn78l37yjzk6fvqwpxurzv10jo2ya0zo7ws9rlheice9/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
	<script>
		tinymce.init({
			selector: 'textarea#editorShort',
			skin: 'bootstrap',
			plugins: 'lists, link, image',
			toolbar: 'fontsize forecolor | bold italic underline strikethrough bullist numlist | alignleft aligncenter alignright | link image | subscript superscript | undo redo selectall removeformat',
			color_default_foreground: 'white',
			font_size_formats: '8pt 10pt 12pt 14pt 18pt',
			menubar: false,
			skin: 'oxide-dark',
  			content_css: 'dark'
		});

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
		html: "Článek s názvem <b>" + postTitle + "</b> byl přidán",
		icon: "success",
		confirmButtonColor: "#198754",
		confirmButtonText: "Otevřít hlavní stranu"
	}).then((result) => {
		if (result.isConfirmed) {
			window.location.href = "../../../index.php";
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
		if (!($_SESSION['permissions'] >= 8)) {
			exit();
		} else {
			if ($_POST['title'] != "" && $_POST['text'] != "") {
				$title = validate($_POST['title']);
				$shortText = $_POST['shortText'];
				$text = $_POST['text'];
				$author = $_SESSION['id'];

				$sql = "INSERT INTO news (title, shortArticleText, articleText, author) VALUES ('$title', '$shortText', '$text', '$author')";
				$result = mysqli_query($conn, $sql);
				unset($_POST['submit']);
				echo('<script>postSuccess("'. $title . '")</script>');
			} else {
				echo('<script>postError("Předmět a/nebo obsah článku nebyl zadán");</script>');
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
		<span class="fs-1">Tvorba nového článku</span>
	</div>

	<hr>

	<div class="mb-2">
		<small class="text-light"><i class="bi bi-info-circle text-info"></i> V nadpisu jsou povoleny jen alfanumerické znaky - všechny ostatní se zobrazí přesně tak, jak jste je napsali. Žádný HTML, BBCODE a jiné nebude zpracováno tak, jako v kódu.</small><br />
		<small class="text-light"><i class="bi bi-info-circle text-info"></i> Krátký náhled článku se zobrazuje na hlavní stránce - limitujte jej proto na jeden odstavec.</small><br />
		<small class="text-light"><i class="bi bi-exclamation-triangle text-danger"></i> Všechen text v obsahu nakonec nastavte na bílou barvu - jinak bude nečitelný na hlavní stránce.</small>
	</div>

	<hr>
</div>

<div class="container">
	<form method="POST">
		<div class="row mb-3">
			<label for="enterTitle" class="col-sm-2 col-form-label text-light">Nadpis článku:</label>
			<div class="col-sm-10">
				<input type="text" name="title" class="form-control text-light" style="background-color: #222f3e; border: 0;" id="enterTitle">
			</div>
		</div>

		<label for="editorShort" class="col-sm-2 col-form-label text-light">Krátký náhled článku:</label>
		<textarea name="shortText" id="editorShort"></textarea>

		<label for="editor" class="col-sm-2 col-form-label text-light mt-3">Celý článek:</label>
		<textarea name="text" id="editor"></textarea>

		<div class="mt-3 text-end">
			<button type="submit" name="submit" value="1" class="btn btn-primary buttonC buttonC-2"><i class="bi bi-send"></i> Přidat článek</button>
		</div>
	</form>

	<hr>
</div>

<?php
	srcBuildFooter();
	// Pro potřeby funkčnosti header('Location: ../')
	ob_end_flush();
?>