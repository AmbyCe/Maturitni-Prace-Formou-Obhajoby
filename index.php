<?php
	// Pro potřeby autentizace
	session_start();

	define('FileOpenedThroughRequire', TRUE);

	require('./core/src/secret/headerAndFooter.php');
	require('./core/src/secret/databaseConnect.php');

	srcBuildHeader("Hlavní strana");
?>

	<!-- Main page style -->
	<link href="./core/src/styles.css" rel="stylesheet">
	<script src="./core/src/script.js"></script>
</head>

<noscript>
	<h1 class="text-center">Nemáš povolený JavaScript na našich stránkách - stránky nebudou fungovat, tak jak by měly.<br />Povol jej a vše bude fungovat tak, jak má ;) <a href="https://www.enable-javascript.com/">www.enable-javascript.com</a></h1>
</noscript>

<body class="customBody" style="font-family: 'Roboto', sans-serif;">

<?php
	// Header s informacemi o uživateli a možnost odhlášení
	require('./pages/dashboard/src/dashboardHeader.php');
	buildHeader('./pages/dashboard/logout.php', true, './pages/dashboard/dashboard.php');
?>

	<div class="container">

		<!-- Top logo with animation -->
		<div class="topBackgroundImage">
			<div class="zoom-in-out-box text-center">
				<img src="./core/img/bigLogo.png" style="max-width: 100%;">
			</div>
		</div>

		<hr>

		<!-- IP to copy -->
		<div class="text-center fs-1 ipText" style="font-family: 'Roboto', sans-serif; font-weight: 900;">
			Play.UniverseMC.cz
		</div>
		<div class="text-center pb-3 d-none d-lg-block">
			<button type="button" class="btn btn-primary btn-sm" onclick="copyIp()">Zkopírovat IP <i class="bi bi-arrow-right-square"></i></button>
		</div>

		<hr>

	</div>

	<div class="container">

		<!-- Right panel (fullwidth for mobile view) -->
		<div class="d-block d-lg-none">
			<div class="row">
				<div class="col-6">
					<a class="btn btn-lg w-100 text-light text-opacity-75 buttonC buttonC-1" role="button" href="./pages/dashboard/dashboard.php">
						Uživatelský panel
					</a>

					<a class="btn btn-lg w-100 text-light text-opacity-75 buttonC buttonC-6 mt-2" role="button" href="./pages/navody/">
						Nápověda - wiki
					</a>

					<a class="btn btn-lg w-100 text-light text-opacity-75 buttonC buttonC-3 mt-2" role="button" href="">
						Nahlásit hráče
					</a>
				</div>

				<div class="col-6">
					<a class="btn btn-lg w-100 text-light text-opacity-75 buttonC buttonC-2" role="button" href="">
						Žádost o unban
					</a>

					<a class="btn btn-lg w-100 text-light text-opacity-75 buttonC buttonC-5 mt-2" role="button" href="">
						Žádost o práva
					</a>

					<a class="btn btn-lg w-100 text-light text-opacity-75 buttonC buttonC-4 mt-2" role="button" href="">
						Aktivovat VIP
					</a>
				</div>
			</div>

			<hr>
		</div>

		<div class="row">
			<!-- News -->
			<div class="col-12 col-lg-9 mb-2 ps-2">

				<?php
					$sql = "SELECT id, title, timeCreated, shortArticleText, author FROM news ORDER BY id DESC LIMIT 5";
					$result = mysqli_query($conn, $sql);

					if (mysqli_num_rows($result) >= 1) {
						while ($row = mysqli_fetch_assoc($result)) {
							echo('
							<div class="row mb-3 ms-1 me-1 me-lg-0">
								<div class="card" style="background: linear-gradient(#141e30, #243b55); border: 0;">
									<div class="card-body">
										<h4 class="card-title text-light">' . $row['title'] . '</h4>
										<h6 class="card-subtitle text-muted small" style="color: #6c757d!important;">' . $row['timeCreated'] . '</h6>
			
										<hr class="text-light">
			
										<div style="max-width: 100%;">
											<p class="card-text text-light text-opacity-50 text text-truncate">' . $row['shortArticleText'] . '</p>
										</div>
			
										<hr class="text-light">
			
										<div class="row">
											<div class="col-6 my-auto">
												<a href="./pages/fullArticle.php?id=' . $row['id'] . '" type="button" class="btn btn-primary btn-sm text-nowrap"><i class="bi bi-file-text"></i> <span class="d-none d-lg-inline-block">Přečíst c</span><span class="d-inline-block d-lg-none">C</span>elý článek</a>
											</div>
											<div class="col-6 text-end text-light text-opacity-50 my-auto">
												<img src="http://cravatar.eu/avatar/' . $row['author'] . '/24.png" class="rounded-2 w-auto h-auto me-1"> <span class="d-none d-lg-inline-block">' . $row['author'] . '</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							');
						}
					}
				?>

			</div>

			<!-- Right panel (only on desktop) -->
			<div class="d-none d-lg-block col-lg-3">
				<a class="btn btn-lg w-100 text-light text-opacity-75 buttonC buttonC-1" role="button" href="./pages/dashboard/dashboard.php">
					<i class="bi bi-speedometer2 d-none d-lg-inline-block"></i> Uživatelský panel
				</a>

				<a class="btn btn-lg w-100 text-light text-opacity-75 buttonC buttonC-6 mt-2" role="button" href="./pages/navody/">
					<i class="bi bi-body-text d-none d-lg-inline-block"></i> Nápověda - wiki
				</a>

				<button type="button" class="btn btn-lg w-100 text-light text-opacity-75 buttonC buttonC-3 mt-lg-2">
					<i class="bi bi-flag d-none d-lg-inline-block"></i> Nahlásit hráče
				</button>

				<button type="button" class="btn btn-lg w-100 text-light text-opacity-75 buttonC buttonC-2 mt-2">
					<i class="bi bi-x-circle d-none d-lg-inline-block"></i> Žádost o unban
				</button>

				<button type="button" class="btn btn-lg w-100 text-light text-opacity-75 buttonC buttonC-5 mt-2">
					<i class="bi bi-person-check d-none d-lg-inline-block"></i> Žádost o práva
				</button>

				<button type="button" class="btn btn-lg w-100 text-light text-opacity-75 buttonC buttonC-4 mt-2">
					<i class="bi bi-cart4 d-none d-lg-inline-block"></i> Aktivovat VIP
				</button>
			</div>

		</div>

		<hr>
	</div>
<?php
	srcBuildFooter();
?>