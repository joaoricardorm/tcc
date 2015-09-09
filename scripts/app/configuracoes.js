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
 * View logic for Configuracoes
 */

/**
 * application logic specific to the Configuracao listing page
 */

var page = {
    configuracoes: new model.ConfiguracaoCollection(),
    collectionView: null,
    configuracao: null,
    modelView: null,
    isInitialized: false,
    isInitializing: false,
    fetchParams: {filter: '', orderBy: '', orderDesc: '', page: 1},
    fetchInProgress: false,
    dialogIsOpen: false,
    /**
     *
     */
    init: function() {
        // ensure initialization only occurs once
        if (page.isInitialized || page.isInitializing)
            return;
        page.isInitializing = true;

        if (!$.isReady && console)
            console.warn('page was initialized before dom is ready.  views may not render properly.');

        // Em vez de gerar uma nova configuracao, edita a de id 1
        $("#newConfiguracaoButton").click(function(e) {
            e.preventDefault();
            var m = page.configuracoes.get(1);
            page.showDetailDialog(m);
        });

        // em vez de gerar uma nova configuracao o sistema edita ela, ja que e somente uma
        // $("#editConfiguracaoButton").click(function(e) {
        // alert('at');
        // e.preventDefault();
        // var m = page.configuracaos.get(1);
        // });
		
        // let the page know when the dialog is open
        $('#configuracaoDetailDialog').on('show', function() {
            page.dialogIsOpen = true;
        });

        // when the model dialog is closed, let page know and reset the model view
        $('#configuracaoDetailDialog').on('hidden', function() {
            $('#modelAlert').html('');
            page.dialogIsOpen = false;
        });
		
        // save the model when the save button is clicked
        $("#saveConfiguracaoButton").click(function(e) {
            e.preventDefault();
            
			//Somente realiza upload da nova imagem caso o usuario clique em alterar imagem
			if($('#ckbAltImagem').is(':checked'))
				uploader.submit();
			else
				page.updateModel();
        });

        // initialize the collection view
        this.collectionView = new view.CollectionView({
            el: $("#configuracaoCollectionContainer"),
            templateEl: $("#configuracaoCollectionTemplate"),
            collection: page.configuracoes
        });

        /* nao existe necessidade de busca, pois so existe uma configuracao
         // initialize the search filter
         $('#filter').change(function(obj) {
         page.fetchParams.filter = $('#filter').val();
         page.fetchParams.page = 1;
         page.fetchConfiguracoes(page.fetchParams);
         });
         */

        // make the rows clickable ('rendered' is a custom event, not a standard backbone event)
        this.collectionView.on('rendered', function() {
			
			// Adiciona o atributo data-title nas tr da tabela para responsividade
			$( "table.collection tbody td" ).each(function(index){
				titulo = $( "table.collection thead th").eq(index).text();
				$(this).attr('data-title',titulo);
			}); 
	
            // attach click handler to the table rows for editing
            $('table.collection tbody tr').click(function(e) {
                e.preventDefault();
                var m = page.configuracoes.get(this.id);
                page.showDetailDialog(m);
            });

            /* Nao existe necessidade de sortear nem paginar, pois so existe uma configuracao
             
             // make the headers clickable for sorting
             $('table.collection thead tr th').click(function(e) {
             e.preventDefault();
             var prop = this.id.replace('header_','');
             
             // toggle the ascending/descending before we change the sort prop
             page.fetchParams.orderDesc = (prop == page.fetchParams.orderBy && !page.fetchParams.orderDesc) ? '1' : '';
             page.fetchParams.orderBy = prop;
             page.fetchParams.page = 1;
             page.fetchConfiguracoes(page.fetchParams);
             });
             
             // attach click handlers to the pagination controls
             $('.pageButton').click(function(e) {
             e.preventDefault();
             page.fetchParams.page = this.id.substr(5);
             page.fetchConfiguracoes(page.fetchParams);
             });
             
             */

            page.isInitialized = true;
            page.isInitializing = false;
        });

        // backbone docs recommend bootstrapping data on initial page load, but we live by our own rules!
        this.fetchConfiguracoes({page: 1});

        // initialize the model view
        this.modelView = new view.ModelView({
            el: $("#configuracaoModelContainer")
        });

        // tell the model view where it's template is located
        this.modelView.templateEl = $("#configuracaoModelTemplate");

        if (model.longPollDuration > 0) {
            setInterval(function() {

                if (!page.dialogIsOpen) {
                    page.fetchConfiguracoes(page.fetchParams, true);
                }

            }, model.longPollDuration);
        }
    },
    /**
     * Fetch the collection data from the server
     * @param object params passed through to collection.fetch
     * @param bool true to hide the loading animation
     */
    fetchConfiguracoes: function(params, hideLoader) {
        // persist the params so that paging/sorting/filtering will play together nicely
        page.fetchParams = params;

        if (page.fetchInProgress) {
            if (console)
                console.log('supressing fetch because it is already in progress');
        }

        page.fetchInProgress = true;

        if (!hideLoader)
            app.showProgress('loader');

        page.configuracoes.fetch({
            data: params,
            success: function() {			
                if (page.configuracoes.collectionHasChanged) {					
                    // TODO: add any logic necessary if the collection has changed
                    // the sync event will trigger the view to re-render
                }

                app.hideProgress('loader');
                page.fetchInProgress = false;
            },
            error: function(m, r) {
                app.appendAlert(app.getErrorMessage(r), 'alert-error', 0, 'collectionAlert');
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
        $('#configuracaoDetailDialog').modal({backdrop: 'static', show: true});

        // if a model was specified then that means a user is editing an existing record
        // if not, then the user is creating a new record
        page.configuracao = m ? m : new model.ConfiguracaoModel();

        page.modelView.model = page.configuracao;

        if (page.configuracao.id == null || page.configuracao.id == '') {
            // this is a new record, there is no need to contact the server
            page.renderModelView(false);
        } else {
            app.showProgress('modelLoader');

            // fetch the model from the server so we are not updating stale data
            page.configuracao.fetch({
                success: function() {
                    // data returned from the server.  render the model view
                    page.renderModelView(true);

                    image_uploader();
					
					$('.modal .modal-footer .btn:first').focus();

                },
                error: function(m, r) {
                    app.appendAlert(app.getErrorMessage(r), 'alert-error', 0, 'modelAlert');
                    app.hideProgress('modelLoader');
                }

            });
        }
    },
    /**
     * Render the model template in the popup
     * @param bool show the delete button
     */
    renderModelView: function(showDeleteButton) {
        page.modelView.render();

        app.hideProgress('modelLoader');

        // initialize any special controls
        try {
            $('.date-picker')
                    .datepicker()
                    .on('changeDate', function(ev) {
                        $('.date-picker').datepicker('hide');
                    });
        } catch (error) {
            // this happens if the datepicker input.value isn't a valid date
            if (console)
                console.log('datepicker error: ' + error.message);
        }

        $('.timepicker-default').timepicker({defaultTime: 'value'});
		
		
		//Habilita ou desabilita alteracao da imagem de logotipo
		$('#UploadAltImagem, #ckbAltImagem').hide();
		$('#btnAltImagem').toggle(function(e){
			$('#UploadAltImagem').slideDown('fast');
			$('#ckbAltImagem').prop('checked', true);
			$(this).html('<i class="icon-remove"></i> Cancelar alteração da imagem').removeClass('btn-primary');
		},function(){
			$('#UploadAltImagem').slideUp('fast');
			$('#ckbAltImagem').prop('checked', false);
			$(this).html('<i class="icon-picture"></i> Alterar imagem').addClass('btn-primary');
		});
		
		//mascara para telefone e cnpj
		$("#telefone").mask("(99) 9999-9999",{placeholder:"_"});
		$("#cnpj").mask("99.999.999/9999-99",{placeholder:"_"});

        if (showDeleteButton) {
            // attach click handlers to the delete buttons

            $('#deleteConfiguracaoButton').click(function(e) {
                e.preventDefault();
                $('#confirmDeleteConfiguracaoContainer').show('fast');
            });

            $('#cancelDeleteConfiguracaoButton').click(function(e) {
                e.preventDefault();				
                $('#confirmDeleteConfiguracaoContainer').hide();
            });

            $('#confirmDeleteConfiguracaoButton').click(function(e) {
                e.preventDefault();
                page.deleteModel();
            });

        } else {
            // no point in initializing the click handlers if we don't show the button
            $('#deleteConfiguracaoButtonContainer').hide();
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
        var isNew = page.configuracao.isNew();

        app.showProgress('modelLoader');                   

        //substitui o campo padrao do framework pela imagem do upload
        page.configuracao.save({
            'nomeInstituicao': $('input#nomeInstituicao').val(),
            'imagemLogo': imgSalva,
            'cnpj': $('input#cnpj').val(),
            'telefone': $('input#telefone').val()
        }, {
            wait: true,
            success: function() {

                //Esconde Modal
                $('#configuracaoDetailDialog').modal('hide');

                setTimeout("app.appendAlert('A configuração foi " + (isNew ? "inserida" : "editada") + " com sucesso','alert-success',3000,'collectionAlert')", 500);
                app.hideProgress('modelLoader');

                // if the collection was initally new then we need to add it to the collection now
                if (isNew) {
                    page.configuracoes.add(page.configuracao)
                }

                if (model.reloadCollectionOnModelUpdate) {
                    // re-fetch and render the collection after the model has been updated
                    page.fetchConfiguracoes(page.fetchParams, true);
                }
            },
            error: function(model, response, scope) {

                app.hideProgress('modelLoader');

                app.appendAlert(app.getErrorMessage(response), 'alert-error', 0, 'modelAlert');

                try {
                    var json = $.parseJSON(response.responseText);

                    if (json.errors) {
                        $.each(json.errors, function(key, value) {
                            $('#' + key + 'InputContainer').addClass('error');
                            $('#' + key + 'InputContainer span.help-inline').html(value);
                            $('#' + key + 'InputContainer span.help-inline').show();
                        });
                    }
                } catch (e2) {
                    if (console)
                        console.log('error parsing server response: ' + e2.message);
                }
            }
        });
    },
    /**
     * delete the model that is currently displayed in the dialog
     */
    deleteModel: function() {
        // reset any previous errors
        $('#modelAlert').html('');

        app.showProgress('modelLoader');

        page.configuracao.destroy({
            wait: true,
            success: function() {
                $('#configuracaoDetailDialog').modal('hide');
                setTimeout("app.appendAlert('A Configura��o foi excluida','alert-success',3000,'collectionAlert')", 500);
                app.hideProgress('modelLoader');

                if (model.reloadCollectionOnModelUpdate) {
                    // re-fetch and render the collection after the model has been updated
                    page.fetchConfiguracoes(page.fetchParams, true);
                }
            },
            error: function(model, response, scope) {
                app.appendAlert(app.getErrorMessage(response), 'alert-error', 0, 'modelAlert');
                app.hideProgress('modelLoader');
            }
        });
    }
};

