<?php
	// Pro potřeby funkčnosti header('Location: ../')
	ob_start();
	// Pro potřeby autentizace
	session_start();

	define('FileOpenedThroughRequire', TRUE);

	require('../../core/src/secret/headerAndFooter.php');
	require('../../core/src/secret/databaseConnect.php');

	srcBuildHeader("Přihlášení");
?>

	<!-- Login page style -->
	<link href="../../core/src/styles.css" rel="stylesheet">
</head>

<script>
function loginError(text) {
	Swal.fire(
		"Chyba!", 
		text, 
		"error"
	)
}

function loginSuccess() {
	Swal.fire({
		title: "Úspěšné přihlášení!",
		text: "Nyní můžeš přejít do uživatelského panelu",
		icon: "success",
		confirmButtonColor: "#198754",
		confirmButtonText: "Otevřít uživatelský panel"
	}).then((result) => {
		if (result.isConfirmed) {
			window.location.href = "./dashboard.php";
		} else if (result.isDismissed) {
			window.location.href = "./dashboard.php";
		}
	})
}

function howToLogin() {
	Swal.fire(
		'Jak se přihlásit?', 
		'K přihlášení využij své herní údaje<br /><br /><br />' +
		'<div class="row" style="max-width: 100%;">' +
		'<div class="col-6"><small><strong>Uživatelské jméno</strong><br />Tvůj nick, který používáš na serveru</small></div>' +
		'<div class="col-6"><small><strong>Heslo</strong><br />Tvé heslo, které bylo zadáno při registraci na serveru</small></div>' +
		'</div><br /><br /><br />' +
		'<small><i class="bi bi-info-circle text-primary"></i> Před přihlášením zde na webu je nutné být zaregistrován na našem herním serveru</small>',
		'question'
	);
}
</script>

<body class="customBody">

<?php
// Pokud je již přihlášen přesměrovat
if (isset($_SESSION['username'])) {
	header('Location: dashboard.php');
	exit();
}

// Oveření přihlášení
if (isset($_POST['submit'])) {
	if ($_POST['username'] != "" && $_POST['password'] != "") {
		function validate($data){
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}

		$uname = validate($_POST['username']);
		$pass = validate($_POST['password']);
		$pass = hash('SHA256', $pass);

		$sql = "SELECT * FROM users WHERE username='$uname' AND password='$pass'";
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) === 1) {
			$row = mysqli_fetch_assoc($result);
			if ($row['username'] === $uname && $row['password'] === $pass) {
				$_SESSION['username'] = $row['username'];
				$_SESSION['id'] = $row['id'];
				$_SESSION['permissions'] = $row['permissions'];

				$_POST['username'] = "";
				$_POST['password'] = "";

				echo('<script>loginSuccess()</script>');
			} else {
				echo('<script>loginError("Nesprávné přihlašovací údaje");</script>');
			}

		} else {
			echo('<script>loginError("Nesprávné jméno a/nebo heslo");</script>');
		}

	} else {
		echo('<script>loginError("Nesprávné jméno a/nebo heslo");</script>');
	}
}
?>

	<div class="container">
		<div class="row">
			<div class="col-1 col-lg-4"></div>
			<div class="col-10 col-lg-4">
				<div class="text-center mb-2">
					<img src="../../core/img/bigLogo.png" class="img-fluid" style="max-height: 75%; max-width: 80%;">
				</div>

				<form method="POST" action="" class="ms-lg-3 me-lg-3">
					<label for="usernameInput" class="form-label text-light">Uživatelské jméno</label>
					<input type="text" class="form-control mb-4" id="usernameInput" name="username" style="background: linear-gradient(#141e30, #243b55); border: 0; color: #FFF;">

					<label for="passwordInput" class="form-label text-light">Heslo</label>
					<input type="password" class="form-control mb-4" id="passwordInput" name="password" style="background: linear-gradient(#141e30, #243b55); border: 0; color: #FFF;">

					<div class="text-center">
						<button type="submit" name="submit" value="1" class="btn btn-primary buttonC buttonC-2"><i class="bi bi-box-arrow-in-right"></i> Přihlásit se</button>
					</div>
				</form>

				<div class="text-center">
					<button class="btn text-secondary" onclick="howToLogin()"><i class="bi bi-question-circle"></i> Jak se přihlásit?</button>
				</div>
			</div>
			<div class="col-1 col-lg-4"></div>
		</div>

		<hr>
	</div>

<?php
	srcBuildFooter();
	// Pro potřeby funkčnosti header('Location: ../')
	ob_end_flush();
?>