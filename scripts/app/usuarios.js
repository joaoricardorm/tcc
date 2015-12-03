/**
 * View logic for Usuarios
 */

/**
 * application logic specific to the Usuario listing page
 */
 
var page = {

	usuarios: new model.UsuarioCollection(),
	collectionView: null,
	usuario: null,
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

		// make the new button clickable
		$("#newUsuarioButton").click(function(e) {
			e.preventDefault();
			page.showDetailDialog();
		});

		// let the page know when the dialog is open
		$('#usuarioDetailDialog').on('show',function() {
			page.dialogIsOpen = true;			
		});

		// when the model dialog is closed, let page know and reset the model view
		$('#usuarioDetailDialog').on('hidden',function() {
			$('#modelAlert').html('');
			page.dialogIsOpen = false;
		});

		// save the model when the save button is clicked
		$("#saveUsuarioButton").click(function(e) {
			e.preventDefault();
			page.updateModel();
		});

		// initialize the collection view
		this.collectionView = new view.CollectionView({
			el: $("#usuarioCollectionContainer"),
			templateEl: $("#usuarioCollectionTemplate"),
			collection: page.usuarios
		});

		// initialize the search filter
		$('#filter').change(function(obj) {
			page.fetchParams.filter = $('#filter').val();
			page.fetchParams.page = 1;
			page.fetchUsuarios(page.fetchParams);
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
				var m = page.usuarios.get(this.id);
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
 				page.fetchUsuarios(page.fetchParams);
 			});

			// make the headers clickable for sorting
 			$('table.collection thead tr th').click(function(e) {
 				e.preventDefault();
				var prop = this.id.replace('header_','');

				// toggle the ascending/descending before we change the sort prop
				page.fetchParams.orderDesc = (prop == page.fetchParams.orderBy && !page.fetchParams.orderDesc) ? '1' : '';
				page.fetchParams.orderBy = prop;
				page.fetchParams.page = 1;
 				page.fetchUsuarios(page.fetchParams);
 			});

			// attach click handlers to the pagination controls
			$('.pageButton').click(function(e) {
				e.preventDefault();
				page.fetchParams.page = this.id.substr(5);
				page.fetchUsuarios(page.fetchParams);
			});
			
			page.isInitialized = true;
			page.isInitializing = false;
		});

		// backbone docs recommend bootstrapping data on initial page load, but we live by our own rules!
		this.fetchUsuarios({ page: 1, orderBy: 'IdUsuario', orderDesc: 'down' });

		// initialize the model view
		this.modelView = new view.ModelView({
			el: $("#usuarioModelContainer")
		});

		// tell the model view where it's template is located
		this.modelView.templateEl = $("#usuarioModelTemplate");

		if (model.longPollDuration > 0)	{
			setInterval(function () {

				if (!page.dialogIsOpen)	{
					page.fetchUsuarios(page.fetchParams,true);
				}

			}, model.longPollDuration);
		}
	},

	/**
	 * Fetch the collection data from the server
	 * @param object params passed through to collection.fetch
	 * @param bool true to hide the loading animation
	 */
	fetchUsuarios: function(params, hideLoader) {		
		// persist the params so that paging/sorting/filtering will play together nicely
		page.fetchParams = params;

		if (page.fetchInProgress) {
			if (console) console.log('supressing fetch because it is already in progress');
		}

		page.fetchInProgress = true;

		if (!hideLoader) app.showProgress('loader');

		page.usuarios.fetch({

			data: params,

			success: function() {

				if (page.usuarios.collectionHasChanged) {
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
		$('#usuarioDetailDialog').modal({ backdrop: 'static', show: true });

		// if a model was specified then that means a user is editing an existing record
		// if not, then the user is creating a new record
		page.usuario = m ? m : new model.UsuarioModel();

		page.modelView.model = page.usuario;

		if (page.usuario.id == null || page.usuario.id == '') {
			
			$('#titulo-modal').html('Cadastrar');
			$('#icone-acao-modal').addClass('icon-plus-sign');
			
			// this is a new record, there is no need to contact the server
			page.renderModelView(false);
		} else {
			
			$('#titulo-modal').html('Editar');
			$('#icone-acao-modal').removeClass('icon-plus-sign');
			
			app.showProgress('modelLoader');

			// fetch the model from the server so we are not updating stale data
			page.usuario.fetch({

				success: function() {
					// data returned from the server.  render the model view
					page.renderModelView(true);
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
				if(!isMobile){ 	
			setTimeout(function(){
				$('.modal .modal-body input[type=text]').first().click().focus();
			}, 500); 
		}

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

		//altera mensagem de dica sobre a permissao do usuario padrao e administrador
		var textoPadrao = $('#tipoUsuarioInputContainer .help-inline .padrao').text();	
		var textoAdmin = $('#tipoUsuarioInputContainer .help-inline .admin').text();	
		function help_inline_tipoUsuario(){
			texto = $('#tipoUsuario').val() === 'padrao' ? textoPadrao : textoAdmin; 
			$('#tipoUsuarioInputContainer .help-inline').text(texto); 
		}
		//executa on change select
		$('#tipoUsuario').change(function(){ help_inline_tipoUsuario(); });
		//executa ao exibir form
		help_inline_tipoUsuario();
		
		//mostra campo de alterar de senha		
		$('#alterarSenhaUsuarioButton').click(function(e) {
				e.preventDefault();
				$('#alterarSenhaUsuarioContainer').show('fast');
				$('#alterarSenhaUsuarioButton').hide();
		});
		
		if (showDeleteButton) {
			
			//mostra campo de alterar senha somente quando tiver usuario do bd
			$('#alterarSenhaUsuarioContainer').hide();
			$('#alterarSenhaInputContainer').show();
			
			// attach click handlers to the delete buttons

			$('#deleteUsuarioButton').click(function(e) {
				e.preventDefault();
				$('#confirmDeleteUsuarioContainer').show('fast').removeClass('hide');
			});

			$('#cancelDeleteUsuarioButton').click(function(e) {
				e.preventDefault();
				$('#confirmDeleteUsuarioContainer').hide();
			});

			$('#confirmDeleteUsuarioButton').click(function(e) {
				e.preventDefault();
				page.deleteModel();
			});

		} else {
			// no point in initializing the click handlers if we don't show the button
			$('#deleteUsuarioButtonContainer').hide();
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
		var isNew = page.usuario.isNew();

		app.showProgress('modelLoader');

		page.usuario.save({

			'nome': $('input#nome').val(),
			'email': $('input#email').val(),
			'login': $('input#login').val(),
			'senha': $('input#senha').val(),
			'confirmarSenha': $('input#confirmarSenha').val(),
			'tipoUsuario': $('select#tipoUsuario').val()
		}, {
			wait: true,
			success: function(){
				$('#usuarioDetailDialog').modal('hide');
				setTimeout("app.appendAlert('Usuário foi " + (isNew ? "inserido" : "editado") + " com sucesso','alert-success',3000,'collectionAlert')",500);
				app.hideProgress('modelLoader');

				// if the collection was initally new then we need to add it to the collection now
				if (isNew) { page.usuarios.add(page.usuario) }

				if (model.reloadCollectionOnModelUpdate) {
					// re-fetch and render the collection after the model has been updated
					page.fetchUsuarios(page.fetchParams,true);
				}
				
				$('table.collection tr#'+page.usuario.id).addClass('modificou-item');	
				
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
						
						//limpa senha ao ocorrer algum erro
						$('#senha').val('');
						$('#confirmarSenha').val('');
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

		page.usuario.destroy({
			wait: true,
			success: function(){
				$('#usuarioDetailDialog').modal('hide');
				setTimeout("app.appendAlert('O usuário foi excluido','alert-success',3000,'collectionAlert')",500);
				app.hideProgress('modelLoader');

				if (model.reloadCollectionOnModelUpdate) {
					// re-fetch and render the collection after the model has been updated
					page.fetchUsuarios(page.fetchParams,true);
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
};

