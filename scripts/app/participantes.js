/**
 * View logic for Participantes
 */

/**
 * application logic specific to the Participante listing page
 */
var page = {

	participantes: new model.ParticipanteCollection(),
	collectionView: null,
	participante: null,
	modelView: null,
	isInitialized: false,
	isInitializing: false,

	fetchParams: { filter: '', orderBy: '', orderDesc: '', page: 1 },
	fetchInProgress: false,
	dialogIsOpen: false,
	
	SearchTableById: '',

	/**
	 *
	 */
	init: function() {
		// ensure initialization only occurs once
		if (page.isInitialized || page.isInitializing) return;
		page.isInitializing = true;

		if (!$.isReady && console) console.warn('page was initialized before dom is ready.  views may not render properly.');

		// make the new button clickable
		$("#newParticipanteButton").click(function(e) {
			e.preventDefault();
			page.showDetailDialog();
		});

		// let the page know when the dialog is open
		$('#participanteDetailDialog').on('show',function() {
			page.dialogIsOpen = true;
		});

		// when the model dialog is closed, let page know and reset the model view
		$('#participanteDetailDialog').on('hidden',function() {
			$('#modelAlert').html('');
			page.dialogIsOpen = false;
			
			//RECARREGA A PAGINA POR CAUSA DO HANDSONTABLE
			setTimeout(function(){
				window.location.reload();
			}, 1000);
		});

		// save the model when the save button is clicked
		$("#saveParticipanteButton").click(function(e) {
			e.preventDefault();
			page.updateModel();
		});

		// initialize the collection view
		this.collectionView = new view.CollectionView({
			el: $("#participanteCollectionContainer"),
			templateEl: $("#participanteCollectionTemplate"),
			collection: page.participantes
		});

		// initialize the search filter
		$('#filter').change(function(obj) {
			page.fetchParams.filter = $('#filter').val();
			page.fetchParams.page = 1;
			page.fetchParticipantes(page.fetchParams);
		});
		
		// make the rows clickable ('rendered' is a custom event, not a standard backbone event)
		this.collectionView.on('rendered',function(){

			// attach click handler to the table rows for editing
			$('table.collection tbody tr').click(function(e) {
				e.preventDefault();
				var m = page.participantes.get(this.id);
				page.showDetailDialog(m);
				
				//acha o participante clicado na handsontable
				page.SearchTableById = this.id;		
			});

			// make the headers clickable for sorting
 			$('table.collection thead tr th').click(function(e) {
 				e.preventDefault();
				var prop = this.id.replace('header_','');

				// toggle the ascending/descending before we change the sort prop
				page.fetchParams.orderDesc = (prop == page.fetchParams.orderBy && !page.fetchParams.orderDesc) ? '1' : '';
				page.fetchParams.orderBy = prop;
				page.fetchParams.page = 1;
 				page.fetchParticipantes(page.fetchParams);
 			});

			// attach click handlers to the pagination controls
			$('.pageButton').click(function(e) {
				e.preventDefault();
				page.fetchParams.page = this.id.substr(5);
				page.fetchParticipantes(page.fetchParams);
			});
			
			page.isInitialized = true;
			page.isInitializing = false;
		});

		// backbone docs recommend bootstrapping data on initial page load, but we live by our own rules!
		this.fetchParticipantes({ page: 1 });

		// initialize the model view
		this.modelView = new view.ModelView({
			el: $("#participanteModelContainer")
		});

		// tell the model view where it's template is located
		this.modelView.templateEl = $("#participanteModelTemplate");

		if (model.longPollDuration > 0)	{
			setInterval(function () {

				if (!page.dialogIsOpen)	{
					page.fetchParticipantes(page.fetchParams,true);
				}

			}, model.longPollDuration);
		}
	},

	/**
	 * Fetch the collection data from the server
	 * @param object params passed through to collection.fetch
	 * @param bool true to hide the loading animation
	 */
	fetchParticipantes: function(params, hideLoader) {
		// persist the params so that paging/sorting/filtering will play together nicely
		page.fetchParams = params;

		if (page.fetchInProgress) {
			if (console) console.log('supressing fetch because it is already in progress');
		}

		page.fetchInProgress = true;

		if (!hideLoader) app.showProgress('loader');

		page.participantes.fetch({

			data: params,

			success: function() {

				if (page.participantes.collectionHasChanged) {
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
		$('#participanteDetailDialog').modal({ backdrop: 'static', show: true });

		// if a model was specified then that means a user is editing an existing record
		// if not, then the user is creating a new record
		page.participante = m ? m : new model.ParticipanteModel();

		page.modelView.model = page.participante;

		if (page.participante.id == null || page.participante.id == '') {
			// this is a new record, there is no need to contact the server
			page.renderModelView(false);
		} else {
$('#titulo-modal').html('Editar');
$('#icone-acao-modal').removeClass('icon-plus-sign');
			app.showProgress('modelLoader');

			// fetch the model from the server so we are not updating stale data
			page.participante.fetch({

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


//CARREGANDO
$('#table-participantes').html('<div class="text-center"><span class="multiselect-loading icon-big icon-refresh icon-spin" style="margin:20px; font-size:48px; vertical-align:middle;"></span> Carregando dados</div>');


var listaParticipantes = $.getJSON(base+'api/participantes');

listaParticipantes.success(function(res){
	ppp = res;
	



page.participantes.fetch({

			data: '',

			success: function(p) {
				
				
				
				
				var
    container = document.getElementById('table-participantes'),
    addCar = document.getElementById('add_car'),
    removeCar = document.getElementById('remove_car'),	
	saveCar = document.getElementById('save_car'),
	searchFiled = document.getElementById('search_field'),
    eventHolder = document.getElementById('example1_events'),
    CarModel = Backbone.Model.extend({}),
    CarCollection,
    cars,
    hot;

  CarCollection = Backbone.Collection.extend({
    model: page.participante,
    // Backbone.Collection doesn't support `splice`, yet! Easy to add.
    splice: hackedSplice
  });

 // cars = new model.ParticipanteCollection();

  cars = p;

 function customRender(instance, td, row, col, prop, value, cellProperties) {
    // change to the type of renderer you need; eg. if this is supposed
    // to be a checkbox, use CheckboxRenderer instead of TextRenderer
    Handsontable.renderers.TextRenderer.apply(this, arguments);

	console.log(td);
	
    // get the jquery selector for the td or add the class name using native JS
    $(td).addClass("error");

    return td;
}

var yourErrorRenderer = function (instance,td, row, col, prop, value, cellProperties) {
 console.log(instance.getDataAtCell(row, '_tr_attr1'));
 if($('#table-participantes').handsontable('getDataAtCell',row, 1) == 'error'){
    $(td).parent().css('background-color','#205199');
  }else{
    $(td).parent().addClass('classe');
  }
  return td;
};

var descriptionRenderer = function (instance, td, row, col, prop, value, cellProperties) {
  var escaped = Handsontable.helper.stringify(instance.getDataAtCell(row, 1));
  td.innerHTML = escaped;
  return td;
};

var customRenderer = function (instance, td, row, col, prop, value) {
	//COLOCA ID como classe para a celula
	total = instance.getDataAtCell(row, 1);
	$(td).parent().attr('id', 'item_'+instance.getDataAtCell(row, 0) );

	Handsontable.renderers.TextRenderer.apply(this, arguments);
};
  
  //VALIDAÇÕES
  emptyValidator = function(value, callback) {
	if (value === '') // isEmpty is a function that determines emptiness, you should define it
		callback(false);
	else
		callback(true);
  }

  emailValidator = function(value, callback) {
	ehEmail = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i.test(value);
	
	if(ehEmail === false && value !== '')
		callback(false);
	else
		callback(true);
  } 

  cpfValidator = function(value, callback) {
	teste = value.replace(/^(\d{3})(\d{3})(\d{3})(\d{2})$/g,"\$1.\$2.\$3\-\$4");
	
	ehCpf = /^(\d{3})\.(\d{3})\.(\d{3})\-(\d{2})$/g.test(teste);
	
	if(ehCpf === false)
		callback(false);
	else
		callback(true);
  } 
  
  hot = new Handsontable(container, {
    // data: cars,
    // dataSchema: makeCar,
	
	data: ppp.rows,
    dataSchema: {idParticipante: null, nome: null, email: null, cpf: null},
	
   // contextMenu: ["row_above", "row_below", "remove_row", "undo", "redo"],
	height: $(window).height()-( $(window).height()/3.4 ),
	colWidths: [10], //ESCONDE ID 0.1
	stretchH: 'all',
	className:  p.get('idParticipante'),
	currentRowClassName: 'currentRow',
    currentColClassName: 'currentCol',
	allowInsertColumn: false,
	comments: true,
	columnSorting: true,
	fillHandle: false,
	search: true,
	rowHeaders: true,
    colHeaders: ['#', 'Nome', 'Email', 'CPF'],	
    minRows: 1,
	columns: [
		{data: 'idParticipante' },
		{data: 'nome', validator: emptyValidator, allowInvalid: false},
		{data: 'email', validator: emailValidator, allowInvalid: true},
		{data: 'cpf', validator: cpfValidator, allowInvalid: true}
    ],
	
	beforeRemoveRow: function (index, amount) {
			if((hot.getDataAtCell(index, 0) === null && hot.getDataAtCell(index, 1) === null && hot.getDataAtCell(index, 3) === null)
			){
				//SE TUDO ESTIVER EM BRANCO, TIPO UM CTRL APÓS ADICIONAR UMA NOVA LINHA
			} else if(amount > 1){
				$('#modalNaoPodeExcluirVarios').modal({
					width: '400px'
				});
				$('#modalNaoPodeExcluirVarios').on('hidden', function () {
					hot.undo();
				});
			} else {
				$('#modalConfirmarExclusao').modal({
					width: '400px',
					backdropTemplate: '<div class="modal-backdrop red" />'
				});
				
				//se existir id
				if(hot.getDataAtCell(index, 0) != ''){
					idExcluido = hot.getDataAtCell(index, 0);
				
					console.log('idEX',idExcluido);
				
					page.participante = page.participantes.get( idExcluido );
				}
					
				$('#modalConfirmarExclusao .modal-body h4').text(page.participante.get('nome'));
				
				$('#btnConfirmarExclusao').click(function(){
					
					$(this).text('Excluindo...');
				
					//se existir id
					if(hot.getDataAtCell(index, 0) != '')
						page.deleteModel();
					
					$('#modalConfirmarExclusao').modal('toggle');
				});
				$('#btnCloseExclusao, #btnCancelarExclusao').click(function(){
					hot.undo();
				});
			}
			console.log(index, amount, 'VALOR DA CELULAR',hot.getDataAtCell(index, 0));
	},
	
	beforeChange: function (changes, source) {		
      for (var i = changes.length - 1; i >= 0; i--) {
		  
			//tenta converter o valor para cpf se for na coluna cpf
			if(changes[i][1] === 'cpf')
				changes[i][3] = changes[i][3].replace(/^(\d{3})(\d{3})(\d{3})(\d{2})$/g,"\$1.\$2.\$3\-\$4");
			
		
      }
    },
	
	cells: function(row, col, prop){
        
        //Deixa ID como somente leitura
		if (col === 0) {   
			var cellProperties = {};
            cellProperties.readOnly = true;
        }
		
		this.renderer = customRenderer;
		
        return cellProperties;
    }
  });
  
  
  //OPÇÕES DO MENU DE CONTEXTO
  hot.updateSettings({
    contextMenu: {
      items: {
        "row_above": {
          disabled: function () {
            // if first row, disable this option
            return hot.getSelected()[0] === 0;
          },
		  name: 'Inserir linha acima'
        },
        "row_below": { name: 'Inserir linha abaixo'},
        "hsep1": "---------",
        "remove_row": {
          name: 'Excluir participante'
        },
		"hsep2": "---------",
        "undo": {
          name: 'Desfazer (Ctrl+Z)'
        },
		"redo": {
          name: 'Refazer (Ctrl+Y)'
        }
      }
    }
  });
  
  
  
  //BUSCA
  function onlyExactMatch(queryStr, value) {
    if(queryStr.toString() === value.toString())
		return true;
  }

  //SE TIVER CLICADO EM UM ELEMENTO DA VIEW ELE FILTRA PELO ID
  Handsontable.Dom.addEvent(searchFiled, 'blur', function (event) {
	hot.updateSettings({
		search: { queryMethod: onlyExactMatch }
	});
	var queryResult = hot.search.query(page.SearchTableById);
	
	hot.selectCell(queryResult[0].row, queryResult[0].col); //seleciona o item
	
	if(queryResult !== null)
    hot.render();
  });
  
  //SE NÃO, BUSCA PELO QUE FOR DIGITADO
  Handsontable.Dom.addEvent(searchFiled, 'keypress', function (event) {
    hot.updateSettings({
		search: true
	});
	var queryResult = hot.search.query(this.value);
	if(queryResult !== null)
    hot.render();
  });
  
  setTimeout(function(){
		if(page.SearchTableById !== ''){
			$('#search_field').blur().focus();
			$('td.htDimmed.htSearchResult').parent().find('td').addClass('currentRow');
		}
  }, 1000);
 

  // you'll have to make something like these until there is a better
  // way to use the string notation, i.e. "bb:make"!

  // normally, you'd get these from the server with .fetch()
  function attr(attr) {
    // this lets us remember `attr` for when when it is get/set
    return {data: function (car, value) {
      if (_.isUndefined(value)) {
        return car.get(attr);
      }
      car.set(attr, value);
    }};
  }

  // just setting `dataSchema: CarModel` would be great, but it is non-
  // trivial to detect constructors...
  function makeCar() {
    return new model.ParticipanteModel();
  }

  Handsontable.Dom.addEvent(addCar, 'click', function () {
	hot.alter('insert_row',0);	
  });

  Handsontable.Dom.addEvent(removeCar, 'click', function () {
	console.log(hot.getSelected());
	hot.alter('remove_row',hot.getSelected());	
  });
  
  
  Handsontable.Dom.addEvent(saveCar, 'click', function() {
    
	
	// save all cell's data
    var request = $.ajax({
		url: base+'api/participantes/updateall',
		type: "post",
		contentType: "application/json",
		dataType: 'json',
		data: JSON.stringify({data: hot.getData()}),
		complete: function(t){
			console.log('AEEE',t);
		},
		beforeSend: function(){
			app.showProgress('modelLoader');
		},
		error: function(model,response,scope){	

				// reset any previous errors
				$('#modelAlert').html('');
				$('.control-group').removeClass('error');
				$('.help-inline').html('');
				
				//Volta ao topo se der erro
				$('.modal-scrollable').animate({
					scrollTop: 30
				}, 500);
				
				app.hideProgress('modelLoader');
				
				app.appendAlert('Ocorreram erros ao salvar os participantes. Por favor verifique.', 'alert-error',0,'modelAlert');

				try {
					var json = $.parseJSON(model.responseText); 

					console.log('AQUIQUIQ',json.errors);
				
					
						$.each( json.errors , function(key, value) {
							console.log(key, value);
							
							var elemento = null;
							elemento = $('.htCore tr#item_'+key);
							if(!key)
								elemento = $('#table-participantes');
							//elemento.addClass('error');
							
							console.log('LELL',elemento);
							
							$.each( value.message , function(campo, msg, row) {
								// var options = {
									// title: 'Titulo',
									// content: 'TEste',
									// placement: 'bottom'
								// }
								//elemento.popover(options).popover('show');
								//elemento.append('<div class="error-handsontable-item">▲'+msg+'</div>');
								
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
								$('<a class="goto btn btn-small btn-warning" data-row="'+value.row+'">CORRIGIR</a>').appendTo('#modelAlert .alert:last-child');
								$('<a class="gotoback btn btn-small btn-primary hide" data-row="'+value.row+'">CORRIGIDO</a>').appendTo('#modelAlert .alert:last-child');
								
								$('.goto').on('click',function(e){
									var linha = parseInt($(this).data('row'));
									hot.selectCell(linha, 0, linha, 3, true);
									
									//$(this).parent().css({position:'fixed',top: $('.modal-scrollable').scrollTop() ,zindex:6000});
									$('.alert').removeClass('stick');
									$(this).parent().addClass('stick').css({'position':'absolute','z-index':6000});
									
									$('.modal-scrollable').scroll(function() {
										 var a=$('.modal-scrollable').scrollTop();
										 $('.stick').css('top',a+'px');
									});
									
									//Desce até a linha do elemento
									// console.log('ROLAGEM DESSE CARA',elemento.offset().top, 'Nova alt', elemento.offset().top - $('.modal-scrollable').scrollTop());
									$('.wtHolder').animate({
									  scrollTop: elemento.offset().top
									}, 0);
									$('.modal-scrollable').animate({
										scrollTop: elemento.offset().top
									}, 500);
									
									$(this).parent().find('.gotoback').removeClass('hide');
									$(this).hide();
								});
	
								$('.gotoback').on('click',function(e){
									//Fecha a notificação ao clicar nela em corrigido
									//$(this).unbind('click');
								
										//Volta ao topo se der erro
										$('.wtHolder').animate({
											scrollTop: 0
										}, 500);
										$('.modal-scrollable').animate({
											scrollTop: 30
										}, 500);
				
										$('.alert.stick').addClass('animated bounceOut').delay(1000).queue(function(){
											$(this).remove();
											
											console.log($( ".alert.alert-dark" ).length);
										
											//Se não existir mais nenhum erro ele já salva
											if ( !$( ".alert.alert-dark" ).length ) {
												
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
		
		app.hideProgress('modelLoader');		
		app.appendAlert(response.message, 'alert-success',0,'modelAlert');
		
		//Adiciona id dos novos participantes cadastrados na tabela
		$.each( response.novo , function(index, novo) {
			hot.setDataAtCell(novo.row, 0, novo.idParticipante);
		});
		
	});
	 
	request.fail(function( jqXHR, textStatus ) {
	 console.log( "FALHA AO ENVIAR PARTICIPANTES PARA O SERVIDOR: " + textStatus );
	 console.log(jqXHR);
	 console.log('.........');
	});
	
	
  });
  
				
				
				
				
				
			},

			error: function(m, r) {
				console.log('Erro ao carregar participantes');
			}
		});







}); //Fim get







		
		
		
		
		












		
		
		

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


		if (showDeleteButton) {
			// attach click handlers to the delete buttons

			$('#deleteParticipanteButton').click(function(e) {
				e.preventDefault();
				$('#confirmDeleteParticipanteContainer').show('fast');
			});

			$('#cancelDeleteParticipanteButton').click(function(e) {
				e.preventDefault();
				$('#confirmDeleteParticipanteContainer').hide();
			});

			$('#confirmDeleteParticipanteButton').click(function(e) {
				e.preventDefault();
				page.deleteModel();
			});

		} else {
			// no point in initializing the click handlers if we don't show the button
			$('#deleteParticipanteButtonContainer').hide();
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
		var isNew = page.participante.isNew();

		app.showProgress('modelLoader');

		page.participante.save({

			'nome': $('input#nome').val(),
			'email': $('input#email').val(),
			'cpf': $('input#cpf').val()
		}, {
			wait: true,
			success: function(){
				$('#participanteDetailDialog').modal('hide');
				setTimeout("app.appendAlert('Participante foi " + (isNew ? "inserido" : "editado") + " com sucesso','alert-success',3000,'collectionAlert')",500);
				app.hideProgress('modelLoader');

				// if the collection was initally new then we need to add it to the collection now
				if (isNew) { page.participantes.add(page.participante) }

				if (model.reloadCollectionOnModelUpdate) {
					// re-fetch and render the collection after the model has been updated
					page.fetchParticipantes(page.fetchParams,true);
				}
				
				$('table.collection tr#'+page.participante.id).addClass('modificou-item');	
				
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

		page.participante.destroy({
			wait: true,
			success: function(){
				$('#participanteDetailDialog').modal('hide');
				setTimeout("app.appendAlert('The Participante record was deleted','alert-success',3000,'collectionAlert')",500);
				app.hideProgress('modelLoader');

				if (model.reloadCollectionOnModelUpdate) {
					// re-fetch and render the collection after the model has been updated
					page.fetchParticipantes(page.fetchParams,true);
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

