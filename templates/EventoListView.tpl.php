<?php
	$this->assign('title','Certificados FAROL | Eventos');
	$this->assign('nav','eventos');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB
	.script("bootstrap/js/bootstrap-confirmation.js")
	.script("bootstrap/js/bootstrap-tooltip.js")
	.script("scripts/app/eventos.js").wait(function(){
		$(document).ready(function(){
			page.init();
		});
		
		// hack for IE9 which may respond inconsistently with document.ready
		setTimeout(function(){
			if (!page.isInitialized) page.init();
		},1000);
	});
</script>

<nav class="container hero-unit small">
		<ol class="cd-multi-steps text-top">
			<li class="current"><span>Evento</span></li> <!-- Classe "visited" -->
			<li><span class="muted">Atividades</span></li>
			<li><span class="muted">Palestrantes</span></li>
			<li><span class="muted">Participantes</span></li>
		</ol>
</nav>

<div class="container hero-unit">

<h1>
	<i class="icon-user"></i> Eventos
	<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
</h1>

<div class="new-and-search-container clearfix">
	<span id="newButtonContainer" class="buttonContainer pull-left">
		<button id="newEventoButton" class="btn btn-primary"><i class="icon-plus"></i> Cadastrar Evento</button>
	</span>
	<span class='input-append pull-right searchContainer'>
		<input id='filter' type="text" placeholder="Buscar..." />
		<button class='btn add-on'><i class="icon-search"></i></button>
	</span>
</div>

	<!-- underscore template for the collection -->
	<script type="text/template" id="eventoCollectionTemplate">
		
		<div id="no-more-tables">
		
		<table class="collection table table-hover table-striped responsible-table">
		<thead>
			<tr>
				<th id="header_Nome">Nome do evento<% if (page.orderBy == 'Nome') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Local">Local<% if (page.orderBy == 'Local') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Data">Data<% if (page.orderBy == 'Data') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Duracao">Duração<% if (page.orderBy == 'Duracao') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('idEvento')) %>">
				<td><%= _.escape(item.get('nome') || '') %></td>
				<td><%= _.escape(item.get('local') || '') %></td>
				<td><%if (item.get('data')) { %><%= _date(app.parseDate(item.get('data'))).format('DD/MM/YYYY') %><% } else { %>NULL<% } %></td>
				<td><%= _.escape(item.get('duracao') || '') %></td>
			</tr>
		<% }); %>
		</tbody>
		</table>
		
		</div>

		<%=  view.getPaginationHtml(page) %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="eventoModelTemplate">
		<form class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div id="nomeInputContainer" class="control-group">
					<label class="control-label" for="nome">Nome do evento</label>
					<div class="controls inline-inputs">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-tag"></i></span>
							<input type="text" class="input-xlarge" id="nome" placeholder="Nome do evento" value="<%= _.escape(item.get('nome') || '') %>">
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="localInputContainer" class="control-group">
					<label class="control-label" for="local">Local</label>
					<div class="controls inline-inputs">			
						<div class="input-prepend">
							<span class="add-on"><i class="icon-home"></i></span>
							<input type="text" class="input-xlarge" id="local" placeholder="Local" value="<%= _.escape(item.get('local') || '') %>">
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="dataInputContainer" class="control-group">
					<label class="control-label" for="data">Data</label>
					<div class="controls inline-inputs">
						<div class="input-prepend" data-date-format="dd-mm-yyyy">
							<span class="add-on"><i class="icon-calendar"></i></span>
							<input id="data" type="date" class="input-large" value="<% if(item.get('idEvento')){ %><%= _date(app.parseDate(item.get('data'))).format('YYYY-MM-DD') %><% } %>" />
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="duracaoInputContainer" class="control-group">
					<label class="control-label" for="duracao">Duração</label>
					<div class="controls inline-inputs">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-time"></i></span>
							<input type="text" class="input-xlarge" id="duracao" placeholder="Ex: 5 horas e 30 minutos" value="<%= _.escape(item.get('duracao') || '') %>">
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
				<% if(item.get('idEvento')){ %>
				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
						<button id="atividadesButton" class="btn btn-primary margin-right-bigger-sm block-sm"><i class="icon-tag icon-white"></i> Atividades</button>
				
						<button id="palestrantesButton" class="btn btn-primary margin-right-bigger-sm block-sm"><i class="icon-microphone icon-white"></i> Palestrantes</button>
						
						<button id="participantesButton" class="btn btn-primary block-sm"><i class="icon-group icon-white"></i> Participantes</button>
					
					</div>
				</div>
				<% } %>
			</fieldset>
		</form>

		<!-- delete button is is a separate form to prevent enter key from triggering a delete -->
		<form id="deleteEventoButtonContainer" class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
						<button id="deleteEventoButton" class="btn btn-danger"><i class="icon-trash icon-white"></i> Excluir Evento</button>
						<span id="confirmDeleteEventoContainer" class="hide">
							<button id="cancelDeleteEventoButton" class="btn">Cancelar</button>
							<button id="confirmDeleteEventoButton" class="btn btn-success">Confirmar</button>
						</span>
					</div>
				</div>
			</fieldset>
		</form>
	</script>

	<!-- modal edit dialog -->
	<div class="modal hide fade" id="eventoDetailDialog">
		<div class="modal-header">
			<a class="close btn btn-danger btn-big" data-dismiss="modal">&times;</a>
			<h3>
				<i id="icone-acao-modal" class="icon-edit"></i> <span id="titulo-modal">Editar</span> Evento
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="eventoModelContainer"></div>
		</div>
		<div class="modal-footer">
			<button id="saveEventoButton" class="btn btn-primary">Salvar e continuar<i class="icon-arrow-right icon-margin-left"></i></button><button id="cancelSaveEventoButton" class="btn" data-dismiss="modal" >Cancelar</button>
		</div>
	</div>
	
	<!-- modal confirm -->
	<div class="modal fade" id="eventoConfirmSaveDialog">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title">Modal title</h4>
		  </div>
		  <div class="modal-body">
			<p>One fine body&hellip;</p>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary">Save changes</button>
		  </div>
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div id="collectionAlert"></div>
	
	<div id="eventoCollectionContainer" class="collectionContainer">
	</div>

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
