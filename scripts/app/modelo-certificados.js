$(document).ready(function(){
			
	
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
		
		if(!$(el.data('target')).hasClass($(this).data(options.commandRole))){
			$(this).addClass('btn-primary');
			$(el.data('target')).addClass($(this).data(options.commandRole));
		} else {
			if($(this).attr('data-dbitem') === true){
				$(this).attr('data-dbitem',false);
			} else {
				$(this).removeClass('btn-primary');
				$(el.data('target')).removeClass($(this).data(options.commandRole));
			} 
		}
			
				
			if($('#btnOrientacao').parent().hasClass('A4portrait'))
				$('#btnOrientacao').addClass('btn-primary')
	});						
	
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
					}, 2000);
				});
			}
	
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
					
				
					//remove as classes adicionais para ficar no Id
					var idDbItem = $(this).attr('class').replace(/(dbitem|tagit-choice|ui-sortable-handle|\ )/g,'');
					if($(this).hasClass('dbitem'))
						item = '<span id="'+idDbItem+'" class="dbItemCertificado '+$(this).attr('class')+'" style="color: '+corTextoDinamico+'">'+item+'</span>';
					
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
				
				var orientacao = '';
				orientacao = $('.containerPDF').attr('class').match(/.*?A4portrait/i);
				console.log('OOO',orientacao);
				if(typeof orientacao !== 'undefined' && orientacao != null)
					$('.ToolBarGeral a[data-edit="A4portrait"]').click();

				setTimeout(function(){
					if(typeof bold !== 'undefined' && bold != null)
						$('.ToolBarGeral a[data-edit="bold"]').addClass('btn-primary');
					
					if(typeof italic !== 'undefined' && italic != null)
						$('.ToolBarGeral a[data-edit="italic"]').addClass('btn-primary');
					
					
					if($('.containerPDF').hasClass('A4portrait'))
						$('.containerA4preview, #previewCertificado').addClass('A4portrait');
					
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
			
			
			
			
			
			
			
			
			
			
			
		
			//PREENCHE OS DADOS NO BANCO SE JÁ EXISTIREM OU NÃO
			
			var textoTags; 
			var textoPalestranteBD;
			var textoParticipanteBD;
			
			
		function updateTextoTags(){
			
			if(ehPalestrante != ''){
			 
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
				
			  console.log('AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA');
			  console.log(modeloCertificado.rows[0].textoParticipante);
				
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
			
		}
		updateTextoTags();
			
			if(modeloCertificado.rows[0].elementos != '')
				$('#previewCertificado').html(modeloCertificado.rows[0].elementos);
			else
				$('#previewCertificado').html(certPadrao.elementos);
			
			function previewCertificado(){
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
					
					console.log('TPARTBD-----',textoParticipanteBD,'TPALBD-----',textoPalestranteBD);
					
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
							
							
							// REDIRECIONA PARA O CERTIFICADO DO PALESTRANTE, SE ESTIVER NO DO PARTICIPANTE, OU PARA A PAGINA OBTER CERTIFICADOS
							// if(ehPalestrante === ''){
								// document.location.href = document.location.href+'&palestrante=true'; 
							// } else {
								// document.location.href = base+'emitir-certificados/obter/'+app.getUrlParameter('idPalestra')+'?idPalestra='+app.getUrlParameter('idPalestra');

								// $('#frmRedirPalestrante').each('input',function(){
									// console.log($(this).val());
								// });
								// $('#frmRedirComParticipantes').submit();
							// }
							
							$('#frmRedirComParticipantes').submit();
							
							
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
			
			
			
			
			
			
			
			
	$('.tagit-choice').attr('title', 'Reorganizar ou remover esse trecho de texto do certificado');
			
			
	
	$( "#sortable1, #sortable2" ).sortable({
		connectWith: ".connectedSortable",
		placeholder : "highlight",
		items: "li:not(.tagit-input)",
		stop: function(event, ui) { previewCertificado(); console.log('TEXTAOTAGAO',textoTags); },
		start: function(e, ui ){
			 ui.placeholder.width(ui.helper.outerWidth());
		},
		receive: function(event, ui) { 			
console.log('ui',ui,'event',event);
			if(this.id == 'sortable1'){ //nao vai # pois nao eh jquery
				
				$(this).tagit("add", $(ui.draggable).text());
		
				$(ui.draggable).remove();
				console.log('ui',ui,'event',event);
				
					$('.tagit-choice').attr('title', 'Reorganizar ou remover esse trecho de texto do certificado');
			
				
			} else { //SE FOR DO MENU
				$(ui.draggable).remove();
				$('#sortable1').tagit("remove", ui.item.context.innerText);
				
				$(ui.draggable).attr('title', 'Adicionar ao texto do certificado de participante').find('.tagit-close').text('+');
			}
		}
	}).disableSelection();
	
	
	
		
		
	
	
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
			
					
	
			

		
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
});