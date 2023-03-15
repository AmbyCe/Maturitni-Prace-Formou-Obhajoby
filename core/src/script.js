function copyIp() {
	Swal.fire(
		'IP zkopírována!', 
		'Vlož ji ve hře použitím klávesové zkratky <code>Ctrl+V</code>', 
		'success'
	)
	navigator.clipboard.writeText("Play.UniverseMC.cz")
}