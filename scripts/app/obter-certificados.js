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
} else {
	console.log('Textarea dos participantes est√° vazio');
}	

console.log(ArrParticipantes);
	
$('#btnObterCertificados').click(function(){
	if($('#cbkImprimir').is(':checked')){
		imprimirCertificados();
	}
	if($('#cbkPDF').is(':checked')){
		//alert('PDF');
		
		// $.each(ArrParticipantes,function(idParticipante){
			// geraPDFCertificadoParticipante(idParticipante);
		// });
	}
	if($('#cbkEmail').is(':checked')){
		alert('Email');
	}
});

function imprimirCertificados(idParticipante){
	// save all cell's data
    var request = $.ajax({
		url: base+'api/imprimircertificados/palestra/'+app.getUrlParameter('idPalestra'),
		type: "post",
		contentType: "application/json",
		dataType: 'json',
		data: JSON.stringify(ArrParticipantes),
		complete: function(t){
			page.timerLigado = false;
		},
		beforeSend: function(){
			app.showProgress('modelLoader');
			app.showProgress('savingFloat');
		},
		error: function(model,response,scope){	
				
				console.log('Ocorreram erros ao salvar alguns participantes. Por favor verifique.', 'alert-error',0,'modelAlert');
				
				console.log(response);

				try {
					var json = $.parseJSON(model.responseText); 				
					
						$.each( json.errors , function(key, value) {
							
							var elemento = null;
							elemento = $('.htCore tr#item_'+key);
							if(!key)
								elemento = $('#table-participantes');
							//elemento.addClass('error');
							
							$.each( value.message , function(campo, msg, row) {
								// var options = {
									// title: 'Titulo',
									// content: 'TEste',
									// placement: 'bottom'
								// }
								//elemento.popover(options).popover('show');
								//elemento.append('<div class="error-handsontable-item">?'+msg+'</div>');
								
								switch(campo) {
									case 'Cpf':
										nomeCampo = 'CPF'
										break;
									case 'Email':
										nomeCampo = 'E-mail'
										break;
									default:
										nomeCampo = campo
										break;
								}
								
								app.appendAlert('<b style="color:#999">Erro na linha '+ (value.row+1) +' - '+nomeCampo+':</b> '+msg+' ', 'alert-dark small',0,'modelAlert');
						
								$('<a class="goto btn btn-small btn-warning" data-id="'+key+'"data-row="'+value.row+'">CORRIGIR</a>').appendTo('#modelAlert .alert:last-child');
								$('<a style="margin-left:5px" class="gotoback btn btn-small btn-primary hide" data-row="'+value.row+'">CORRIGIDO</a>').appendTo('#modelAlert .alert:last-child');
								
								$('.goto').click(function(e){
									var id = $(this).data('id');
									var linha = parseInt($(this).data('row'));
									hot.selectCell(linha, 0, linha, 3, true);
									
									var itemTop = $('.handsontable').position().top;
									if(id != '')
										itemTop = $('#item_'+id).position().top;
									
									//$(this).parent().css({position:'fixed',top: $('.modal-scrollable').scrollTop() ,zindex:6000});
									$('.alert').removeClass('stick');
									$(this).parent().addClass('stick').addClass('float-bottom-notification');
									
									$('.modal-scrollable').scroll(function() {
										 var a=$('.modal-scrollable').scrollTop();
										 $('.stick').css('margin-top',a+'px');
									});
									
									//Desce atÈ a linha do elemento (-500 no monitor 22 pol)
									$('.wtHolder').scrollTop( itemTop - ($('.handsontable').height()/2) );
									$('.modal-scrollable').scrollTop( $(document).height() + 300 ); 
									
									$(this).parent().find('.gotoback').removeClass('hide');
									$(this).hide();
								});
	
								$('.gotoback').on('click',function(e){
									//Fecha a notificaÁ„o ao clicar nela em corrigido
									//$(this).unbind('click');
								
										//Volta ao topo se der erro
										$('.modal-scrollable, .wtHolder').scrollTop(0);
				
										$('.alert.stick').addClass('animated bounceOutUp').delay(1000).queue(function(){
											$(this).remove();
										
											//Se n„o existir mais nenhum erro ele j· salva
											//O LENGTH DEVERIA SER 0 PARA MOSTRA TODAS AS NOTIFICA«’ES DE ERRO, MAS N√O EST¡ MOSTRANDO
											if ( $( ".alert.alert-dark" ).length === 1) {
												
												$('#modelAlert').html('');
												$('.control-group').removeClass('error');
												$('.help-inline').html('');
												
												saveCar.click();
											}
										});
										
						
								});
								
							});
						});
					
					if (json.errors) {
						$.each(json.errors, function(key, value) {
							$('#'+key+'InputContainer').addClass('error');
							$('#'+key+'InputContainer span.help-inline').html(value);
							$('#'+key+'InputContainer span.help-inline').show();
						});
					}
				} catch (e2) {
					if (console) console.log('error parsing server response: '+e2.message);
				}
		}
		
	});	
	
	request.success(function( response ) {
		
		// reset any previous errors
		$('#modelAlert').html('');
		$('.control-group').removeClass('error');
		$('.help-inline').html('');
		
		//Volta ao topo se der erro
		$('.modal-scrollable').scrollTop(0);

		//Mensagem com a hora que foi salvo
		$('#save_car').attr('data-autosave','Salvo ‡s '+_date(app.parseDate(salvoUltimaVez)).format('HH:mm:ss'));
				
		//Para nao perguntar para salvar ao fechar modal		
		page.temAlteracoes = false;	
		$('#btnCloseModalPrincipal').on('click',function() {
			$('#modalSalvarAlteracoes, #participanteDetailDialog').modal('hide');
		});			
				
		//Recarrega dados		
		hot.loadData(ppp.rows);
		page.fetchParticipantes(page.fetchParams);
		
		app.hideProgress('modelLoader');
		app.hideProgress('savingFloat');		
		app.appendAlert(response.message, 'alert-success',0,'modelAlert');
		
		//para nao dar erro no length
		if (typeof response.novo == "undefined")
			response.novo = {};

		//Adiciona id dos novos participantes cadastrados na tabela
		if(response.novo.length > 0){
		$.each( response.novo , function(index, novo) {
			hot.setDataAtCell(novo.row, 0, novo.idParticipante);	
		});
		}
		
		
	});
	 
	request.fail(function( jqXHR, textStatus ) {
	 console.log( "FALHA AO ENVIAR PARTICIPANTES PARA O SERVIDOR: " + textStatus );
	 //console.log(jqXHR);
	 console.log('.........');
	});
}

});