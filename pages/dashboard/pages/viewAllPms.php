<?php
	// Pro potřeby autentizace
	session_start();

	define('FileOpenedThroughRequire', TRUE);

	require('../../../core/src/secret/headerAndFooter.php');
	require('../../../core/src/secret/databaseConnect.php');

	srcBuildHeader("Všechny soukromé zprávy");

	// Zobrazit stránku jen, pokud je přihlášen
	if (!isset($_SESSION['username'])) {
		header("Location: ../login.php");
		exit();
	}
?>

	<!-- Dashboard page style -->
	<link href="../../../core/src/styles.css" rel="stylesheet">
</head>

<body class="customBody" style="font-family: 'Roboto', sans-serif;">

<script>
function pageError(text) {
	Swal.fire(
		"Chyba!",
		text, 
		"error"
	)
}
</script>

<?php
	// Header s informacemi o uživateli a možnost odhlášení
	require('../src/dashboardHeader.php');
	buildHeader('../logout.php', true, '../dashboard.php');



	// Pokud je v GETu validní zpráva + načtení informací o ní
	if (!isset($_GET['show'])) {
		echo('<script>pageError("Nesprávné zadání požadavku");</script>');
		$thisPageTitle = "Nesprávné zadání požadavku";
	} else {
		if ($_GET['show'] == 1) {
			$thisPageTitle = "Všechny přijaté zprávy";
		} else {
			$thisPageTitle = "Všechny odeslané zprávy";
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

	<div class="container">
		<!-- Nadpis -->
		<div class="text-center text-light" style="font-weight: 700;">
			<span class="fs-1"><?php echo($thisPageTitle); ?></span>
		</div>

		<hr>

		<!-- Seznam zpráv -->
		<div class="pt-2 pb-2" style="background-color: #0d1129;">

			<div class="ms-4">

				<div class="row">
					<?php
						if (isset($_GET['show'])) {
							$userId = $_SESSION['id'];
							if ($_GET['show'] == 1) {
								$sql = "SELECT m.msgId as msgId, m.title as title, m.timeCreated as timeCreated, u.username as author, m.msgReaded as msgReaded FROM privatemessages m, users u WHERE u.id = m.fromUser AND m.toUser = '$userId' ORDER BY msgId DESC";
							} else {
								$sql = "SELECT m.msgId as msgId, m.title as title, m.timeCreated as timeCreated, u.username as author, m.msgReaded as msgReaded FROM privatemessages m, users u WHERE u.id = m.toUser AND m.fromUser = '$userId' ORDER BY msgId DESC";
							}
							$result = mysqli_query($conn, $sql);

							if (mysqli_num_rows($result) >= 1) {
								while ($row = mysqli_fetch_assoc($result)) {

									// Zobrazení ikon podle toho, jestli byla zpráva přečtena
									if ($row['msgReaded'] != NULL) {
										$readIcon = '<span class="badge text-bg-success"><i class="bi bi-check-circle"></i> Přečteno</span>';
									} else {
										$readIcon = '<span class="badge text-bg-danger"><i class="bi bi-x-circle"></i> Nepřečteno</span>';
									}

									echo('
									<div class="row mt-2 mt-lg-1">
										<div class="card" style="background: linear-gradient(#141e30, #243b55); border: 0; color: white;">
											<div class="row pt-2 pb-2">
												<div class="col-12 col-lg-2 text-center text-lg-start text-truncate">
													' . $readIcon . '
												</div>
												<div class="col-12 col-lg-2 text-center text-lg-start text-muted text-truncate pt-2 pt-lg-0" style="color: #6c757d!important;">
													<em>' . $row['timeCreated'] . '</em>
												</div>
												<div class="col-12 col-lg-4 text-center text-lg-start text-truncate pt-2 pt-lg-0">
													' . $row['title'] . '
												</div>
												<div class="col-12 col-lg-2 text-center text-muted text-truncate pt-2 pt-lg-0" style="color: #6c757d!important;">
													<img src="http://cravatar.eu/avatar/' . $row['author'] . '/24.png" class="rounded-2 me-1"> ' . $row['author'] . '
												</div>
												<div class="col-12 col-lg-2 text-center text-lg-end pt-2 pt-lg-0">
													<a class="btn btn-sm w-75 w-lg-100 text-light buttonC buttonC-1" role="button" href="./viewPm.php?id=' . $row['msgId'] . '">
														<span style="opacity: 80%;">
															<small><i class="bi bi-book"></i> Zobrazit zprávu</small>
														</span>
													</a>
												</div>
											</div>
										</div>
									</div>
									');
								}
							} else {
								echo('
								<div class="row mt-2 mt-lg-1">
										<div class="card" style="background: linear-gradient(#141e30, #243b55); border: 0; color: white;">
											<div class="pt-2 pb-2 text-center">
												<i class="bi bi-envelope-x"></i> Nemáš žádné přijaté zprávy..
											</div>
										</div>
								</div>');
							}
						} else {
							echo('
								<div class="row mt-2 mt-lg-1">
										<div class="card" style="background: linear-gradient(#141e30, #243b55); border: 0; color: white;">
											<div class="pt-2 pb-2 text-center">
												<i class="bi bi-exclamation-triangle"></i> Nesprávné zadání požadavku..
											</div>
										</div>
								</div>');
						}
					?>
				</div>

			</div>
		</div>

		<!-- Zpět do uživatelského panelu -->
		<div class="mt-2 text-center text-lg-end">
			<a href="../pages/writePm.php" class="btn btn-primary buttonC buttonC-2 m-2 m-lg-0 me-lg-1"><i class="bi bi-pencil"></i> Nová soukromá zpráva</a>
			<a href="../dashboard.php" class="btn btn-primary buttonC buttonC-1"><i class="bi bi-speedometer2"></i> Zpět do uživatelského panelu</a>
		</div>

		<hr>
	</div>

<?php
	srcBuildFooter();
?>