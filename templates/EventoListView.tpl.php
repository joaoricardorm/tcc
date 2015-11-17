<?php
	$this->assign('title','Eventos - Certifica-μ');
	$this->assign('nav','eventos');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB
	.script(base+"scripts/app/eventos.js").wait(function(){
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
			<li class="current"><span><i class="icon-tag"></i>Eventos</span></li> <!-- Classe "visited" -->
			<li class="remove-on-single"><span class="muted"><i class="icon-tags"></i>Atividades</span></li>
			<li><span class="muted"><i class="icon-microphone"></i>Palestrantes</span></li>
			<li><span class="muted"><i class="icon-group"></i>Participantes</span></li>
		</ol>
</nav>

<div class="container hero-unit">

<h1>
	<i class="icon-tag"></i> Eventos
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
		
		<% if(items.length === 0){ %>
			<hr>
			<h3>Nenhum evento encontrado</h3>
		<% } else { %>
		
		
		<p class="ordemCadastro" id="ordemCadastro_IdEvento">
			<a href="#">
			<% if (page.orderBy == 'IdEvento') { %> 
				<%= page.orderDesc ? "Mostrar antigos primeiro <i class='icon-arrow-down' />" : "Mostrar recentes primeiro <i class='icon-arrow-up' />" %>
			<% } else { %>
				Ordenar pelo cadastro no sistema <i class='icon-arrow-down' />
			<% } %>
			</a>
		</p>
		
		
		<div id="no-more-tables">
		
		<table class="collection table table-hover table-striped responsible-table">
		<thead>
			<tr>
				<th id="header_Nome"><i class="icon icon-tag"></i>Nome do evento<% if (page.orderBy == 'Nome') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Local"><i class="icon icon-home"></i>Local<% if (page.orderBy == 'Local') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Data"><i class="icon icon-calendar"></i>Data<% if (page.orderBy == 'Data') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Duracao"><i class="icon icon-time"></i>Duração<% if (page.orderBy == 'Duracao') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
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
		
		<% } %>
		
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="eventoModelTemplate">
	
		<nav class="passos-evento">
		<ol class="cd-multi-steps text-top">
			<li class="current"><span><i class="icon-tag"></i>Evento</span></li> <!-- Classe "visited" -->
			<li><% if(item.get('idEvento')){ %><a href="./evento/<%=item.get('idEvento')%>/atividades/"><i class="icon-tags"></i><span class="remove-on-single">Atividades</span><span class="show-on-single">Detalhes</span></a><% } else { %><span class="muted">Atividades/Detalhes</span><% } %></li>
			<li><span class="muted"><i class="icon-microphone"></i>Palestrantes</span></li>
			<li><span class="muted"><i class="icon-group"></i>Participantes</span></li>
		</ol>
		</nav>
	
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
						<a href="evento/<%= _.escape(item.get('idEvento')) %>/atividades/" id="atividadesButton" class="btn btn-primary margin-right-bigger-sm block-sm"><i class="icon-tags icon-white"></i> <span class="remove-on-single">Atividades</span><span class="show-on-single">Detalhes</span></a>
						
						<span class="show-on-single">
							<a id="palestrantesButton" class="btn btn-primary margin-right-bigger-sm block-sm"><i class="icon-microphone icon-white"></i> Palestrantes</a>
							<a id="participantesButton" class="btn btn-primary block-sm"><i class="icon-group icon-white"></i> Participantes</a>
						</span>
						
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
						<button id="deleteEventoButton" class="btn btn-danger block-sm"><i class="icon-trash icon-white"></i> Excluir Evento</button>
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
	<div class="modal hide fade animated bounceIn modal-on-top"  data-backdrop="static" id="eventoDetailDialog">
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
			<button id="saveEventoButton" class="btn btn-primary block-sm"><span>Salvar e continuar</span><i class="icon-arrow-right icon-margin-left"></i></button><button id="cancelSaveEventoButton" class="btn" data-dismiss="modal" >Cancelar</button>
		</div>
	</div>

	<div id="collectionAlert"></div>
	
	<div id="eventoCollectionContainer" class="collectionContainer">
	</div>

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
