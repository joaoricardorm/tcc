$(document).ready(function(){
	
	if (!app.browserSucks()) {
		$('#selectEventos, #selectAtividades').selectpicker({
			style: 'btn-default btn-large',
			liveSearch: true
		});
		$('#selectAtividades').hide();
	}
	
	$('#btnContinuar').click(function(e){
		if($('#selectAtividades').val() === ''){
			e.preventDefault();
			alert('Escolha uma atividade primeiro');
		}
	});
	
	$('#selectEventos, #selectAtividades').change(function(){ $(this).selectpicker('setStyle', 'btn-primary btn-large'); });
	
	$('#selectAtividades').change(function(){
		$('#btnContinuar').removeClass('hide').addClass('animated fadeInDown').delay(900).queue(function(){ $(this).removeClass('animated'); });
	});
	
	$('#selectEventos').change(function(){
		$('#loader').removeClass('hidden');
		
		var atividades = $.getJSON(base+'api/palestras?idEvento='+$(this).val());

		atividades.success(function(todasAtividades){
			$('#selectAtividades').empty();
			$('#selectAtividades').append('<option value="" selected disabled>Atividades</option>');
			$.each( todasAtividades.rows , function(key, value) {
				$('#selectAtividades').append('<option value="'+value.idPalestra+'">'+value.nome+'</option>');				
				if(value.proprioEvento === '1'){
					$('#listaAtividades').addClass('hide');	 
					$('#selectAtividades').val(value.idPalestra).show();
					$('#btnContinuar').removeClass('hide').addClass('animated fadeInDown');
				 } else {
					$('#listaAtividades').removeClass('hide').addClass('animated fadeInDown');
				 }
				 
			});	
			
			$('#selectAtividades').selectpicker('setStyle', 'btn-default btn-large');
			$('#selectAtividades').selectpicker('refresh');
			$('#loader').addClass('hidden');
		});		
		
	});
	
	
	var palestrantes = $.getJSON(base+'api/palestrapalestrantes?idPalestra='+app.getUrlParameter('idPalestra')+'&naoTemCertificado=true');
	var participantes = $.getJSON(base+'api/palestraparticipantes?idPalestra='+app.getUrlParameter('idPalestra')+'&naoTemCertificado=true');
	
	if(typeof app.getUrlParameter('idPalestra') !== 'undefined'){ 
		$('#conteudo').css('position','relative').prepend('<div id="avisoRedirecionamento" class="overlay-big-message full"><span class="icon-refresh icon-spin icon-big" style="margin-left:5px;"></span>Aguarde um momento...</div>');
		
		//TIRAR
		//emitirAta();
	}
	
	palestrantes.success(function(todosPalestrantes){
	participantes.success(function(todosParticipantes){	
		
		//PARA PREVER EM QUAL PÁGINA CAIRÁ O CÓDIGO FINAL DOS CERTIFICADOS 
		var totalCertificados =  todosPalestrantes.totalResults + todosParticipantes.totalResults;

		var totalFolhas = Math.ceil(totalCertificados / 35); //CALCULA O TOTAL DE FOLHAS, A CADA 35 REGISTROS AUMENTA 1	
		
		$('#folhaInputContainer .help-inline').text('até '+(parseInt($('#folha').val()) + totalFolhas-1));
		
		$('#codigoInputContainer .help-inline').text('até '+(parseInt($('#codigo').val()) + totalCertificados-1));
		
		$('#folha').keyup(function(){
			val = $(this).val();
			if(!isNaN(parseFloat(val)) && isFinite(val)){
				t = totalFolhas-1 + parseInt($(this).val());
				$('#folhaInputContainer .help-inline').text('até '+t);
				$('#folhaInputContainer').removeClass('error');
			} else {
				$('#folhaInputContainer .help-inline').text('Isso não é um número!')
				$('#folhaInputContainer').addClass('error');
			}
		});
		
		$('#codigo').keyup(function(){
			val = $(this).val();
			if(!isNaN(parseFloat(val)) && isFinite(val)){
				t = totalCertificados-1 + parseInt($(this).val());
				$('#codigoInputContainer .help-inline').text('até '+t);
				$('#codigoInputContainer').removeClass('error');
			} else {
				$('#codigoInputContainer .help-inline').text('Isso não é um número!')
				$('#codigoInputContainer').addClass('error');
			}
		});
		
		//TRABALHAR SEMPRE COM URL FEIA AQUI IDPALESTRA COMO PARAMETRO
		//REDIRECIONA PARA CERTIFICADOS EMITIDOS SE TODOS JÁ TIVEREM SIDO EMITIDOS
		function redirecionaSeTodosTemCertificados(){			
			if(todosPalestrantes.totalResults === 0 && todosParticipantes.totalResults === 0){
				redirecionaCertificadosEmitidos();
			} else {
				$('#avisoRedirecionamento').remove();
				$('#loader').hide();
			}	
		}
		
		//SE NÃO EXISTIR PALESTRA NA URL ELE CHAMA FUNCAO PARA REDIRECIONAR
		if(typeof app.getUrlParameter('idPalestra') !== 'undefined'){ 
			redirecionaSeTodosTemCertificados();
		}
			
		});
	});

	//Validação
	function validaCampos(){	
		$('#dadosAta .control-group.error .help-inline').text('');
		$('#dadosAta .control-group').removeClass('error');
		
		if($('#codigo').val() === ''){ $('#codigo').focus(); $('#codigoInputContainer').addClass('error'); $('#codigoInputContainer .help-inline').text('Campo obrigatório');}
		if($('#folha').val() === ''){ $('#folha').focus(); $('#folhaInputContainer').addClass('error'); $('#folhaInputContainer .help-inline').text('Campo obrigatório'); }
		if($('#livro').val() === ''){ $('#livro').focus(); $('#livroInputContainer').addClass('error'); $('#livroInputContainer .help-inline').text('Campo obrigatório'); }
		
		if($('#livro').val() === '' || $('#folha').val() === '' || $('#codigo').val() === '') return false;

		return true;
	}	
		
	$('#btnContinuarEmitir').click(function(e){			
		if(validaCampos())
			$('#alertaEmitirCertificados').removeClass('hide').addClass('animated rubberBand');
	});
	
	//EMISSÃO DOS CERTIFICADOS
	
	var dados = {};
	
	$('#btnEmitirCertificados').click(function(e){			
		$('#dadosAta').addClass('animated fadeOutUp').delay(900).queue(function(){ 
			
			var $inputs = $(':input',this);

			$inputs.each(function() {
				dados[this.name] = $(this).val();
			});

			$(this).remove(); 
			
			emitirCertificadosPalestrantes();
		});
	});
	
	function redirecionaCertificadosEmitidos(){
		document.location.href = base+'certificados-emitidos/';
	}
	
	function emitirCertificadosPalestrantes(){
		
		//GERA CERTIFICADOS PARA OS PALESTRANTES QUE AINDA NAO TEM CERTIFICADO
		var palestrantes = $.getJSON(base+'api/palestrapalestrantes?idPalestra='+dados.idPalestra+'&naoTemCertificado=true');
			
			palestrantes.success(function(todosPalestrantes){
				
				console.log(todosPalestrantes);
			
				if(todosPalestrantes.totalResults > 0){
					
					$('#progresso').removeClass('hide').addClass('animated fadeInUp');
					$('#progresso .acao').text('Preparando certificados dos palestrantes');
				
					//LOOP NOS PALESTRANTES
					qtd=1;	

					var livro = dados.livro;
					var folha = dados.folha;
					var codigo = dados.codigo; 
					
					console.log(todosPalestrantes);
					$.each( todosPalestrantes.rows , function(key, palestrante) {
						
						geraCertificado = $.get(base+'api/geracertificadopalestrante/'+palestrante.idPalestrante+'?idPalestra='+dados.idPalestra+'&livro='+livro+'&folha='+folha+'&codigo='+codigo);

						//console.log(base+'api/geracertificadopalestrante/'+palestrante.idPalestrante+'?idPalestra='+dados.idPalestra+'&livro='+livro+'&folha='+folha+'&codigo='+codigo);
						
						geraCertificado.success(function(response){
							progresso = qtd/todosPalestrantes.totalResults*100;
							 $('.progress-bar').css('width', progresso+'%').attr('aria-valuenow', progresso).text(progresso.toFixed(1)+'%'); 
							
							if(qtd===todosPalestrantes.totalResults){
								setTimeout(function(){
									$('#progresso').addClass('animated fadeOutUp').delay(450).queue(function(){ $(this).removeClass('animated fadeOutUp').addClass('hide'); });
								}, 450);
								emitirCertificadosParticipantes(codigo); //puxa o último codigo gerado do palestrante
							}
							
							qtd++;
						
							console.log('RESPOSTA',response,codigo,folha,livro);
						});			
						
						if(codigo % 35 === 0) folha++; // a cada 35 itens aumenta uma folha da ata
						codigo++; 	
						
					});				
			
				} else {
					emitirCertificadosParticipantes(dados.codigo); //puxa o coigo do form, já que nao tem palestrante
				}	
				
			});	
			
	}
	
	function emitirCertificadosParticipantes(codigoInicial){
		
		//GERA CERTIFICADOS PARA OS PARTICIPANTES QUE AINDA NAO TEM CERTIFICADO
		var participantes = $.getJSON(base+'api/palestraparticipantes?idPalestra='+dados.idPalestra+'&naoTemCertificado=true');
			
			participantes.success(function(todosParticipantes){
				
				console.log(todosParticipantes);
			
				if(todosParticipantes.totalResults > 0){
					
					$('#progresso').removeClass('hide').addClass('animated fadeIn');
					$('#progresso .acao').text('Preparando certificados dos participantes');
				
					//LOOP NOS PALESTRANTES
					qtd=1;	

					var livro = dados.livro;
					var folha = dados.folha;
					var codigo = codigoInicial; //para nao conflitar com o dos palestrantes 
					
					console.log(todosParticipantes);
					$.each( todosParticipantes.rows , function(key, participante) {
						
						geraCertificado = $.get(base+'api/geracertificadoparticipante/'+participante.idParticipante+'?idPalestra='+dados.idPalestra+'&livro='+livro+'&folha='+folha+'&codigo='+codigo);

						//console.log(base+'api/geracertificadopalestrante/'+participante.idParticipante+'?idPalestra='+dados.idPalestra+'&livro='+livro+'&folha='+folha+'&codigo='+codigo);
						
						geraCertificado.success(function(response){
							progresso = qtd/todosParticipantes.totalResults*100;
							$('.progress-bar').css('width', progresso+'%').attr('aria-valuenow', progresso).text(progresso.toFixed(1)+'%'); 
							
							if(qtd===todosParticipantes.totalResults){ //settimeout pois é o penultimo ainda
								setTimeout(function(){
									emitirAta(); //SÓ DEPOIS QUE HOUVER PARTICIPANTES
									console.log('Terminou a emissao dos certificados dos participantes');
								}, 300);
							}
							
							qtd++;
						
							console.log('RESPOSTA',response,codigo,folha,livro);
						});			
						
						if(codigo % 35 === 0) folha++; // a cada 35 itens aumenta uma folha da ata
						codigo++; 	
						
					});				
			
				} else {
					emitirAta();
					//redirecionaCertificadosEmitidos();
					console.log('QUANDO NÃO TEM PARTICIPANTE');
				}	
				
			});	
			
	}
	
	function emitirAta(){
		
		$('#progresso').removeClass('hide').addClass('animated fadeIn');
		$('#progresso .acao').text('Preparando a ata');
		$('.progress-bar').addClass('active').removeClass('bar-success').css('width', '100%').attr('aria-valuenow', 100).text('');
		
		//GERA ATA DA ATIVIDADE
		var ata = $.getJSON(base+'api/gerarata/'+dados.idPalestra); //SUBSTITUIR POR dados.idPalestra
			
			ata.done(function(ataEmitida){
				$('.progress-bar').addClass('bar-success');
				console.log('feito',ataEmitida);
			});
			
			ata.fail(function(ataEmitida){
				$('.progress-bar').addClass('bar-success');
				console.log('falhou',ataEmitida);
			});
			
			ata.success(function(ataEmitida){
				$('.progress-bar').addClass('bar-success');
				
				//BAIXA A ATA
				if(ataEmitida.success){
					document.location.href = base+'api/downloadata/'+dados.idPalestra;
					
					$('#alertaDownloadAta').removeClass('hide').addClass('animated rubberBand');
					$('#progresso').removeClass('fadeIn').addClass('hide animated fadeOutUp').delay(900).queue(function(){ 
						$(this).hide();					
					});
				}
			});	
			
	}
	
});