$(document).ready(function(){
	
app.alertaAnimado('#btnObterAta', 'click', '#alertaDownloadAta',4000);

function toggleBtnObterCertificados(){
	if($("input:checkbox:checked").length > 0) {
        $('#btnObterCertificados').removeClass('hide').addClass('animated fadeInDown').delay(900).queue(function(){ $(this).removeClass('animated'); });
    } else {
		$('#btnObterCertificados').addClass('hide');
	}
}

toggleBtnObterCertificados();
$("input[type=checkbox]").change(function() {
    toggleBtnObterCertificados();
});

app.hideProgress('loader');
	
TextareaParticipantes = $('#listaParticipantes').val();
if(TextareaParticipantes !== ''){	
	var ArrParticipantes = JSON.parse(TextareaParticipantes);
	var totalParticipantes = ArrParticipantes.length;
} else {
	console.log('Textarea dos participantes está vazio');
}	

//converte array do textarea para integer
var ArrParticipantesInt = ArrParticipantes.map(function (x) { 
    return parseInt(x, 10); 
});

console.log(ArrParticipantesInt);
	
$('#btnObterCertificados').click(function(){
	
	$('#btnObterCertificados .icon-spin').removeClass('hidden');
	
	if($('#cbkImprimir').is(':checked')){
		gerarPDF();
		setTimeout(function(){ gerarZIP(); },500);
	}
	if($('#cbkPDF').is(':checked')){
		//alert('PDF');
		
		// $.each(ArrParticipantesInt,function(idParticipante){
			// geraPDFCertificadoParticipante(idParticipante);
		// });
	}
	if($('#cbkEmail').is(':checked')){
		alert('Email');
	}
});

function gerarPDF(){
	
	var qtd=1;
		
	$.each( ArrParticipantesInt , function(key, idParticipante) {
			console.log(idParticipante);
			
		geraCertificado = $.ajax(base+'api/gerarcertificados/palestra/'+app.getUrlParameter('idPalestra')+'?participantes=['+idParticipante+']');

		geraCertificado.complete(function(response){
			
			if(qtd === 1){
				$('#progresso').removeClass('hidden').addClass('animated fadeInUp');
				$('#progresso .acao').text('Preparando certificados dos participantes');
				$('.progress-bar').css('width',0).attr('aria-valuenow', 0).text('0%');
			}
			
			console.log('Deu certo',idParticipante);
			
			progresso = qtd/totalParticipantes*100;
			 $('.progress-bar').css('width', progresso+'%').attr('aria-valuenow', progresso).text(progresso.toFixed(1)+'%'); 
			
			if(qtd===totalParticipantes){
				setTimeout(function(){
					$('#progresso').addClass('animated fadeOutUp').delay(450).queue(function(){ $(this).removeClass('animated fadeOutUp fadeInUp').addClass('hidden'); });
					
					$('#progresso').addClass('hidden');
					
					$('#btnObterCertificados .icon-spin').addClass('hidden');
						
				}, 450);
				
				//CHAMA FUNÇÃO PARA EMITIR O CERTIFICADOS DOS PALESTRANTES
			}
			
			qtd++;
		
			console.log('RESPOSTA',response,idParticipante,'Progresso',progresso);
		});			

	});				
}		


function gerarZIP(){
	

		document.location.href = base+'api/compactarcertificados/palestra/'+app.getUrlParameter('idPalestra')+'?participantes='+JSON.stringify(ArrParticipantesInt); //já coloca [] no inicio e fim

		
}

});