<?php
	$this->assign('title','Certificados FAROL | Palestras');
	$this->assign('nav','palestras');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">

	$LAB.script(base+"scripts/app/palestras.js").wait(function(){
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
			<li class="visited"><a href="eventos">Eventos</a></span></li> <!-- Classe "visited" -->
			<li class="current"><span>Atividade<span class="remove-on-single">s</span></span></li>
			<li><span class="muted">Palestrantes</span></li>
			<li><span class="muted">Participantes</span></li>
		</ol>
</nav>

<div class="container hero-unit">

<h1>
	<i class="icon-tags"></i> Atividade<span class="remove-on-single">s</span> <?php if($this->Evento) $this->eprint(' do evento '.$this->Evento->Nome); ?>
	<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
</h1>

<div class="new-and-search-container clearfix">
	<span id="newButtonContainer" class="buttonContainer pull-left">
		<button id="newPalestraButton" class="btn btn-primary"><i class="icon-plus"></i> Cadastrar Atividade</button>
	</span>
	<span class='input-append pull-right searchContainer'>
		<input id='filter' type="text" placeholder="Buscar..." />
		<button class='btn add-on'><i class="icon-search"></i></button>
	</span>
</div>


	<!-- underscore template for the collection -->
	<script type="text/template" id="palestraCollectionTemplate">
		
		<div id="no-more-tables">
	
		<table class="collection table table-hover table-striped responsible-table">
		<thead>
			<tr>
				<th id="header_IdPalestra">id<% if (page.orderBy == 'IdPalestra') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Nome">Nome da atividade<% if (page.orderBy == 'Nome') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Data">Data<% if (page.orderBy == 'Data') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_CargaHoraria">Carga Horária<% if (page.orderBy == 'CargaHoraria') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_ProprioEvento">Próprio Evento<% if (page.orderBy == 'ProprioEvento') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_NomeEvento">Evento<% if (page.orderBy == 'NomeEvento') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_IdModeloCertificado">Modelo do certificado<% if (page.orderBy == 'NomeModeloCertificado') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('idPalestra')) %>">
				<td><%= _.escape(item.get('idPalestra') || '') %></td>
				<td><%= _.escape(item.get('nome') || '') %></td>
				<td><%if (item.get('data')) { %><%= _date(app.parseDate(item.get('data'))).format('DD/MM/YYYY') %><% } else { %>NULL<% } %></td>
				<td><%if (item.get('cargaHoraria')) { %><%= _date(app.parseDate(item.get('cargaHoraria'))).format('h:mm') %> horas<% } else { %>NULL<% } %></td>
				<td><%= _.escape(item.get('proprioEvento') || '') %></td>
				<td><%= _.escape(item.get('nomeEvento') || '') %></td>
				<td><%= _.escape(item.get('nomeModeloCertificado') || '') %></td>
			</tr>
		<% }); %>
		</tbody>
		</table>
		
		</div>

		<%=  view.getPaginationHtml(page) %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="palestraModelTemplate">
		<form class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<input type="hidden" class="input-xlarge" id="proprioEvento" value="<%= _.escape(item.get('proprioEvento') || '0') %>">
				
				<div id="idPalestraInputContainer" class="control-group">
					<label class="control-label" for="idPalestra">Id Palestra</label>
					<div class="controls inline-inputs">
						<span class="input-xlarge uneditable-input" id="idPalestra"><%= _.escape(item.get('idPalestra') || '') %></span>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="nomeInputContainer" class="control-group">
					<label class="control-label" for="nome">Nome</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="nome" placeholder="Nome" value="<%= _.escape(item.get('nome') || '') %>">
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
				<div id="cargaHorariaInputContainer" class="control-group">
					<label class="control-label" for="cargaHoraria">Carga Horaria</label>
					<div class="controls inline-inputs">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-time"></i></span>
							<input id="cargaHoraria" type="time" class=" input-small" value="<%= _date(app.parseDate(item.get('cargaHoraria'))).format('hh:mm') %>" />
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="idEventoInputContainer" class="control-group">
					<label class="control-label" for="idEvento">Id Evento</label>
					<div class="controls inline-inputs">
						<select id="idEvento" name="idEvento"></select>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="idModeloCertificadoInputContainer" class="control-group">
					<label class="control-label" for="idModeloCertificado">Id Modelo Certificado</label>
					<div class="controls inline-inputs">
						<select id="idModeloCertificado" name="idModeloCertificado"></select>
						<span class="help-inline"></span>
					</div>
				</div>
			</fieldset>
		</form>

		<!-- delete button is is a separate form to prevent enter key from triggering a delete -->
		<form id="deletePalestraButtonContainer" class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
						<button id="deletePalestraButton" class="btn btn-danger"><i class="icon-trash icon-white"></i> Excluir Palestra</button>
						<span id="confirmDeletePalestraContainer" class="hide">
							<button id="cancelDeletePalestraButton" class="btn">Cancelar</button>
							<button id="confirmDeletePalestraButton" class="btn btn-success">Confirmar</button>
						</span>
					</div>
				</div>
			</fieldset>
		</form>
	</script>

	<!-- modal edit dialog -->
	<div class="modal hide fade" id="palestraDetailDialog">
		<div class="modal-header">
			<a class="close btn btn-danger btn-big" data-dismiss="modal">&times;</a>
			<h3>
				<i id="icone-acao-modal" class="icon-edit"></i> <span id="titulo-modal">Editar</span> Palestra
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="palestraModelContainer"></div>
		</div>
		<div class="modal-footer">
			<button id="savePalestraButton" class="btn btn-primary">Salvar</button>
			<button class="btn" data-dismiss="modal">Cancelar</button>
		</div>
	</div>

	<div id="collectionAlert"></div>
	
	<div id="palestraCollectionContainer" class="collectionContainer">
	</div>

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
