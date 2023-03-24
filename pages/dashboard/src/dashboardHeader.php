<?php
	// Nedovolit přístup k souboru přímou cestou, jedině pokud na něj odkazuje jiný soubor
	if(!defined('FileOpenedThroughRequire')) {
		die('Přímý přístup k souborům webu není možný.');
	}



	// Získávání UUID z nicku hráče (resp. z toho co vrátí Mojang API)
	function username_to_uuid($username) {
		$profile = username_to_profile($username);
		if (is_array($profile) and isset($profile['id'])) {
			return $profile['id'];
		}
		return false;
	}

	// Získání UUID z nicku hráče pomocí Mojang API
	function username_to_profile($username) {
		if (is_valid_username($username)) {
			$json = file_get_contents('https://api.mojang.com/users/profiles/minecraft/' . $username);
			if (!empty($json)) {
				$data = json_decode($json, true);
				if (is_array($data) and !empty($data)) {
					return $data;
				}
			}
		}
		return false;
	}

	// Převedení zadaného jména hráče na jméno akceptované Mojang API
	function is_valid_username($string) {
		return is_string($string) and strlen($string) >= 2 and strlen($string) <= 16 and ctype_alnum(str_replace('_', '', $string));
	}

	// Převedení čísla permisse na název
	function permissionToRankname($permission) {
		if ($permission == NULL) {
			return "Hráč";
		} else if ($permission == 1) {
			return "VIP";
		} else if ($permission == 2) {
			return "Legenda";
		} else if ($permission == 3) {
			return "Sponzor";
		} else if ($permission == 4) {
			return "Zkušební Builder";
		} else if ($permission == 5) {
			return "Zkušební Admin";
		} else if ($permission == 6) {
			return "Builder";
		} else if ($permission == 7) {
			return "Admin";
		} else if ($permission == 8) {
			return "Hlavní Builder";
		} else if ($permission == 9) {
			return "Hlavní Admin";
		} else if ($permission == 10) {
			return "Management";
		} else if ($permission == 100) {
			return "Majitel";
		}
	}

	// Převedení čísla permisse na barvu ranku
	function permissionToRankcolor($permission) {
		if ($permission == NULL) {
			return "#6c757d";
		} else if ($permission == 1) {
			return "#ffa939";
		} else if ($permission == 2) {
			return "#6afaff";
		} else if ($permission == 3) {
			return "#45e768";
		} else if ($permission == 4) {
			return "#bdbdbd";
		} else if ($permission == 5) {
			return "#bdbdbd";
		} else if ($permission == 6) {
			return "#30c67c";
		} else if ($permission == 7) {
			return "#ea5753";
		} else if ($permission == 8) {
			return "#ff930f";
		} else if ($permission == 9) {
			return "#ff930f";
		} else if ($permission == 10) {
			return "#bf55ec";
		} else if ($permission == 100) {
			return "#ff4646";
		}
	}

	// Převedení na charaktery - proti XSS
	function validate($data){
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}



	// Výstavba headeru, pokud je přihlášen
	function buildHeader($logoutUrl, $toUserPanel, $linkForBlueButton) {
		if (isset($_SESSION['username'])) {
			// Převedení na charaktery - proti XSS
			$usernameString = validate($_SESSION['username']);

			// Aby modré tlačítko odkazovalo na vhodnou stránku
			if ($toUserPanel == true) {
				$buttonText = "Přejít do uživatelského panelu";
			} else {
				$buttonText = "Přejít na hlavní stránku";
			}

			$outputString = '
				<div class="container">

					<!-- Logout flex -->
					<div class="d-lg-flex flex-lg-row-reverse text-center text-lg-none">
						<div class="mt-3 mb-3">
							<button class="btn btn-primary buttonC buttonC-1 me-2 mb-1 mb-lg-0" onclick=\'location.href = "' . $linkForBlueButton . '"\'><i class="bi bi-speedometer2"></i> ' . $buttonText . '</button>

							<button class="btn btn-primary buttonC buttonC-0 me-2 mb-1 mb-lg-0">
								<img src="http://cravatar.eu/helmavatar/' . $usernameString . '" class="me-1" style="max-height: 1em; cursor: none;">
								<span class="badge me-1" style="background-color: ' . permissionToRankcolor($_SESSION['permissions']) . '">' . permissionToRankname($_SESSION['permissions']) . '</span>
								<small class="align-baseline">' . $usernameString . '</small>
							</button>
							<button class="btn btn-primary buttonC buttonC-3" onclick=\'location.href = "' . $logoutUrl . '"\'><i class="bi bi-door-closed"></i> Odhlásit se</button>
						</div>
					</div>

				</div>
			';
			echo($outputString);
		}
	}

?>