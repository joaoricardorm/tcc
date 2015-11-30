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



palestrantesPalestra = $.getJSON(base+'api/palestrapalestrantes?idPalestra='+app.getUrlParameter('idPalestra'));

palestrantesPalestra.complete(function(td){
	
	todosPalestrantes = JSON.parse(td.responseText);
	
	var totalPalestrantes = todosPalestrantes.totalResults;
	var ArrPalestrantesInt = [];
	$.each( todosPalestrantes.rows, function(key, value) {	 
		 ArrPalestrantesInt.push(parseInt(value.idPalestrante));
	});	
	
	console.log('TUDO',ArrPalestrantesInt,totalPalestrantes);
	

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
	

//AÇÕES DO SISTEMA DE ACORDO COM AS OPÇÕES	
	$('#btnObterCertificados').click(function(){
		
		$('#btnObterCertificados .icon-spin').removeClass('hidden');
		
		if($('#cbkImprimir').is(':checked')){
			gerarPDFParticipantes();
			gerarPDFPalestrantes();	
			setTimeout(function(){  imprimirCertificados(); },500);
		}
		if($('#cbkPDF').is(':checked')){
			gerarPDFParticipantes();
			gerarPDFPalestrantes();		
			setTimeout(function(){  downloadCertificados(); },500);
		}
		if($('#cbkEmail').is(':checked')){
			alert('Email');
		}
	});

	function gerarPDFParticipantes(){
		
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

	function gerarPDFPalestrantes(){
		
		var qtd=1;
			
		$.each( ArrPalestrantesInt , function(key, idPalestrante) {
				console.log(idPalestrante);
				
			geraCertificado = $.ajax(base+'api/gerarcertificados/palestra/'+app.getUrlParameter('idPalestra')+'/palestrantes/'+idPalestrante+'?idPalestrante='+idPalestrante);
			
			console.log(base+'api/gerarcertificados/palestra/'+app.getUrlParameter('idPalestra')+'/palestrantes/'+idPalestrante+'?idPalestrante='+idPalestrante);

			geraCertificado.complete(function(response){
				
				if(qtd === 1){
					$('#progresso').removeClass('hidden').addClass('animated fadeInUp');
					$('#progresso .acao').text('Preparando certificados dos palestrantes');
					$('.progress-bar').css('width',0).attr('aria-valuenow', 0).text('0%');
				}
				
				console.log('Deu certo',idPalestrante);
				
				progresso = qtd/totalPalestrantes*100;
				 $('.progress-bar').css('width', progresso+'%').attr('aria-valuenow', progresso).text(progresso.toFixed(1)+'%'); 
				
				if(qtd===totalPalestrantes){
					setTimeout(function(){
						$('#progresso').addClass('animated fadeOutUp').delay(450).queue(function(){ $(this).removeClass('animated fadeOutUp fadeInUp').addClass('hidden'); });
						
						$('#progresso').addClass('hidden');
						
						$('#btnObterCertificados .icon-spin').addClass('hidden');
							
					}, 450);
					
					//CHAMA FUNÇÃO PARA EMITIR O CERTIFICADOS DOS PALESTRANTES
				}
				
				qtd++;
			
				console.log('RESPOSTA',response,idPalestrante,'Progresso',progresso);
			});			

		});				
	}		


	/*
	
	function gerarZIPParticipantes(){
		console.log('redi part');
		document.location.href = base+'api/compactarcertificados/palestra/'+app.getUrlParameter('idPalestra')+'?participantes='+JSON.stringify(ArrParticipantesInt); //já coloca [] no inicio e fim		
	}
	function gerarZIPPalestrantes(){
		console.log('redi pal');
		document.location.href = base+'api/compactarcertificados/palestra/'+app.getUrlParameter('idPalestra')+'/palestrantes?palestrantes='+JSON.stringify(ArrPalestrantesInt); //já coloca [] no inicio e fim		
	} */
	
	//COMPACTA E BAIXA OS CERTIFICADOS DE PALESTRANTES DE PARTICIPANTES
	function downloadCertificados(){
	  var files = [];
	  
	  var urlDownloadParticipantes = base+'api/compactarcertificados/palestra/'+app.getUrlParameter('idPalestra')+'?participantes='+JSON.stringify(ArrParticipantesInt);
	  var urlDownloadPalestrantes = base+'api/compactarcertificados/palestra/'+app.getUrlParameter('idPalestra')+'/palestrantes?palestrantes='+JSON.stringify(ArrPalestrantesInt);
	  
	  files.push(urlDownloadParticipantes);
	  files.push(urlDownloadPalestrantes);

	  for(var ii=0; ii<files.length; ii++){
		downloadURL(files[ii]);
	  }
	}

	var count=0;
	var downloadURL = function downloadURL(url){
	  var hiddenIFrameID = 'hiddenDownloader' + count++;
	  var iframe = document.createElement('iframe');
	  iframe.id = hiddenIFrameID;
	  iframe.style.display = 'none';
	  document.body.appendChild(iframe);
	  iframe.src = url;
	  
	  setTimeout(function(){
		  app.alertaAnimado('body', 'mousemove', '#alertaDownloadCertificados',7000);
	  }, 3000);
	  setTimeout(function(){
		$('body').unbind('mousemove');
	  }, 3400);
	  
	}


}); //LISTA PALESTRANTES

});