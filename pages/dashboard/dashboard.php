<?php
	// Pro potřeby funkčnosti header('Location: ../')
	ob_start();
	// Pro potřeby autentizace
	session_start();

	define('FileOpenedThroughRequire', TRUE);

	require('../../core/src/secret/headerAndFooter.php');
	require('../../core/src/secret/databaseConnect.php');

	srcBuildHeader("Uživatelský panel");

	// Zobrazit stránku jen, pokud je přihlášen
	if (!isset($_SESSION['username'])) {
		header("Location: login.php");
		exit();
	}
?>

	<!-- Dashboard page style -->
	<link href="../../core/src/styles.css" rel="stylesheet">
</head>

<body class="customBody">

<?php
	// Header s informacemi o uživateli a možnost odhlášení
	require('./src/dashboardHeader.php');
	buildHeader('./logout.php', false, '../../index.php');
?>

<div class="container">
	<div class="row">
		<div class="col-1 col-lg-4"></div>
		<div class="col-10 col-lg-4">
			<div class="text-center mb-2">
				<a href="../../index.php">
					<img src="../../core/img/bigLogo.png" class="img-fluid" style="max-height: 75%; max-width: 80%;">
				</a>
			</div>
		</div>
		<div class="col-1 col-lg-4"></div>
	</div>
</div>

