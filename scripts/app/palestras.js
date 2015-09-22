/**
 * View logic for Palestras
 */

/**
 * application logic specific to the Palestra listing page
 */
 
var page = {
	
	palestras: new model.PalestraCollection(),
	collectionView: null,
	palestra: null,
	modelView: null,
	isInitialized: false,
	isInitializing: false,
	
	fetchParams: { filter: '', orderBy: '', orderDesc: '', page: 1 },
	fetchInProgress: false,
	dialogIsOpen: false,
	
	proprioEvento: 0,

	/**
	 *
	 */
	init: function() {
		// ensure initialization only occurs once
		if (page.isInitialized || page.isInitializing) return;
		page.isInitializing = true;

		if (!$.isReady && console) console.warn('page was initialized before dom is ready.  views may not render properly.');

		// make the new button clickable
		$("#newPalestraButton").click(function(e) {
			e.preventDefault();
			page.showDetailDialog();
		});

		// let the page know when the dialog is open
		$('#palestraDetailDialog').on('show',function() {
			page.dialogIsOpen = true;
		});

		// when the model dialog is closed, let page know and reset the model view
		$('#palestraDetailDialog').on('hidden',function() {
			$('#modelAlert').html('');
			page.dialogIsOpen = false;
		});

		// save the model when the save button is clicked
		$("#savePalestraButton").click(function(e) {
			e.preventDefault();
			page.updateModel();
		});

		// initialize the collection view
		this.collectionView = new view.CollectionView({
			el: $("#palestraCollectionContainer"),
			templateEl: $("#palestraCollectionTemplate"),
			collection: page.palestras
		});

		// initialize the search filter
		$('#filter').change(function(obj) {
			page.fetchParams.filter = $('#filter').val();
			page.fetchParams.page = 1;
			page.fetchPalestras(page.fetchParams);
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
				var m = page.palestras.get(this.id);
				page.showDetailDialog(m);
			});

			// make the headers clickable for sorting
 			$('table.collection thead tr th').click(function(e) {
 				e.preventDefault();
				var prop = this.id.replace('header_','');

				// toggle the ascending/descending before we change the sort prop
				page.fetchParams.orderDesc = (prop == page.fetchParams.orderBy && !page.fetchParams.orderDesc) ? '1' : '';
				page.fetchParams.orderBy = prop;
				page.fetchParams.page = 1;
 				page.fetchPalestras(page.fetchParams);
 			});

			// attach click handlers to the pagination controls
			$('.pageButton').click(function(e) {
				e.preventDefault();
				page.fetchParams.page = this.id.substr(5);
				page.fetchPalestras(page.fetchParams);
			});
			
			page.isInitialized = true;
			page.isInitializing = false;
		});

		// backbone docs recommend bootstrapping data on initial page load, but we live by our own rules!
		this.fetchPalestras({ page: 1, orderBy: 'IdPalestra', orderDesc: 'up' });

		// initialize the model view
		this.modelView = new view.ModelView({
			el: $("#palestraModelContainer")
		});

		// tell the model view where it's template is located
		this.modelView.templateEl = $("#palestraModelTemplate");

		if (model.longPollDuration > 0)	{
			setInterval(function () {

				if (!page.dialogIsOpen)	{
					page.fetchPalestras(page.fetchParams,true);
				}

			}, model.longPollDuration);
		}
	},

	/**
	 * Fetch the collection data from the server
	 * @param object params passed through to collection.fetch
	 * @param bool true to hide the loading animation
	 */
	fetchPalestras: function(params, hideLoader) {
		// persist the params so that paging/sorting/filtering will play together nicely
		page.fetchParams = params;
		
		//Filtra palestras pelo evento
		idEvento = window.location.pathname.match(/evento\/([0-9]+)/);
		if(idEvento){
			page.fetchParams.evento = idEvento[1];
		}

		if (page.fetchInProgress) {
			if (console) console.log('supressing fetch because it is already in progress');
		}

		page.fetchInProgress = true;

		if (!hideLoader) app.showProgress('loader');

		page.palestras.fetch({

			data: params,

			success: function() {
				if (page.palestras.collectionHasChanged) {
					// TODO: add any logic necessary if the collection has changed
					// the sync event will trigger the view to re-render
					
					//pega o atributo proprioEvento da atividade (se for pr�prio s� ter� um elemento)
					if(page.palestras.length > 0){
						page.proprioEvento = page.palestras.models[0].attributes.proprioEvento;
						idProprioEvento = page.palestras.models[0].id;
						
						//ser for proprio evento faz a m�gica de apagar elementos e j� mostrar tela de edi��o
						if(page.proprioEvento == 1){
							$('.new-and-search-container, .remove-on-single').remove();
							
							var m = page.palestras.get(idProprioEvento);
							page.showDetailDialog(m);
						}
					}
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
		$('#palestraDetailDialog').modal({ backdrop: 'static', show: true });

		// if a model was specified then that means a user is editing an existing record
		// if not, then the user is creating a new record
		page.palestra = m ? m : new model.PalestraModel();

		page.modelView.model = page.palestra;

		if (page.palestra.id == null || page.palestra.id == '') {
			// this is a new record, there is no need to contact the server
			page.renderModelView(false);
		} else {
$('#titulo-modal').html('Editar');
$('#icone-acao-modal').removeClass('icon-plus-sign');
			app.showProgress('modelLoader');

			// fetch the model from the server so we are not updating stale data
			page.palestra.fetch({

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

		// populate the dropdown options for idEvento
		// TODO: load only the selected value, then fetch all options when the drop-down is clicked
		var idEventoValues = new model.EventoCollection();
		idEventoValues.fetch({
			success: function(c){
				var dd = $('#idEvento');
				dd.append('<option value=""></option>');
				c.forEach(function(item,index) {
					dd.append(app.getOptionHtml(
						item.get('idEvento'),
						item.get('nome'), // TODO: change fieldname if the dropdown doesn't show the desired column
						page.palestra.get('idEvento') == item.get('idEvento')
					));
				});
				
				if (!app.browserSucks()) {
					dd.combobox();
					$('div.combobox-container + span.help-inline').hide(); // TODO: hack because combobox is making the inline help div have a height
				}
				
				//Corrige posicao do dropdown
				$('.dropdown-toggle').click(function (ele){
					app.dropDownFixPosition($(this).parent(),$(this).parent().find('.dropdown-menu'));
				});
			},
			error: function(collection,response,scope) {
				app.appendAlert(app.getErrorMessage(response), 'alert-error',0,'modelAlert');
			}
		});

		// populate the dropdown options for idModeloCertificado
		// TODO: load only the selected value, then fetch all options when the drop-down is clicked
		var idModeloCertificadoValues = new model.ModeloCertificadoCollection();
		idModeloCertificadoValues.fetch({
			success: function(c){
				var dd = $('#idModeloCertificado');
				dd.append('<option value=""></option>');
				c.forEach(function(item,index) {
					dd.append(app.getOptionHtml(
						item.get('idModeloCertificado'),
						item.get('nome'), // TODO: change fieldname if the dropdown doesn't show the desired column
						page.palestra.get('idModeloCertificado') == item.get('idModeloCertificado')
					));
				});
				
				if (!app.browserSucks()) {
					dd.combobox();
					$('div.combobox-container + span.help-inline').hide(); // TODO: hack because combobox is making the inline help div have a height
				}
				
				//Corrige posicao do dropdown
				$('.dropdown-toggle').click(function (ele){
					app.dropDownFixPosition($(this).parent(),$(this).parent().find('.dropdown-menu'));
				});
				

			},
			error: function(collection,response,scope) {
				app.appendAlert(app.getErrorMessage(response), 'alert-error',0,'modelAlert');
			}
		});


		if (showDeleteButton) {
			// attach click handlers to the delete buttons

			$('#deletePalestraButton').click(function(e) {
				e.preventDefault();
				$('#confirmDeletePalestraContainer').show('fast');
			});

			$('#cancelDeletePalestraButton').click(function(e) {
				e.preventDefault();
				$('#confirmDeletePalestraContainer').hide();
			});

			$('#confirmDeletePalestraButton').click(function(e) {
				e.preventDefault();
				page.deleteModel();
			});

		} else {
			// no point in initializing the click handlers if we don't show the button
			$('#deletePalestraButtonContainer').hide();
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
		var isNew = page.palestra.isNew();

		app.showProgress('modelLoader');

		page.palestra.save({

			'nome': $('input#nome').val(),
			'data': $('input#data').val(),
			'cargaHoraria': $('input#cargaHoraria').val(),
			'proprioEvento': $('input#proprioEvento').val(),
			'idEvento': $('select#idEvento').val(),
			'idModeloCertificado': $('select#idModeloCertificado').val()
		}, {
			wait: true,
			success: function(){
				$('#palestraDetailDialog').modal('hide');
				setTimeout("app.appendAlert('Palestra foi " + (isNew ? "inserido" : "editado") + " com sucesso','alert-success',3000,'collectionAlert')",500);
				app.hideProgress('modelLoader');

				// if the collection was initally new then we need to add it to the collection now
				if (isNew) { page.palestras.add(page.palestra) }

				if (model.reloadCollectionOnModelUpdate) {
					// re-fetch and render the collection after the model has been updated
					page.fetchPalestras(page.fetchParams,true);
				}
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

		page.palestra.destroy({
			wait: true,
			success: function(){
				$('#palestraDetailDialog').modal('hide');
				setTimeout("app.appendAlert('The Palestra record was deleted','alert-success',3000,'collectionAlert')",500);
				app.hideProgress('modelLoader');

				if (model.reloadCollectionOnModelUpdate) {
					// re-fetch and render the collection after the model has been updated
					page.fetchPalestras(page.fetchParams,true);
				}
			},
			error: function(model,response,scope) {
				app.appendAlert(app.getErrorMessage(response), 'alert-error',0,'modelAlert');
				app.hideProgress('modelLoader');
			}
		});
	}
};

