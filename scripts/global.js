//Habilita o onpopstate para botoes voltar e avancar funcionarem
window.onpopstate = function(e) {
	if (e.state) {
		window.location.reload();
	}
};

//DIMINUI O TOPO AO ROLAR
$(window).scroll(function() {
	if($(window).width() > 767){
		 var a=$(window).scrollTop();
		 if(a > 100){
			$('.navbar').addClass('scroll');
		 } else {
			$('.navbar').removeClass('scroll');
		 }
	}
});	