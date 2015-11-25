$(document).ready(function(){
			
	
	app.alertaAnimado('#btnObterAta', 'click', '#alertaDownloadAta',4000);
     
			function getTags(elemento) {
				$('#loader').hide();
				
				var tags = elemento.clone().find('.tagit-close').remove().end();
				
				var listaTags = '';
				
				
					
					var corTextoDinamico = $('.containerPDF .dbitem').css('color');	

					$('.ToolBarDbItem input[type=color]').val(rgb2hex(corTextoDinamico));
					$('.ToolBarGeral input[type=color]').val(rgb2hex($('.containerPDF').css('color')));
					
			
				tags.find('.tagit-choice').each(function(i, el){
					
					var item = $(this).text().trim();
					
					//junta as strings de acordo com ponto final, virgula etc...
					join = ' ';
					if(item.match(/(,|\.|;|\?)(\ |^$)/i))
						join = '';	

						/* var corDinamicaRGB = $('.containerPDF #containerDinamico').html().match(/style\=\"color:\ (.*?)\;\"/);
						console.log('ALOHA',corDinamicaRGB[1]); */
					
				
					
					if($(this).hasClass('dbitem'))
						item = '<span class="dbItemCertificado '+$(this).attr('class')+'" style="color: '+corTextoDinamico+'">'+item+'</span>';
					
					listaTags = listaTags + join + item;
				});
				
				console.log(listaTags);
				
				//var listaTagsTrimmed = $.map(listaTags, $.trim);
				

					
				var alinhamento = '';
				alinhamento = $('.containerPDF').attr('class').match(/.*?justify([a-z]+)/i);
				
				if(typeof alinhamento !== 'undefined')
					$('a[data-edit="justify'+alinhamento[1]+'"]').click();
				
				
				var bold = '';
				bold = $('.containerPDF').attr('class').match(/.*?bold/i);
				console.log(bold);
				if(typeof bold !== 'undefined' && bold != null)
					$('.ToolBarGeral a[data-edit="bold"]').click();
				
				var italic = '';
				italic = $('.containerPDF').attr('class').match(/.*?italic/i);
				console.log(italic);
				if(typeof italic !== 'undefined' && italic != null)
					$('.ToolBarGeral a[data-edit="italic"]').click();

				setTimeout(function(){
					if(typeof bold !== 'undefined' && bold != null)
						$('.ToolBarGeral a[data-edit="bold"]').addClass('btn-primary');
					
					if(typeof italic !== 'undefined' && italic != null)
						$('.ToolBarGeral a[data-edit="italic"]').addClass('btn-primary');
				},1000);
				
				
				return listaTags;
			}
			
			function getTagsAsJSON(elemento) {
				$('#loader').hide();
				
				var tags = elemento.clone().find('.tagit-close').remove().end();
				
				var listaTags = '[';
			
				tags.find('.tagit-choice').each(function(i, el){
					
					var item = '"'+$(this).text().trim()+'"';
					
					//junta as strings de acordo com ponto final, virgula etc...
					var join = '';
					if(i > 0)
						join = ',';
					
					if($(this).hasClass('dbitem'))
						item = '{ "label": '+item+', "class": "'+$(this).attr('class')+'" }';
					
					listaTags = listaTags + join + item;
				});
				
				
				listaTags = listaTags + ']';
				
				return listaTags;
			}
			
			
			
		



			
			
	
		
		ehPalestrante = '';
			if(typeof app.getUrlParameter('palestrante') !== 'undefined' && app.getUrlParameter('palestrante') !== '')
			  ehPalestrante = '&palestrante=true';
		  
		  console.log('EH',ehPalestrante);
			
			var modeloAtual = $.getJSON(base+'api/modelocertificados/?idPalestra='+app.getUrlParameter('idPalestra')+'&palestrante=true');
			
		//TEM QUE PUXAR DO ID DA PALESTRA
		var modeloCertificadoJSON = $.getJSON(base+'api/modelocertificados?idPalestra='+app.getUrlParameter('idPalestra')+ehPalestrante);

		modeloCertificadoJSON.success(function(modeloCertificado){					
			
			
			
			
			
			//TEM QUE PUXAR DO ID DA PALESTRA
		var palestraJSON = $.getJSON(base+'api/palestra/'+app.getUrlParameter('idPalestra'));
		
		palestraJSON.success(function(palestra){				
			
			
			
			
			
			
			//TROCAR POR TEXTOPALESTRANTE NESSAS DUAS LINHAS
			
			
			var certificadoPadrao = $.getJSON(base+'api/modelocertificado/1');

		certificadoPadrao.success(function(certPadrao){
			
			
			
			
			
			function updateToolBar(){
				$(toolbarBtnSelector).each(function(e) {
					if($(this).data('selected') === true){
						el = $(this);
						el.data('selected',false);
						
						el.click(); console.log('clickou 1',el);
					}
					
					setTimeout(function(){
						//$('.ToolBarDbItem input[type=color]').val(rgb2hex($('.containerPDF .dbitem').css('color')));
						//$('.ToolBarGeral input[type=color]').val(rgb2hex($('.containerPDF').css('color')));	
					}, 5000);
				});
			}
			
			
			
			
			
		
			//PREENCHE OS DADOS NO BANCO SE JÁ EXISTIREM OU NÃO
			
			var textoTags; 
			var textoPalestranteBD;
			var textoParticipanteBD;
			
			if(app.getUrlParameter('palestrante') === true && app.getUrlParameter('palestrante') !== 'undefined'){
			 
			  if(modeloCertificado.rows[0].textoPalestrante === '')
				textoTags = certPadrao.textoPalestrante;
			  else
				textoTags = modeloCertificado.rows[0].textoPalestrante;
				
			  if(modeloCertificado.rows[0].textoParticipante === '')
				textoParticipanteBD = certPadrao.textoParticipante
			  else
				textoParticipanteBD = modeloCertificado.rows[0].textoParticipante;
			 
              textoPalestranteBD = textoTags;
			
			} else {
				
			  if(modeloCertificado.rows[0].textoParticipante === '')
				textoTags = certPadrao.textoParticipante;
  			  else
				textoTags = modeloCertificado.rows[0].textoParticipante;
			  
			  if(modeloCertificado.rows[0].textoPalestrante === '')
				textoPalestranteBD = certPadrao.textoPalestrante
			  else
				textoPalestranteBD = modeloCertificado.rows[0].textoPalestrante;
			 
              textoParticipanteBD = textoTags;
			
			}
			
			console.log('TEXTAOTAGAO',textoTags);
			
			if(modeloCertificado.rows[0].elementos != '')
				$('#previewCertificado').html(modeloCertificado.rows[0].elementos);
			else
				$('#previewCertificado').html(certPadrao.elementos);
			
			function previewCertificado(){
					
					getTagsAsJSON($('#sortable1'));
				
				
					preview = getTags($('#sortable1'));
					$('#previewCertificado #containerDinamico').html(preview);
					updateToolBar();
			}
			
			
			
			var json = JSON.parse(textoTags);			
			
			$('#sortable1').tagit({
			focusInputOnLoad: true,
			tagSource:json,
			initialTags:json,
			triggerKeys:['enter', 'tab'],
			sortable: false,
			tagsChanged: function(tagValue, action, element){

			$('#sortable1 .tagit-choice:not(.dbitem)').click(function(){
				
				if($('.inputTagEdit').length <= 0){
					textoSemX = $(this).clone().find('.tagit-close').remove().end();
					tagWidth = $(this).width();
					$(this).html('<input class="inputTagEdit" type="text" value="'+textoSemX.text()+'" style="width:'+tagWidth+'px!important;">').parent('box-sizing','border-box');
					$('.inputTagEdit',this).keyup(function(e){
						$(this).parent().css('width',$(this).width()+'px!important');
						if(e.which == 13 || e.keyCode == 13){
							$(this).parent().html($(this).val());
							previewCertificado();
						}
					});
				}
				
			});
			
			console.log(action,tagValue,element);

			//redimensionaInputTagit();

			elemento = element.element;

			if(action === 'added'){
			var classe;
			$("#sortable2 .dbitem").filter(function() { 
				var textoSemX = $(this).clone().find('.tagit-close').remove().end();
				if( textoSemX.text() == tagValue ){
					classe = 'dbitem ui-sortable-handle';
				}
			});

			$("#sortable2 li").filter(function() { 
				var textoSemX = $(this).clone().find('.tagit-close').remove().end();
				if( textoSemX.text() == tagValue ){
					$(this).addClass('animated fadeOutUp').delay(450).queue(function(){ $(this).hide(); });
				}
			});
				

			$( "#sortable1 .tagit-choice" ).last().addClass(classe);

			var classe;
			$("#sortable2 .dbitem").filter(function() { 
				if( $(this).text().trim() == tagValue.trim() ){ 
					classe = 'dbitem ui-sortable-handle';
				}
			});

			if(elemento)
				elemento.addClass(classe+' animated fadeInUp').attr('title', 'Remover do texto do certificado de participante').find('.tagit-close').text('x');
			}
			if(action === 'popped'){
			elemento.addClass('animated bounceIn').attr('title', 'Adicionar ao texto do certificado de participante').find('.tagit-close').text('+');
			//if(!elemento.hasClass('dbitem'))
				$('#sortable2').append(elemento);
			}

			previewCertificado();

			}
			});

			//$('#sortable1').sortable('option','connectWith',".connectedSortable");

			$('.tagit-input[type=text]').attr('placeholder','Inserir texto...').css('width','300!important');

			function redimensionaInputTagit(){
			soma = 0; 
			$('#sortable1 .tagit-choice').each(function(){ 
			soma += $(this).width(); 
			});
			largura = $('#sortable1').width() - soma - 200;
			console.log(soma);
			if(largura > 120)
			$('.tagit .tagit-new').width(largura);
			else 
			$('.tagit .tagit-new').width('90%');
			} 

			previewCertificado(); 
			
			
			
			
			
		
			$('#btnContinuar').click(function(){	
			
			$('.icon-arrow-right',this).addClass('hidden')
			$('.icon-spin',this).removeClass('hidden');
			
			var ehPalestrante = '';
			if(typeof app.getUrlParameter('palestrante') !== 'undefined' && app.getUrlParameter('palestrante') !== '')
			  ehPalestrante = '&palestrante=true';
		  
		  console.log('EHaqui',ehPalestrante);
			
			var modeloAtual = $.getJSON(base+'api/modelocertificados/?idPalestra='+app.getUrlParameter('idPalestra')+ehPalestrante);
	
			modeloAtual.success(function(todosModelos){
					
				
				modAtual = todosModelos.rows[0];	
			
				modeloCertificado = new model.ModeloCertificadoModel();
				
				console.log('DADOS',modeloCertificado);
				
				if(modAtual.idModeloCertificado !== '1'){
					modeloCertificado.set({idModeloCertificado: modAtual.idModeloCertificado});
					modeloCertificado.fetch();
				}
				
				console.log('MODLEO ATUAL',modeloCertificado.id);
					
					
					modPadrao = new model.ModeloCertificadoModel();

					modPadrao.set({idModeloCertificado: 1});
					modPadrao.fetch();
					
					console.log(modPadrao.get('textoParticipante'));
					
					modeloCertificado.save({
						'nome': palestra.nome,
						'textoParticipante': textoParticipanteBD,
						'textoPalestrante': textoPalestranteBD,
						'arquivoCss': 'padrao',
						'elementos': $('.containerCertificado').html().replace(/hide-palestrante\ hide/,'hide-palestrante')
					}, {
						wait: true,
						success: function(modelo){
							console.log('Mod',modelo);
							console.log('Atualizou ou criou o modelo de certificado para a palestra');
							
							//SALVA O MODELO DE CERTIFICADO NA PALESTRA ATUAL


		var palestrasBackbone = new model.PalestraCollection();	
		
		palestrasBackbone.fetch({
			data : {
				idPalestra: palestra.idPalestra
			},
			success: function(pa){
				
				p = pa.first();
									
				p.save({
						//MUDA ISSO
						'idModeloCertificado': parseInt(modelo.get('idModeloCertificado')),
						//MANTEM ISSO-->
						'nome': p.get('nome'),
						'data': p.get('data'),
						'cargaHoraria': p.get('cargaHoraria'),
						'proprioEvento': p.get('proprioEvento'),
						'idEvento': p.get('idEvento')
					}, {
						wait: true,
						success: function(p){
							
							$('#btnContinuar .icon-arrow-right').removeClass('hidden')
							$('#btnContinuar .icon-spin').addClass('hidden');
							
							console.log('Pal',p);
							console.log('Atualizou ou o modelo na palestra',p);
					},
						error: function(model,response,scope){
							console.log("Erro ao atualizar modelo de certificado na palestra");
							console.log(model,response,scope);
						}
				});
								
								
							
			},
				error: function(model,response,scope){
					console.log("Erro ao pegar a palestra");
					console.log(model,response,scope);
				}
			});
				
					
					
					
					
					
					
					
					},
						error: function(model,response,scope){
							console.log("Erro ao cadastrar ou atualizar modelo do certificado");
							console.log(model,response,scope);
						}
					});
				

		
		
			
				
									
				
	
				});
	
		});
			
			
			
			
			
			
			
			
			
			
	
	$( "#sortable1, #sortable2" ).sortable({
		connectWith: ".connectedSortable",
		placeholder : "highlight",
		items: "li:not(.tagit-input)",
		stop: function(event, ui) { previewCertificado(); },
		start: function(e, ui ){
			 ui.placeholder.width(ui.helper.outerWidth());
		},
		receive: function(event, ui) { 			
console.log('ui',ui,'event',event);
			if(this.id == 'sortable1'){ //nao vai # pois nao eh jquery
				
				$(this).tagit("add", $(ui.draggable).text());
		
				$(ui.draggable).remove();
				console.log('ui',ui,'event',event);
			
			} else { //SE FOR DO MENU
				$(ui.draggable).remove();
				$('#sortable1').tagit("remove", ui.item.context.innerText);
				
				$(ui.draggable).attr('title', 'Adicionar ao texto do certificado de participante').find('.tagit-close').text('+');
			}
		}
	}).disableSelection();
	
	
	
	var options, toolbarBtnSelector;
	
	var options = { activeToolbarClass: 'btn-info', toolbarSelector: '[data-role=editor-toolbar]', commandRole: 'edit' };
	toolbarBtnSelector = 'li[data-' + options.commandRole + '],a[data-' + options.commandRole + '],button[data-' + options.commandRole + '],input[type=button][data-' + options.commandRole + ']';		
			
	$(toolbarBtnSelector).click(function(e){ 
		if($(this).hasClass('unico')){
			
			$(this).parent().find(toolbarBtnSelector).each(function () {
				if($(this).hasClass('unico')){
					$(this).removeClass('btn-primary').attr('data-selected',false);
					$($(this).parent().data('target')).removeClass($(this).data(options.commandRole));
				}
			});
			$(this).addClass('btn-primary').attr('data-selected',true);
		
		} else {
			if($(this).data('selected') === true)
				$(this).attr('data-selected',false);
			else 
				$(this).attr('data-selected',true);
		}
		
		if($(this).data('target'))
			el = $(this);
		else
			el = $(this).parent();
		
		if(!$(el.data('target')).hasClass($(this).data(options.commandRole)))
			$(this).addClass('btn-primary');
		else 
			$(this).removeClass('btn-primary');
		
			$(el.data('target')).toggleClass($(this).data(options.commandRole));
			
				
			if($('#btnOrientacao').parent().hasClass('A4portrait'))
				$('#btnOrientacao').addClass('btn-primary')
		});
	
	
	$('#btnPreviewCertificadoPDF').click(function(){
		
		var orientacao = 'landscape';
		if($('.containerA4preview').hasClass('A4portrait'))
			orientacao = 'portrait';
		
		console.log(orientacao);
		
		// save all cell's data
		var request = $.ajax({
			url: base+'api/geracertteste/'+app.getUrlParameter('idPalestra')+'/'+orientacao,
			type: "post",
			contentType: "application/json",
			dataType: 'html',
			data: JSON.stringify({data: $('#previewCertificado').html()}),
			beforeSend: function(){
				$('#btnPreviewCertificadoPDF .carregando').removeClass('hidden');
			},
			complete: function(t,r){
				console.log('terminou');
				console.log(t.responseText);
				
				$('#btnPreviewCertificadoPDF .carregando').addClass('hidden');
				
				if(isMobile)
					document.location.href = base+'api/downloadcertteste/'+app.getUrlParameter('idPalestra');
				else 
					$('#framePDF').html(t.responseText);
			},
			error: function(model,response,scope){
				console.log('erro',model,response,scope);
			},
			success: function(model,response,scope){
				console.log('SUCESSO',model,response,scope);
				
				
			},
		});
	});

			
			
			
			
			
			
			
			
			
			
			
			
			});
			
			
			
			
			
			
			
			
				
	 
			
			
			
			
			
			
			
			
			
			
			
			
			
			});  //aqui dentro 
			
			
	
	

		});			
			
					
	
			

		
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
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
	
	//TROCAR
	if(typeof app.getUrlParameter('PidPalestra') !== 'undefined'){ 
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
		//document.location.href = base+'certificados-emitidos/';
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
									$('#progresso').addClass('animated fadeOutLeft').delay(450).queue(function(){ $(this).removeClass('animated fadeOutLeft fadeInUp').addClass('hide'); });
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
					
					$('#progresso').removeClass('hide').addClass('animated fadeInRight');
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