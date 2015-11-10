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
	erroExcluir: false,
	timerLigado: false,
	temAlteracoes: false,

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
		
		

		// make the new button clickable
		$("#newParticipanteButton").click(function(e) {
			e.preventDefault();
			page.showDetailDialog();
		});

		// let the page know when the dialog is open
		$('#participanteDetailDialog').on('show',function() {
			page.dialogIsOpen = true;
		});	
		
		$('#btnCloseModalPrincipal').on('click',function() {
			$('#participanteDetailDialog').modal('hide');
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
			
			//Ordenar pelo cadastro
			$('.ordemCadastro').click(function(e) {
 				e.preventDefault();
				var prop = this.id.replace('ordemCadastro_','');

				// toggle the ascending/descending before we change the sort prop
				page.fetchParams.orderDesc = (prop == page.fetchParams.orderBy && !page.fetchParams.orderDesc) ? '1' : '';
				page.fetchParams.orderBy = prop;
				page.fetchParams.page = 1;
 				page.fetchParticipantes(page.fetchParams);
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

			// Adiciona o atributo data-title nas tr da tabela para responsividade
			$( "table.collection tbody td" ).each(function(index){
				total = $( "table.collection thead th").length;
				titulo = $( "table.collection thead th").eq(index % total).text();
				
				$(this).attr('data-title',titulo); 
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
		this.fetchParticipantes({ page: 1, orderBy: 'IdParticipante', orderDesc: '1' });

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

		//Filtra pelo id da palestra na URL
		if(idPalestra){
			page.fetchParams.idPalestra = idPalestra[1];
		}
		
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
				app.hideProgress('savingFloat');
				page.fetchInProgress = false;
			},

			error: function(m, r) {
				app.appendAlert(app.getErrorMessage(r), 'alert-error',0,'collectionAlert');
				app.hideProgress('loader');
				app.hideProgress('savingFloat');
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
		
		//FIXA BOTÃO DE SALVAR AO ROLAR PÁGINA
		$('.modal-scrollable').scroll(function() {
			 var a=$('.modal-scrollable').scrollTop();
			 if(a > 100){
				$('#save_car').css('top',(a+20)+'px').addClass('affix');
			 } else {
				$('#save_car').removeClass('affix'); 
			 }
		});		
		

		// if a model was specified then that means a user is editing an existing record
		// if not, then the user is creating a new record
		page.participante = m ? m : new model.ParticipanteModel();

		page.modelView.model = page.participante;

		if (page.participante.id == null || page.participante.id == '') {
			// this is a new record, there is no need to contact the server
			page.renderModelView(false);
		} else {

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
					app.hideProgress('savingFloat');
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
		app.hideProgress('savingFloat');		


//CARREGANDO
$('#table-participantes').html('<div class="text-center"><span class="multiselect-loading icon-big icon-refresh icon-spin" style="margin:20px; font-size:48px; vertical-align:middle;"></span> Carregando dados</div>');


var listaParticipantes = $.getJSON(base+'api/participantes?orderDesc=1&orderBy=IdParticipante');

listaParticipantes.success(function(todosParticipantes){


var listaParticipantesPalestra = $.getJSON(base+'api/palestraparticipantes?orderDesc=1&orderBy=IdParticipante&IdPalestra='+idPalestra[1]);

listaParticipantesPalestra.success(function(palestraParticipantes){	

	//FAZ O INNER JOIN DE PARTICIPANTES NA PALESTRA
	var arrayFinal = {};
	arrayFinal['rows'] = [];
	$.each( todosParticipantes.rows , function(key, value) {
	 if($.inArray(value.idParticipante, _.pluck(palestraParticipantes.rows, 'idParticipante')) >= 0)
		 arrayFinal.rows.push(value);
	});	
	 
	
	//Listagem final filtrada
	//ppp = todosParticipantes;	
	ppp = arrayFinal;		
				
				
	var
    container = document.getElementById('table-participantes'),
    addCar = document.getElementById('add_car'),
    removeCar = document.getElementById('remove_car'),	
	saveCar = document.getElementById('save_car'),
	searchFiled = document.getElementById('search_field'),
	autosaveNotification,
	salvoUltimaVez,
    eventHolder = document.getElementById('example1_events'),
    CarModel = Backbone.Model.extend({}),
    CarCollection,
    cars,
    hot;
	
	salvoUltimaVez = new Date();

  CarCollection = Backbone.Collection.extend({
    model: page.participante,
    // Backbone.Collection doesn't support `splice`, yet! Easy to add.
    splice: hackedSplice
  });

 // cars = new model.ParticipanteCollection();

  cars = '';

 function customRender(instance, td, row, col, prop, value, cellProperties) {
    // change to the type of renderer you need; eg. if this is supposed
    // to be a checkbox, use CheckboxRenderer instead of TextRenderer
    Handsontable.renderers.TextRenderer.apply(this, arguments);
	
    // get the jquery selector for the td or add the class name using native JS
    $(td).addClass("error");

    return td;
}

var yourErrorRenderer = function (instance,td, row, col, prop, value, cellProperties) {
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
  
  //LISTAS PARA AUTOCOMPLETE
  //////SUBSTITUIR O RES.ROWS POR OUTRO GET COM TODOS OS PARTICIPANTES DO BANCO E NÃO SÓ OS DA ATIVIDADE ATUAL.
  var listaIds = [];
  var listaNomes = [];
  var listaEmails = [];
  var listaCpfs = [];
  $.each( todosParticipantes.rows , function(key, value) {
		listaIds.push(value.idParticipante);
		listaNomes.push(value.nome);
		listaEmails.push(value.email);
		listaCpfs.push(value.cpf);
  });
  
  hot = new Handsontable(container, {
    // data: cars,
    // dataSchema: makeCar,
	
	data: ppp.rows,
    dataSchema: {idParticipante: null, nome: null, email: null, cpf: null},
	
   // contextMenu: ["row_above", "row_below", "remove_row", "undo", "redo"],
	height: $(window).height()-( $(window).height()/3.4 ),
	colWidths: [10], //ESCONDE ID 0.1
	stretchH: 'all',
	currentRowClassName: 'currentRow',
    currentColClassName: 'currentCol',
	allowInsertColumn: false,
	comments: true,
	columnSorting: true,
	sortIndicator: true,
	fillHandle: false,
	search: true,
	rowHeaders: true,
    colHeaders: ['#', 'Nome', 'Email', 'CPF'],	
    minRows: 1,
	minSpareRows: 1,
	columns: [
		{data: 'idParticipante' },
		//{data: 'nome', validator: emptyValidator, allowInvalid: false},
		//{data: 'email', validator: emailValidator, allowInvalid: true},
		//{data: 'cpf', validator: cpfValidator, allowInvalid: true}
		{
			data: 'nome',
			placeholder: 'Nome', 
			type: 'autocomplete',
			source: listaNomes,
			strict: false,
			validator: emptyValidator, 
			allowInvalid: false
		},
		{
			data: 'email',
			placeholder: 'E-mail', 
			type: 'autocomplete',
			source: listaEmails,
			strict: false,
			validator: emailValidator,
			allowInvalid: true
		},
		{
			data: 'cpf',
			placeholder: 'CPF', 
			type: 'autocomplete',
			source: listaCpfs,
			strict: false,
			validator: cpfValidator,
			allowInvalid: true
		},
    ],
	
	afterChange: function (change, source) {
		if (source === 'loadData') {
			return; //don't save this change
		}
		
		//SALVA A TABELA AUTOMATICAMENTE A CADA X TEMPO 35000 = 35 segundos, se parar de mexer na tabela
		page.timerLigado = true;
		page.temAlteracoes = true;
		
		var salvoUltimaVez = new Date();
		if(page.timerLigado === true){
			clearTimeout(autosaveNotification);
			autosaveNotification = setTimeout(function() {
			  saveCar.click();
			}, 35000);
		}
		
		
		//CONFIRMAR SALVAR ALTERAÇÕES AO CLICAR EM SAIR DO MODAL
		if(page.temAlteracoes === true){
			$('#btnCloseModalPrincipal').unbind('click');	
			$('#btnCloseModalPrincipal').on('click',function() {
				$('#participanteDetailDialog').modal('show');
				$('#modalSalvarAlteracoes').modal({
						width: '400px',
						backdrop: 'static',
						keyboard: false
				});
				$('#btnSalvarAlteracoes').click(function(){
					saveCar.click();
					$('#modalSalvarAlteracoes').modal('hide');
				});
				$('#btnCancelarSalvarAlteracoes').click(function(){
					$('#modalSalvarAlteracoes, #participanteDetailDialog').modal('hide');
				});
			});	
		}
		
	},
	
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
					backdrop: 'static',
					keyboard: false,
					backdropTemplate: '<div class="modal-backdrop red" />'
				});
				
				//se existir id
				if(hot.getDataAtCell(index, 0) != ''){
					idExcluido = hot.getDataAtCell(index, 0);

					if(idExcluido != null)
						page.participante = page.participantes.get( idExcluido );
				}
					
				$('#modalConfirmarExclusao .modal-body h4').text(page.participante.get('nome'));
				
				$('#btnConfirmarExclusao').click(function(){
					
					$(this).text('Excluindo...');
				
					//se existir id
					if(hot.getDataAtCell(index, 0) != ''){
						page.deleteModel();
						page.fetchParticipantes(page.fetchParams);
						
						//se der erro na exclusão por causa da chave estrangeira
						setTimeout(function(){ 
							if(page.erroExcluir === true)
								hot.undo(); 
						}, 2000);
						
						$(this).text('Sim');
					}
					
					$('#modalConfirmarExclusao').modal('hide');
				});
				$('#btnCloseExclusao, #btnCancelarExclusao').click(function(){
					hot.undo();
				});
			}
	},
	
	beforeChange: function (changes, source) {		
		for (var i = changes.length - 1; i >= 0; i--) {
		  
			//tenta converter o valor para cpf se for na coluna cpf
			if(changes[i][1] === 'cpf')
				changes[i][3] = changes[i][3].replace(/^(\d{3})(\d{3})(\d{3})(\d{2})$/g,"\$1.\$2.\$3\-\$4");
			}
	
			//DESLOCA CELULAS PARA BAIXO SE COLAR ALGUMA COISA
			if(changes.length > 1)
			  hot.alter('insert_row',hot.getSelected()[0], changes.length/2); //divide por dois por alguma razão está inserindo duplicado
				  
			//PREENCHE O RESTO DA LINHA COM O DOS DADOS DO CIDADÃO, CASO EXISTA ao escrever ou colar dados
			//FALAR NO RELATÓRIO SOBRE O SEU USO NO EVENTO BEFORECHANGE INVÉS DE AFTER CHANGE
			if(source==="edit" || source==='paste'&&changes.length===1) {
				var oldValue = changes[0][2];
				var value = changes[0][3];
				for(var i=0;i<todosParticipantes.rows.length;i++) {
					if(todosParticipantes.rows[i].nome === value || todosParticipantes.rows[i].email === value || todosParticipantes.rows[i].cpf === value) {
						hot.setDataAtCell(changes[0][0], 0, todosParticipantes.rows[i].idParticipante);
						hot.setDataAtCell(changes[0][0], 1, todosParticipantes.rows[i].nome);
						hot.setDataAtCell(changes[0][0], 2, todosParticipantes.rows[i].email);
						hot.setDataAtCell(changes[0][0], 3, todosParticipantes.rows[i].cpf);

						//REMOVE O PARTICIPANTE SELECIONADO DA LISTAGEM DO AUTOCOMPLETE
						//listaNomes.splice(listaNomes.indexOf(value), 1);
						//listaEmails.splice(listaEmails.indexOf(value), 1);
						//listaCpfs.splice(listaCpfs.indexOf(value), 1);
						
						return false;
					}
				}
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
		  name: '<i class="icon-plus"></i> Inserir linha acima'
        },
        "row_below": { name: '<i class="icon-plus"></i> Inserir linha abaixo'},
        "hsep1": "---------",
        "remove_row": {
          name: '<i class="icon-trash"></i> Excluir participante'
        },
		"hsep2": "---------",
        "undo": {
          name: '<i class="icon-undo"></i> Desfazer (Ctrl+Z)'
        },
		"redo": {
          name: '<i class="icon-repeat"></i> Refazer (Ctrl+Y)'
        }
      }
    }
  });
	
  //BUSCA
  function onlyExactMatch(queryStr, value) {
    if(value !== null && queryStr.toString() === value.toString())
		return true;
  }

  //SE TIVER CLICADO EM UM ELEMENTO DA VIEW ELE FILTRA PELO ID
  Handsontable.Dom.addEvent(searchFiled, 'blur', function (event) {
	hot.updateSettings({
		search: { queryMethod: onlyExactMatch }
	});
	var queryResult = hot.search.query(page.SearchTableById);
	
	if(queryResult !== null & this.value !== ''){
    	hot.selectCell(queryResult[0].row, 1); //seleciona a coluna nome nome
		hot.render();
	}
  });
  
  //SE NÃO, BUSCA PELO QUE FOR DIGITADO
  Handsontable.Dom.addEvent(searchFiled, 'keypress', function (event) {
    hot.updateSettings({
		search: true
	});
	var queryResult = hot.search.query(this.value);
	if(queryResult !== null && this.value !== '')
    hot.render();
  });
  
  setTimeout(function(){
		if(page.SearchTableById !== ''){
			$('#search_field').blur().dblclick().focus();
			$('td.htDimmed.htSearchResult').parent().find('td').addClass('currentRow');
		}
  }, 500);
 

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
	//ia ser no índice 0, mas por causa do Ctr+V pode substituir o conteúdo :(
	hot.alter('insert_row');	
  });

  // Handsontable.Dom.addEvent(removeCar, 'click', function () {
	// console.log(hot.getSelected());
	// hot.alter('remove_row',hot.getSelected());	
  // });
  
  
  Handsontable.Dom.addEvent(saveCar, 'click', function() {
    
	
	// save all cell's data
    var request = $.ajax({
		url: base+'api/participantes/updateall?idPalestra='+idPalestra[1],
		type: "post",
		contentType: "application/json",
		dataType: 'json',
		data: JSON.stringify({data: hot.getData()}),
		complete: function(t){
			page.timerLigado = false;
		},
		beforeSend: function(){
			app.showProgress('modelLoader');
			app.showProgress('savingFloat');
		},
		error: function(model,response,scope){	

				// reset any previous errors
				$('#modelAlert').html('');
				$('.control-group').removeClass('error');
				$('.help-inline').html('');
				
				//Volta ao topo se der erro
				$('.modal-scrollable').scrollTop(0);
				
				app.hideProgress('modelLoader');
				app.hideProgress('savingFloat');
				
				app.appendAlert('Ocorreram erros ao salvar alguns participantes. Por favor verifique.', 'alert-error',0,'modelAlert');

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
									
									//Desce até a linha do elemento (-500 no monitor 22 pol)
									$('.wtHolder').scrollTop( itemTop - ($('.handsontable').height()/2) );
									$('.modal-scrollable').scrollTop( $(document).height() + 300 ); 
									
									$(this).parent().find('.gotoback').removeClass('hide');
									$(this).hide();
								});
	
								$('.gotoback').on('click',function(e){
									//Fecha a notificação ao clicar nela em corrigido
									//$(this).unbind('click');
								
										//Volta ao topo se der erro
										$('.modal-scrollable, .wtHolder').scrollTop(0);
				
										$('.alert.stick').addClass('animated bounceOutUp').delay(1000).queue(function(){
											$(this).remove();
										
											//Se não existir mais nenhum erro ele já salva
											//O LENGTH DEVERIA SER 0 PARA MOSTRA TODAS AS NOTIFICAÇÕES DE ERRO, MAS NÃO ESTÁ MOSTRANDO
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
		$('#save_car').attr('data-autosave','Salvo às '+_date(app.parseDate(salvoUltimaVez)).format('HH:mm:ss'));
				
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
	
	
  });
  
				
				
				
				






}); //Fim get participantespalestra

}); //Fim get todos participantes







		
		
		
		
		












		
		
		

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
				app.hideProgress('savingFloat');
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
				app.hideProgress('savingFloat');

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
						
			var participanteCollection = new model.PalestraParticipanteCollection();	
				
			participanteCollection.fetch({
				data : {
					'idParticipante': page.participante.id,
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
							
								page.palestraParticipante = new model.PalestraParticipanteModel();
								page.palestraParticipante.id = pal.id;
								
								try {
									page.palestraParticipante.destroy();
								} catch(e2) {
									console.log('Não é possível remover pois tem certificado'+e2);
									hot.undo();
								}
						
							}
							
							if(qtd === c.length){
								console.log('Vai remover o palestrante, pois removeu as associacoes');
								removerParticipante();
							}
							qtd++;
							
						});
					} else {
						console.log('Vai remover o palestrante');
						removerParticipante();
					}
				
				},
				error: function(model, response) {
					console.log('Erro ao remover a relação do palestrante');
					console.log(response);
					page.excluir = false;
				}
			});	
		
		
		
		
		
		// REMOVE A PALESTRANTE
		function removerParticipante(){

			page.participante.destroy({
				wait: true,
				success: function(){
					//$('#participanteDetailDialog').modal('hide');
					setTimeout("app.appendAlert('O participante foi excluido','alert-success',3000,'modelAlert')",2000);
					app.hideProgress('modelLoader');
					app.hideProgress('savingFloat');

					if (model.reloadCollectionOnModelUpdate) {
						// re-fetch and render the collection after the model has been updated
						page.fetchParticipantes(page.fetchParams,true);
					}
				},
				error: function(model,response,scope) {
					console.log(response.responseText.message);
					app.appendAlert(app.getErrorMessage(response), 'alert-error',0,'modelAlert');
					app.hideProgress('modelLoader');
					app.hideProgress('savingFloat');
					
					page.erroExcluir = true;
					
					$('.modal').addClass('animated shake').delay(1000).queue(function(){
						$(this).removeClass("animated shake").dequeue();
					});
				}
			});
		
		}
		
	}
};

