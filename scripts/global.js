//Habilita o onpopstate para botoes voltar e avancar funcionarem
window.onpopstate = function(e) {
	if (e.state) {
		window.location.reload();
	}
};