/**
 * Editor para cortar e fazer upload de imagens cropit
 */
var uploader;
var imgSalva;

function image_uploader() {
	
	function erroImagem(){
		$('#imagemLogoInputContainer').addClass('error');
		$('#uploadBtn').addClass('btn-danger');
	}
	function removeErroImagem(){
		$('#imagemLogoInputContainer').removeClass('error');
		$('#uploadBtn').removeClass('btn-danger');
	}

    function escapeTags(str) {
        return String(str)
                .replace(/&/g, '&amp;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
    }

    var btn = document.getElementById('uploadBtn'),
            progressBar = document.getElementById('progressBar'),
            progressOuter = document.getElementById('progressOuter'),
            msgBox = document.getElementById('msgBox');
    nomeArquivo = document.getElementById('nomeArquivo');
    previewBox = $('#preview');

    function removePreviewImage() {
        previewBox.attr('src', '');
        previewBox.hide();
    }

    function previewImage(image, $display) {
		
		msgBox.innerHTML = '';
		removeErroImagem();
        removePreviewImage();

        var imageType = /image.*/; //mostrar preview somente se for imagem
	
        if (image.type.match(imageType)) {

            if ((image.size / 1024) <= uploader._opts.maxSize) {

                var oFReader = new FileReader();
                oFReader.readAsDataURL(image);

                oFReader.onload = function(oFREvent) {
                    $display.fadeIn().css('display','block');
                    $display.attr('src', oFREvent.target.result);
                };

            } else {
                msgBox.innerHTML = '';
				uploader.removeCurrent();
				erroImagem();
                removePreviewImage();
                msgBox.innerHTML = 'O arquivo enviado é muito grande. O tamanho máximo permitido é '+Math.floor((uploader._opts.maxSize / 1024),2)+' MB';
            }

        } else {
            msgBox.innerHTML = '';
			uploader.removeCurrent();
			erroImagem();
            removePreviewImage();
            msgBox.innerHTML = 'Tipo de arquivo inválido. Só é possível enviar imagens aqui';
        }
    };
	
    uploader = new ss.SimpleUpload({
        button: btn,
        url: 'scripts/Simple-Ajax-Uploader/examples/basic_example/file_upload.php',
        name: 'uploadfile',
        autoSubmit: false,
        hoverClass: 'hover',
        focusClass: 'focus',
        maxSize: 2048, //2 MB
        allowedExtensions: ["jpg", "jpeg", "png", "gif"],
        responseType: 'json',
        onChange: function(filename, extension, uploadBtn) {
			uploader.removeCurrent();
			removeErroImagem();
            previewImage($('input[name="uploadfile"]')[0].files[0], previewBox);

			//corta o nome do arquivo na exibicao e coloca pontos se for muito grande
            var nome = filename.substr(0, 25);
			if(nome.length > (25-3))
				nome += '...';
			nomeArquivo.value = nome;		
        },
        startXHR: function() {
            progressOuter.style.display = 'block'; // make progress bar visible
            this.setProgressBar(progressBar);
        },
        onSizeError: function(filename, fileSize) {
			uploader.removeCurrent();
            removePreviewImage();
			erroImagem();
			msgBox.innerHTML = 'O arquivo enviado é muito grande. O tamanho máximo permitido é '+Math.floor((uploader._opts.maxSize / 1024),2)+' MB';
        },
        onExtError: function(filename) {
			uploader.removeCurrent();
            removePreviewImage();
			erroImagem();
            msgBox.innerHTML = 'Tipo de arquivo inválido. Só é possível enviar imagens aqui'; // empty the message box
        },
        onSubmit: function() {
            msgBox.innerHTML = ''; // empty the message box
        },
        onComplete: function(filename, response) {
            progressOuter.style.display = 'none'; // hide progress bar when upload is completed

            if (!response) {
                msgBox.innerHTML = 'Não foi possível enviar o arquivo';
                return;
            }

            if (response.success === true) {
                msgBox.innerHTML = '<strong>' + escapeTags(response.img) + '</strong>' + ' foi enviado.';
                imgSalva = escapeTags(response.img);

                //Só salva o model se a imagem tiver sido enviada
                page.updateModel();

                //para recarrega-lo apos o upload
				uploader.removeCurrent();
				//uploader.clearQueue();
				uploader.destroy();
				uploader.enable();
				nomeArquivo.value = 'Nenhum arquivo selecionado';	
				
				//Fecha o modal ao completar upload
				$('.modal').modal('hide');

            } else {
                if (response.msg) {
                    msgBox.innerHTML = escapeTags(response.msg);

                } else {
                    msgBox.innerHTML = 'Ocorreu um erro e não foi possível enviar o arquivo';
                }
            }
        },
        onError: function() {
			uploader.removeCurrent();
            removePreviewImage();
			erroImagem();
            progressOuter.style.display = 'none';
            msgBox.innerHTML = 'Não foi possível enviar o arquivo';
        }
    });

}

/**
 * View logic for Palestrantes
 */

/**
 * application logic specific to the Palestrante listing page
 */
var page = {

	palestrantes: new model.PalestranteCollection(),
	collectionView: null,
	palestrante: null,
	palestra: null,
	palestraPalestrante: null,
	modelView: null,
	isInitialized: false,
	isInitializing: false,

	fetchParams: { filter: '', orderBy: '', orderDesc: '', page: 1 },
	fetchInProgress: false,
	dialogIsOpen: false,

	/**
	 *
	 */
	init: function() {		
		
		// ensure initialization only occurs once
		if (page.isInitialized || page.isInitializing) return;
		page.isInitializing = true;

		if (!$.isReady && console) console.warn('page was initialized before dom is ready.  views may not render properly.');	
		
		//RETORNA A PALESTRA ATUAL VIA URL
		
		idPalestra = window.location.pathname.match(/atividade\/([0-9]+)/);

		if(idPalestra){
			
			var palestraCollection = new model.PalestraCollection();	
			
			palestraCollection.fetch({
				data : {
					'idPalestra': idPalestra[1]
				},
				success: function(palestra, response) {
					page.palestra = new model.PalestraModel();
					page.palestra = palestra.first();
					
					
					//ABRE O PALESTRANTE ATUAL SE TIVER NA URL
					idPalestrante = window.location.pathname.match(/palestrantes\/([0-9]+)/);
					
					if(idPalestrante !== null){
						var palestranteURL = new model.PalestranteCollection();
						
						palestranteURL.fetch({
							data: {
								idPalestrante: idPalestrante[1]
							},
							success: function(pa) {								
								if(idPalestrante){
									var m = pa.get(idPalestrante[1]);
									page.showDetailDialog(m);
								}				
							},
							error: function(m, response) {
								console.log('Erro ao obter a atividade pelo Id na URL');
								console.log(response);
							}
						});		
					}	
					
					
				},
				error: function(model, response) {
					console.log('Erro ao buscar a palestra da url');
					console.log(response);
				}
			});	
				
		}
		
		
		
		
		
		
		
		// make the new button clickable
		$("#newPalestranteButton").click(function(e) {
			e.preventDefault();
			page.showDetailDialog();
		});

		// let the page know when the dialog is open
		$('#palestranteDetailDialog').on('show',function() {
			page.dialogIsOpen = true;
		});

		// when the model dialog is closed, let page know and reset the model view
		$('#palestranteDetailDialog').on('hidden',function() {
			$('#modelAlert').html('');
			page.dialogIsOpen = false;
			
			window.history.pushState('Object', 'Palestrantes', base+'atividade/'+page.palestra.get('idPalestra')+'/'+app.parseURL(page.palestra.get('nome'))+'/palestrantes/');
			
			//RECARREGA A PAGINA POR CAUSA DO UPLOADER
			window.location.reload();
		});

		// save the model when the save button is clicked
		$("#savePalestranteButton").click(function(e) {
			e.preventDefault();

			//Somente realiza upload da nova imagem caso o usuario clique em alterar imagem ou se houver uma imagem no preview no caso de novo registro
			// if($('#ckbAltImagem').is(':checked') || $('.up-image-preview').attr('src') !== '#')
				// uploader.submit();
			// else
				// page.updateModel();
			
			page.updateModel();
		});

		// initialize the collection view
		this.collectionView = new view.CollectionView({
			el: $("#palestranteCollectionContainer"),
			templateEl: $("#palestranteCollectionTemplate"),
			collection: page.palestrantes
		});

		// initialize the search filter
		$('#filter').change(function(obj) {
			page.fetchParams.filter = $('#filter').val();
			page.fetchParams.page = 1;
			page.fetchPalestrantes(page.fetchParams);
		});
		
		// make the rows clickable ('rendered' is a custom event, not a standard backbone event)
		this.collectionView.on('rendered',function(){
			
			// Adiciona o atributo data-title nas tr da tabela para responsividade
			$( "table.collection tbody td" ).each(function(index){
				total = $( "table.collection thead th").length;
				titulo = $( "table.collection thead th").eq(index % total).text();
				
				$(this).attr('data-title',titulo); 
			});
			
			// attach click handler to the table rows for editing
			$('table.collection tbody tr').click(function(e) {
				e.preventDefault();
				var m = page.palestrantes.get(this.id);
				page.showDetailDialog(m);
			});

			//Ordenar pelo cadastro
			$('.ordemCadastro').click(function(e) {
 				e.preventDefault();
				var prop = this.id.replace('ordemCadastro_','');

				// toggle the ascending/descending before we change the sort prop
				page.fetchParams.orderDesc = (prop == page.fetchParams.orderBy && !page.fetchParams.orderDesc) ? '1' : '';
				page.fetchParams.orderBy = prop;
				page.fetchParams.page = 1;
 				page.fetchPalestrantes(page.fetchParams);
 			});
			
			// make the headers clickable for sorting
 			$('table.collection thead tr th').click(function(e) {
 				e.preventDefault();
				var prop = this.id.replace('header_','');

				// toggle the ascending/descending before we change the sort prop
				page.fetchParams.orderDesc = (prop == page.fetchParams.orderBy && !page.fetchParams.orderDesc) ? '1' : '';
				page.fetchParams.orderBy = prop;
				
				page.fetchParams.page = 1;
 				page.fetchPalestrantes(page.fetchParams);
 			});

			// attach click handlers to the pagination controls
			$('.pageButton').click(function(e) {
				e.preventDefault();
				page.fetchParams.page = this.id.substr(5);
				page.fetchPalestrantes(page.fetchParams);
			});
			
			page.isInitialized = true;
			page.isInitializing = false;
		});

		// backbone docs recommend bootstrapping data on initial page load, but we live by our own rules!
		this.fetchPalestrantes({ page: 1, orderBy: 'IdPalestrante', orderDesc: '1' });

		// initialize the model view
		this.modelView = new view.ModelView({
			el: $("#palestranteModelContainer")
		});

		// tell the model view where it's template is located
		this.modelView.templateEl = $("#palestranteModelTemplate");

		if (model.longPollDuration > 0)	{
			setInterval(function () {

				if (!page.dialogIsOpen)	{
					page.fetchPalestrantes(page.fetchParams,true);
				}

			}, model.longPollDuration);
		}
	},

	/**
	 * Fetch the collection data from the server
	 * @param object params passed through to collection.fetch
	 * @param bool true to hide the loading animation
	 */
	fetchPalestrantes: function(params, hideLoader) {
		// persist the params so that paging/sorting/filtering will play together nicely
		page.fetchParams = params;		
		
		
		//Filtra pelo id da palestra na URL
		if(idPalestra){
			page.fetchParams.idPalestra = idPalestra[1];
		}
		

		if (page.fetchInProgress) {
			if (console) console.log('supressing fetch because it is already in progress');
		}

		page.fetchInProgress = true;

		if (!hideLoader) app.showProgress('loader');
			
		page.palestrantes.fetch({

			data: params,

			success: function() {
				
				//Abre janela para cria? de novo palestrante se tiver na url sem ser parametro e sim l?a url do _app_config.php
				novoURL = /\/novo\/$/.test(window.location.pathname);

				if(novoURL === true){
					page.showDetailDialog();
				}

				if (page.palestrantes.collectionHasChanged) {
					// TODO: add any logic necessary if the collection has changed
					// the sync event will trigger the view to re-render
				}

				app.hideProgress('loader');
				page.fetchInProgress = false;
			},

			error: function(m, r) {
				app.appendAlert(app.getErrorMessage(r), 'alert-error',0,'collectionAlert');
				app.hideProgress('loader');
				page.fetchInProgress = false;
			}

		});
	},

	/**
	 * show the dialog for editing a model
	 * @param model
	 */
	showDetailDialog: function(m) {

		// show the modal dialog
		$('#palestranteDetailDialog').modal({ backdrop: 'static', show: true });

		// if a model was specified then that means a user is editing an existing record
		// if not, then the user is creating a new record
		page.palestrante = m ? m : new model.PalestranteModel();

		page.modelView.model = page.palestrante;

		if (page.palestrante.id == null || page.palestrante.id == '') {
			
			$('#titulo-modal').html('Cadastrar');
			$('#icone-acao-modal').addClass('icon-plus-sign');
			
			// this is a new record, there is no need to contact the server
			page.renderModelView(false);
			
			image_uploader();
			
		} else {
$('#titulo-modal').html('Editar');
$('#icone-acao-modal').removeClass('icon-plus-sign');
			app.showProgress('modelLoader');

			// fetch the model from the server so we are not updating stale data
			page.palestrante.fetch({

				success: function(palestrante) {
					// data returned from the server.  render the model view
					page.renderModelView(true);					 
					
					image_uploader();
					
					// adiciona a url do palestrante atual
					window.history.pushState('Object', 'Palestrante '+palestrante.get('nome'), base+'atividade/'+page.palestra.get('idPalestra')+'/'+app.parseURL(page.palestra.get('nome'))+'/palestrantes/'+palestrante.get('idPalestrante')+'/'+app.parseURL(palestrante.get('nome'))+'/');
				
				},

				error: function(m, r) {
					app.appendAlert(app.getErrorMessage(r), 'alert-error',0,'modelAlert');
					app.hideProgress('modelLoader');
				}

			});
		}

	},

	/**
	 * Render the model template in the popup
	 * @param bool show the delete button
	 */
	renderModelView: function(showDeleteButton)	{
		page.modelView.render();

setTimeout(function(){
	$('.modal .modal-body input[type=text]').first().click().focus();
}, 500);

		app.hideProgress('modelLoader');

		// initialize any special controls
		try {
			$('.date-picker')
				.datepicker({ language: 'pt-BR' })
				.on('changeDate', function(ev){
					$('.date-picker').datepicker('hide');
				});
		} catch (error) {
			// this happens if the datepicker input.value isn't a valid date
			if (console) console.log('datepicker error: '+error.message);
		}
		
		$('.timepicker-default').timepicker({ defaultTime: 'value' });

		
		//acessibilidade para botao de upload
		$('#uploadBtn').keypress(function(event){
			var keycode = (event.keyCode ? event.keyCode : event.which);
			if(keycode == '13' || keycode == '32'){
				$('input[name="uploadfile"]').click();
			}
		});
		
		console.log(page.palestrante.isNew());
		
		//Habilita ou desabilita alteracao da imagem de logotipo
		if(!page.palestrante.isNew()){
			$('#UploadAltImagem, #ckbAltImagem').hide();
		} else {
			$('#lblAltImagem').hide();
		}
		$('#btnAltImagem').toggle(function(e){
			$('#UploadAltImagem').slideDown('fast');
			
			$('#ckbAltImagem').prop('checked', true);
			$(this).html('<i class="icon-remove"></i> Cancelar alteração da imagem').removeClass('btn-primary');
			
			$('.thumb-imagem-alterar').hide();
			
		},function(){
			$('#UploadAltImagem').slideUp('fast');
			$('#ckbAltImagem').prop('checked', false);
			$(this).html('<i class="icon-picture"></i> Alterar imagem').addClass('btn-primary');
			
			$('.thumb-imagem-alterar').show();
		});
		
		
		//mascara para telefone e cnpj
		$("#cpf").mask("999.999.999-99",{placeholder:"_", autoclear: false });
		

		if (showDeleteButton) {
			// attach click handlers to the delete buttons

			$('#deletePalestranteButton').click(function(e) {
				e.preventDefault();
				$('#confirmDeletePalestranteContainer').show('fast');
			});

			$('#cancelDeletePalestranteButton').click(function(e) {
				e.preventDefault();
				$('#confirmDeletePalestranteContainer').hide();
			});

			$('#confirmDeletePalestranteButton').click(function(e) {
				e.preventDefault();
				page.deleteModel();
			});

		} else {
			// no point in initializing the click handlers if we don't show the button
			$('#deletePalestranteButtonContainer').hide();
		}
	},

	/**
	 * update the model that is currently displayed in the dialog
	 */
	updateModel: function() {
		// reset any previous errors
		$('#modelAlert').html('');
		$('.control-group').removeClass('error');
		$('.help-inline').html('');

		// if this is new then on success we need to add it to the collection
		var isNew = page.palestrante.isNew();

		app.showProgress('modelLoader');

		page.palestrante.save({

			'nome': $('input#nome').val(),
			'email': $('input#email').val(),
			'cpf': $('input#cpf').val(),
			'cargo': $('input#cargo').val(),
			'imagemAssinatura': imgSalva
		}, {
			wait: true,
			success: function(palestrante){
				
				//SE TIVER IMAGEM UPLOADER ele faz o upload, senão só esconde a div
				if($('.up-image-preview').attr('src') === '#')
					$('#palestranteDetailDialog').modal('hide');
				else
					uploader.submit();	
				
				//$('#palestranteDetailDialog').modal('hide');
				setTimeout("app.appendAlert('Palestrante foi " + (isNew ? "inserido" : "editado") + " com sucesso','alert-success',3000,'collectionAlert')",500);
				app.hideProgress('modelLoader');				

				// if the collection was initally new then we need to add it to the collection now
				if (isNew) { 
				
					//Associa o palestrante a palestra se ele for novo
					var palestraPalestrante = new model.PalestraPalestranteModel();
					
					palestraPalestrante.save({
							'idPalestra': page.palestra.get('idPalestra'),
							'idPalestrante': page.palestrante.get('idPalestrante'),
							'idCertificado': 0
						}, {
						wait: true,
						success: function(){
							console.log('Associou palestrante a palestra');							
						},
						error: function(model,response,scope){
							console.log('Erro ao associar palestrante a palestra');
							console.log(response);
						}
					});
					
					//Acrescenta na listagem
					page.palestrantes.add(page.palestrante);

				}

				if (model.reloadCollectionOnModelUpdate) {
					// re-fetch and render the collection after the model has been updated
					page.fetchPalestrantes(page.fetchParams,true);
				}
				
				$('table.collection tr#'+page.palestrante.id).addClass('modificou-item');	
				
		},
			error: function(model,response,scope){

				app.hideProgress('modelLoader');

				app.appendAlert(app.getErrorMessage(response), 'alert-error',0,'modelAlert');

				try {
					var json = $.parseJSON(response.responseText);

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
	},

	/**
	 * delete the model that is currently displayed in the dialog
	 */
	deleteModel: function()	{
		// reset any previous errors
		$('#modelAlert').html('');

		app.showProgress('modelLoader');
		
		
		
		
		
		
		
		
		
		
		
		
		//EXCLUI A RELAÇÃO COM PALESTRANTES ASSOCIADOS ANTES DE APAGAR A PALESTRA
						
			var palestranteCollection = new model.PalestraPalestranteCollection();	
				
			palestranteCollection.fetch({
				data : {
					'idPalestrante': page.palestrante.get('idPalestrante'),
				},
				success: function(c, response) {
					
					//VALIDA SE HÁ CERTIFICADO EMITIDO PARA O PALESTRANTE, SE HOUVER O SISTEMA NÃO DEIXA EXCLUIR
					var temCertificado = false;
					c.some(function(pal){
						if(parseInt(pal.get('idCertificado')) > 0){
							temCertificado = true;
							return false;
						}
					});									
								
					
					//REMOVE AS RELAÇÕES COM O PALESTRANTE, caso possua palestrantes, caso não possua certificado, senão joga um erro
	
					if(c.length > 0){
						var qtd = 1;
						c.forEach(function(pal){
							
							if(temCertificado === false){
							
								page.palestraPalestrante = new model.PalestraPalestranteModel();
								page.palestraPalestrante.id = pal.id;
								
								page.palestraPalestrante.destroy();
						
							}
							
							if(qtd === c.length){
								console.log('Vai remover o palestrante');
								removerPalestrante();
							}
							qtd++;
							
						});
					} else {
						console.log('Vai remover o palestrante');
						removerPalestrante();
					}
				
				},
				error: function(model, response) {
					console.log('Erro ao remover a relação do palestrante');
					console.log(response);
					page.excluir = false;
				}
			});	
		
		
		
		// REMOVE A PALESTRANTE
		function removerPalestrante(){

			page.palestrante.destroy({
				wait: true,
				success: function(){
					$('#palestranteDetailDialog').modal('hide');
					setTimeout("app.appendAlert('O palestrante foi excluido','alert-success',3000,'collectionAlert')",500);
					app.hideProgress('modelLoader');

					if (model.reloadCollectionOnModelUpdate) {
						// re-fetch and render the collection after the model has been updated
						page.fetchPalestrantes(page.fetchParams,true);
					}
				},
				error: function(model,response,scope) {
					app.appendAlert(app.getErrorMessage(response), 'alert-error',0,'modelAlert');
					app.hideProgress('modelLoader');
					
					$('.modal').addClass('animated shake').delay(1000).queue(function(){
						$(this).removeClass("animated shake").dequeue();
					});
				}
			});
		
		}
		
		
	}
};