<div class="container" style="font-family: 'Roboto', sans-serif;">
	<!-- Nadpis -->
	<div class="text-center text-light" style="font-weight: 700;">
		<span class="fs-1">Uživatelský panel</span>
	</div>

	<hr>

	<!-- Informace o uživateli -->
	<div class="position-lg-relative" style="background-color: #0d1129;">
		<div class="row">
			<div class="col-12 col-lg-4 text-center">
				<img class="pt-4 pb-4" src="https://visage.surgeplay.com/full/160/<?php echo(username_to_uuid(validate($_SESSION['username']))) ?>">
			</div>
			<div class="col-12 col-lg-8 text-light">
				<div class="pt-4">
					<span class="fs-4" style="font-weight: 500;">Vítej <?php echo(validate($_SESSION['username'])) ?></span><br />

					<ul class="text-secondary">
						<li><small><strong>Hodnost:</strong> <span style="color: <?php echo(permissionToRankcolor($_SESSION['permissions'])) ?>"><?php echo(permissionToRankname($_SESSION['permissions'])) ?></span></small></li>
						<li><small><strong>Hráčské UUID:</strong> <?php echo(username_to_uuid(validate($_SESSION['username']))) ?></small></li>
						<li><small><strong>ID uživatele:</strong> #<?php echo($_SESSION['id']) ?></small></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<hr>

	<!-- Administrace -->
	<div class="text-light" style="font-weight: 500;">
		<span class="fs-3"><i class="bi bi-person-lock"></i> Administrace</span>
	</div>

	<div class="text-center pt-4 pb-4" style="background-color: #0d1129;">
		<div class="row">
			<div class="col-12 col-lg-6">
				<a href="./pages/writeArticle.php" class="btn btn-primary buttonC buttonC-2"><i class="bi bi-pencil"></i> Napsat nový článek</a>
			</div>
			<div class="col-12 col-lg-6">
				<a href="./pages/administerArticles.php" class="btn btn-primary buttonC buttonC-4"><i class="bi bi-pencil-square"></i> Správa článků</a>
			</div>
		</div>
	</div>

	<hr>

	<!-- Reporty -->
	<div class="text-light" style="font-weight: 500;">
		<span class="fs-3"><i class="bi bi-flag"></i> Reporty</span>
	</div>

	<div class="pt-4 pb-4" style="background-color: #0d1129;">
		<?php
			if ($_SESSION['permissions'] < 4) {
				echo('
				<!-- Napsat nový report -->
				<div class="text-center d-lg-flex flex-lg-row-reverse mb-4">
					<a href="./pages/writeReport.php" class="btn btn-primary buttonC buttonC-2 me-lg-4"><i class="bi bi-pencil"></i> Nový report</a>
				</div>

				<!-- Seznam reportů, které zaslal uživatel -->
				<div class="ms-4 me-4">
					<hr>
				');
				$userId = $_SESSION['id'];
				$sql2 = "SELECT r.id as id, u.username as author, r.dateCreated as dateCreated, r.subject as rSubject, r.solved as solved, r.solvedBy as solvedBy FROM reports r, users u WHERE u.id = r.author AND r.author = '$userId' ORDER BY id DESC LIMIT 5";
			} else {
				echo('
				<!-- Seznam reportů zaslaných uživateli -->
				<div class="ms-4 me-4">
				');
				$userId = $_SESSION['id'];
				$sql2 = "SELECT r.id as id, u.username as author, r.dateCreated as dateCreated, r.subject as rSubject, r.solved as solved, r.solvedBy as solvedBy FROM reports r, users u WHERE u.id = r.author AND r.solved IS NULL ORDER BY id DESC LIMIT 5";
			}
		?>
			<?php
				$result2 = mysqli_query($conn, $sql2);

				if (mysqli_num_rows($result2) >= 1) {
					while ($row = mysqli_fetch_assoc($result2)) {

						// Zobrazení ikon podle toho, jestli byl report vyřešen
						if ($row['solved'] != NULL) {
							$solveIcon = '<span class="badge text-bg-success"><i class="bi bi-check-circle"></i> Vyřešeno</span>';
						} else {
							if ($_SESSION['permissions'] < 4) {
								$solveIcon = '<span class="badge text-bg-light"><i class="bi bi-headset"></i> V řešení</span>';
							} else {
								$solveIcon = '<span class="badge text-bg-danger"><i class="bi bi-headset"></i> Vyžaduje vyřešení</span>';
							}
						}

						echo('
						<div class="row mt-2 mt-lg-1">
							<div class="card" style="background: linear-gradient(#141e30, #243b55); border: 0; color: white;">
								<div class="row pt-2 pb-2">
									<div class="col-12 col-lg-2 text-center text-lg-start text-truncate">
										' . $solveIcon . '
									</div>
									<div class="col-12 col-lg-2 text-center text-lg-start text-muted text-truncate pt-2 pt-lg-0" style="color: #6c757d!important;">
										<em>' . $row['dateCreated'] . '</em>
									</div>
									<div class="col-12 col-lg-4 text-center text-lg-start text-truncate pt-2 pt-lg-0">
										' . $row['rSubject'] . '
									</div>
									<div class="col-12 col-lg-2 text-center text-muted text-truncate pt-2 pt-lg-0" style="color: #6c757d!important;">
										<img src="http://cravatar.eu/avatar/' . $row['author'] . '/24.png" class="rounded-2 me-1"> ' . $row['author'] . '
									</div>
									<div class="col-12 col-lg-2 text-center text-lg-end pt-2 pt-lg-0">
										<a class="btn btn-sm w-75 w-lg-100 text-light buttonC buttonC-1" role="button" href="./pages/viewReport.php?id=' . $row['id'] . '">
											<span style="opacity: 80%;">
												<small><i class="bi bi-book"></i> Zobrazit report</small>
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
									<i class="bi bi-envelope-x"></i> Nejsou zde žádné reporty..
								</div>
							</div>
					</div>');
				}
			?>

			<!-- Zobrazit všechny reporty -->
			<div class="text-center mt-2">
				<a href="./pages/viewAllReports.php" class="btn btn-sm btn-primary buttonC buttonC-5 me-lg-4"><i class="bi bi-list-columns-reverse"></i> Zobrazit všechny odeslané reporty</a>
			</div>
		</div>

	</div>

	<hr>

	<!-- Soukromé zprávy -->
	<div class="text-light" style="font-weight: 500;">
		<span class="fs-3"><i class="bi bi-envelope"></i> Soukromé zprávy</span>
	</div>

	<div class="pt-4 pb-4" style="background-color: #0d1129;">
		<!-- Napsat novou zprávu -->
		<div class="text-center d-lg-flex flex-lg-row-reverse mb-4">
			<a href="./pages/writePm.php" class="btn btn-primary buttonC buttonC-2 me-lg-4"><i class="bi bi-pencil"></i> Nová soukromá zpráva</a>
		</div>

		<!-- Seznam zpráv -->
		<div class="ms-4 me-4">
			<hr>

			<!-- Zprávy zaslané uživatli -->
			<div class="text-light" style="font-weight: 500;">
				<span class="fs-5"><i class="bi bi-envelope-at"></i> Přijaté zprávy</span>
			</div>

			<?php
				$userId = $_SESSION['id'];
				$sql = "SELECT m.msgId as msgId, m.title as title, m.timeCreated as timeCreated, u.username as author, m.msgReaded as msgReaded FROM privatemessages m, users u WHERE u.id = m.fromUser AND m.toUser = '$userId' ORDER BY msgId DESC LIMIT 3";
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
										<a class="btn btn-sm w-75 w-lg-100 text-light buttonC buttonC-1" role="button" href="./pages/viewPm.php?id=' . $row['msgId'] . '">
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
			?>

			<!-- Zobrazit všechny přijaté zprávy -->
			<div class="text-center mt-2">
				<a href="./pages/viewAllPms.php?show=1" class="btn btn-sm btn-primary buttonC buttonC-5 me-lg-4"><i class="bi bi-list-columns-reverse"></i> Zobrazit všechny přijaté zprávy</a>
			</div>

			<hr>

			<!-- Zprávy vytvořené uživatelem -->
			<div class="text-light" style="font-weight: 500;">
				<span class="fs-5"><i class="bi bi-envelope-open"></i> Odeslané zprávy</span>
			</div>

			<?php
				$userId = $_SESSION['id'];
				$sql = "SELECT m.msgId as msgId, m.title as title, m.timeCreated as timeCreated, u.username as reciever, m.msgReaded as msgReaded FROM privatemessages m, users u WHERE u.id = m.toUser AND m.fromUser = '$userId' ORDER BY msgId DESC LIMIT 3";
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
										<img src="http://cravatar.eu/avatar/' . $row['reciever'] . '/24.png" class="rounded-2 me-1"> ' . $row['reciever'] . '
									</div>
									<div class="col-12 col-lg-2 text-center text-lg-end pt-2 pt-lg-0">
										<a class="btn btn-sm w-75 w-lg-100 text-light buttonC buttonC-1" role="button" href="./pages/viewPm.php?id=' . $row['msgId'] . '">
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
									<i class="bi bi-envelope-x"></i> Nemáš žádné odeslané zprávy..
								</div>
							</div>
					</div>');
				}
			?>

			<!-- Zobrazit všechny odeslané zprávy -->
			<div class="text-center mt-2">
				<a href="./pages/viewAllPms.php?show=2" class="btn btn-sm btn-primary buttonC buttonC-5 me-lg-4"><i class="bi bi-list-columns-reverse"></i> Zobrazit všechny odeslané zprávy</a>
			</div>
		</div>

		<div class="row ps-4 pe-4">
			<div class="col-2">
				
			</div>
		</div>
	</div>

	<hr>
</div>

<?php
	srcBuildFooter();
	// Pro potřeby funkčnosti header('Location: ../')
	ob_end_flush();
?>