<?php
	// Pro potřeby funkčnosti header('Location: ../')
	ob_start();
	// Pro potřeby autentizace
	session_start();

	define('FileOpenedThroughRequire', TRUE);

	require('../../core/src/secret/headerAndFooter.php');
	require('../../core/src/secret/databaseConnect.php');

	srcBuildHeader("Registrace");
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
		title: "Úspěšná registrace!",
		text: "Nyní můžete přejít do uživatelského panelu",
		icon: "success",
		confirmButtonColor: "#198754",
		confirmButtonText: "Otevřít přihlášení"
	}).then((result) => {
		if (result.isConfirmed) {
			window.location.href = "./dashboard.php";
		} else if (result.isDismissed) {
			window.location.href = "./dashboard.php";
		}
	})
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
		$pass2 = validate($_POST['passwordAgain']);
		$pass = hash('SHA256', $pass);
		$pass2 = hash('SHA256', $pass2);

		if ($pass != $pass2) {
			echo('<script>loginError("Zadaná hesla se neshodují");</script>');
			exit();
		}

		// Ověření, zda už účet s tím jménem neexistuje
		$sql = "SELECT * FROM users WHERE username='$uname'";
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) >= 1) {
			echo('<script>loginError("Účet s tímto jménem již existuje");</script>');
			exit();
		}

		// Uložit data do databáze
		$sql2 = "INSERT INTO users (username, password) VALUES ('$uname', '$pass');";
		$result2 = mysqli_query($conn, $sql2);

		// Získat data z databáze
		$sql3 = "SELECT * FROM users WHERE username='$uname' AND password='$pass'";
		$result3 = mysqli_query($conn, $sql3);
		if (mysqli_num_rows($result3) === 1) {
			$row3 = mysqli_fetch_assoc($result3);
			if ($row3['username'] === $uname && $row3['password'] === $pass) {
				$_SESSION['username'] = $row3['username'];
				$_SESSION['id'] = $row3['id'];
				$_SESSION['permissions'] = $row3['permissions'];

				$_POST['username'] = "";
				$_POST['password'] = "";

				echo('<script>loginSuccess()</script>');
			} else {
				echo('<script>loginError("Nastala chyba, kontaktujte administrátora (kód 1)");</script>');
			}

		} else {
			echo('<script>loginError("Nastala chyba, kontaktujte administrátora (kód 2)");</script>');
		}
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

					<label for="passwordInput2" class="form-label text-light">Heslo znovu</label>
					<input type="password" class="form-control mb-4" id="passwordInput2" name="passwordAgain" style="background: linear-gradient(#141e30, #243b55); border: 0; color: #FFF;">

					<div class="text-center">
						<button type="submit" name="submit" value="1" class="btn btn-primary buttonC buttonC-2"><i class="bi bi-box-arrow-in-right"></i> Zaregistrovat se</button>
					</div>
				</form>
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