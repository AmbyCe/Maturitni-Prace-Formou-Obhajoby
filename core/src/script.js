function copyIp() {
	Swal.fire(
		'IP zkopírována!', 
		'Vlož ji ve hře použitím klávesové zkratky <code>Ctrl+V</code>', 
		'success'
	);

	var ipServeru = "Play.UniverseMC.cz";
	try {
		navigator.clipboard.writeText(ipServeru);
		console.log('[SYS] IP adresa serveru zkopírována.');
	} catch (err) {
		console.error('[SYS] Při kopírování IP adresy nastala chyba: ', err);
	}
}