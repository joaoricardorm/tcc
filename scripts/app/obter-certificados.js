$(document).ready(function(){

jaBaixou = false;
	
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
		
		jaBaixou = false;
		
		$('#btnObterCertificados .icon-spin').removeClass('hidden');
		
		if($('#cbkImprimir').is(':checked')){
			if(!$('#cbkPDF').is(':checked')){ //se pdf não tiver marcado
				gerarPDFParticipantes(); //ela ja chama o de palestrnates se nao tiver marcada = porem nao substitui arquivos
			}
			setTimeout(function(){  imprimirCertificados(); },500);
		}
		if($('#cbkPDF').is(':checked')){
			gerarPDFParticipantes(); //true = substituir certificados ela ja chama o de palestrnates		
		}
		if($('#cbkEmail').is(':checked')){
			if(!$('#cbkPDF').is(':checked')){ //se pdf não tiver marcado
				gerarPDFParticipantes(); //ela ja chama o de palestrnates se nao tiver marcada = porem nao substitui arquivos
			}
			setTimeout(function(){  enviarEmailCertificados(); },500);
		}
	});

	function gerarPDFParticipantes(substituirArquivos){
		
		//porpadrao nao substitui
		substituirArquivos = typeof substituirArquivos !== 'undefined' ? substituirArquivos : false;
		
		var qtd=1;
			
		$.each( ArrParticipantesInt , function(key, idParticipante) {
				console.log(idParticipante);
				
			geraCertificado = $.ajax(base+'api/gerarcertificados/palestra/'+app.getUrlParameter('idPalestra')+'?participantes=['+idParticipante+']&substituir='+substituirArquivos);

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
						//$('#progresso').addClass('animated fadeOutUp').delay(450).queue(function(){ $(this).removeClass('animated fadeOutUp fadeInUp').addClass('hidden'); });
						
						//$('#progresso').addClass('hidden');
						
						//$('#btnObterCertificados .icon-spin').addClass('hidden');
							
					}, 450);
					
					//CHAMA A FUNÇÃO DE GERAR PDF DOS PALESTRANTES
					gerarPDFPalestrantes();
					
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
					
					
					//CHAMA FUNÇÃO PARA baixar Os CERTIFICADOS se o checkbox de pdf estiver marcado
					if($('#cbkPDF').is(':checked')){
						setTimeout(function(){  downloadCertificados(); },500);
					}
					
					
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
		  
		  
		if(jaBaixou === false) {  
			  files.push(urlDownloadParticipantes);
			  files.push(urlDownloadPalestrantes);

			  for(var ii=0; ii<files.length; ii++){
				downloadURL(files[ii]);
			  }
			  jaBaixou = true;
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
	
	
function imprimirCertificados(substituirArquivos){
	
		//porpadrao nao substitui
		substituirArquivos = typeof substituirArquivos !== 'undefined' ? substituirArquivos : false;
		
		$('#progresso').removeClass('hide').addClass('animated fadeIn');
		$('#progresso .acao').text('Preparando a ata');
		$('.progress-bar').addClass('active').removeClass('bar-success').css('width', '100%').attr('aria-valuenow', 100).text('');
		
		mesclarCertificados = $.get(base+'api/mesclarcertificados/palestra/'+app.getUrlParameter('idPalestra')+'?participantes='+JSON.stringify(ArrParticipantesInt)+'&substituir='+substituirArquivos);

		
		console.log('LINK',base+'api/mesclarcertificados/palestra/'+app.getUrlParameter('idPalestra')+'?participantes='+JSON.stringify(ArrParticipantesInt)+'&substituir='+substituirArquivos);
		
			mesclarCertificados.complete(function(response){
		
		
					$('.progress-bar').addClass('bar-success');
		
		
				if(response){
					//document.location.href = base+response;
					
					$('#progresso').removeClass('fadeIn').addClass('hide animated fadeOutUp').delay(900).queue(function(){ 
						$(this).hide();					
					});
					
					var urlPDFImpressao = response.responseText;
					
					app.printPDF(urlPDFImpressao);
				}
				
				console.log('LINK CERTO',response.responseText);	

		});				
	}			
	
	
	//ENVIAR CERTIFICADOS POR EMAIL
	function enviarEmailCertificados(){

		var qtd=1;
			
		$.each( ArrParticipantesInt , function(key, idParticipante) {
				console.log('IDPART',idParticipante);
				
			enviarCertificado = $.ajax(base+'api/enviaremailcertificados/palestra/'+app.getUrlParameter('idPalestra')+'?participantes=['+idParticipante+']');
			
			console.log(base+'api/enviaremailcertificados/palestra/'+app.getUrlParameter('idPalestra')+'?participantes=['+idParticipante+']');

			enviarCertificado.complete(function(response){
				
				if(qtd === 1){
					$('#progresso').removeClass('hidden').addClass('animated fadeInUp');
					$('#progresso .acao').text('Preperando para enviar os certificados por e-mail');
					$('.progress-bar').css('width',0).attr('aria-valuenow', 0).text('0%');
				}
				
				console.log('Deu certo',response);
				
				emailEnviado = JSON.parse(response.responseText);
				console.log(emailEnviado);
				
				if(emailEnviado.success === true)
					$('#progresso .acao').text('('+qtd+'/'+totalParticipantes+') Enviou para '+emailEnviado.email);
				
				progresso = qtd/totalParticipantes*100;
				 $('.progress-bar').css('width', progresso+'%').attr('aria-valuenow', progresso).text(progresso.toFixed(1)+'%'); 
				
				if(qtd===totalParticipantes){
					setTimeout(function(){
						// $('#progresso').addClass('animated fadeOutUp').delay(450).queue(function(){ $(this).removeClass('animated fadeOutUp fadeInUp').addClass('hidden'); });
						// $('#progresso').addClass('hidden');
						// $('#btnObterCertificados .icon-spin').addClass('hidden');

						enviarEmailCertificadosPalestrantes();	
						
					}, 450);
					
				}
				
				qtd++;
			
				console.log('RESPOSTA',response,idParticipante,'Progresso',progresso);
			});			

		});				
	}	
	
	
	
	//ENVIAR CERTIFICADOS POR EMAIL DOS PALESTRANTES
	function enviarEmailCertificadosPalestrantes(){

		var qtd=1;
			
		$.each( ArrPalestrantesInt , function(key, idPalestrante) {
			console.log('IDPAL',idPalestrante);
				
			enviarCertificado = $.ajax(base+'api/enviaremailcertificados/palestra/'+app.getUrlParameter('idPalestra')+'?palestrantes=['+idPalestrante+']');

			enviarCertificado.complete(function(response){
				
				if(qtd === 1){
					$('#progresso').removeClass('hidden').addClass('animated fadeInUp');
					$('#progresso .acao').text('Preperando para enviar os certificados do palestrantes por e-mail');
					$('.progress-bar').css('width',0).attr('aria-valuenow', 0).text('0%');
				}
				
				console.log('Deu certo',response);
				
				emailEnviado = JSON.parse(response.responseText);
				console.log(emailEnviado);
				
				if(emailEnviado.success === true)
					$('#progresso .acao').text('('+qtd+'/'+totalPalestrantes+') Enviou para o palestrante '+emailEnviado.email);
				
				progresso = qtd/totalPalestrantes*100;
				 $('.progress-bar').css('width', progresso+'%').attr('aria-valuenow', progresso).text(progresso.toFixed(1)+'%'); 
				
				if(qtd===totalPalestrantes){
					$('#progresso .acao').text('Todos os e-mails foram enviados com sucesso');
					
					setTimeout(function(){
						$('#progresso').addClass('animated fadeOutUp').delay(450).queue(function(){ $(this).removeClass('animated fadeOutUp fadeInUp').addClass('hidden'); });
						$('#progresso').addClass('hidden');
						$('#btnObterCertificados .icon-spin').addClass('hidden');	
					}, 5000);
					
				}
				
				qtd++;
			
				console.log('RESPOSTA',response,idPalestrante,'Progresso',progresso);
			});			

		});				
	}		
	
	


}); //LISTA PALESTRANTES

});