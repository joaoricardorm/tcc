<?php
	$this->assign('title','Certificados FAROL | Palestras');
	$this->assign('nav','palestras');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB.script("scripts/app/palestras.js").wait(function(){
		$(document).ready(function(){
			page.init();
		});
		
		// hack for IE9 which may respond inconsistently with document.ready
		setTimeout(function(){
			if (!page.isInitialized) page.init();
		},1000);
	});
</script>

<div class="container">

<h1>
	<i class="icon-th-list"></i> Palestras
	<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
	<span class='input-append pull-right searchContainer'>
		<input id='filter' type="text" placeholder="Buscar..." />
		<button class='btn add-on'><i class="icon-search"></i></button>
	</span>
</h1>

	<!-- underscore template for the collection -->
	<script type="text/template" id="palestraCollectionTemplate">
		<table class="collection table table-hover table-striped">
		<thead>
			<tr>
				<th id="header_IdPalestra">Id Palestra<% if (page.orderBy == 'IdPalestra') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Nome">Nome<% if (page.orderBy == 'Nome') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Data">Data<% if (page.orderBy == 'Data') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_CargaHoraria">Carga Horaria<% if (page.orderBy == 'CargaHoraria') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_ProprioEvento">Proprio Evento<% if (page.orderBy == 'ProprioEvento') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
<!-- UNCOMMENT TO SHOW ADDITIONAL COLUMNS
				<th id="header_IdEvento">Id Evento<% if (page.orderBy == 'IdEvento') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_IdModeloCertificado">Id Modelo Certificado<% if (page.orderBy == 'IdModeloCertificado') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
-->
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('idPalestra')) %>">
				<td><%= _.escape(item.get('idPalestra') || '') %></td>
				<td><%= _.escape(item.get('nome') || '') %></td>
				<td><%if (item.get('data')) { %><%= _date(app.parseDate(item.get('data'))).format('MMM D, YYYY') %><% } else { %>NULL<% } %></td>
				<td><%if (item.get('cargaHoraria')) { %><%= _date(app.parseDate(item.get('cargaHoraria'))).format('h:mm A') %><% } else { %>NULL<% } %></td>
				<td><%= _.escape(item.get('proprioEvento') || '') %></td>
<!-- UNCOMMENT TO SHOW ADDITIONAL COLUMNS
				<td><%= _.escape(item.get('idEvento') || '') %></td>
				<td><%= _.escape(item.get('idModeloCertificado') || '') %></td>
-->
			</tr>
		<% }); %>
		</tbody>
		</table>

		<%=  view.getPaginationHtml(page) %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="palestraModelTemplate">
		<form class="form-horizontal" onsubmit="return false;">
			<fieldset>
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
						<div class="input-append date date-picker" data-date-format="yyyy-mm-dd">
							<input id="data" type="text" value="<%= _date(app.parseDate(item.get('data'))).format('YYYY-MM-DD') %>" />
							<span class="add-on"><i class="icon-calendar"></i></span>
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="cargaHorariaInputContainer" class="control-group">
					<label class="control-label" for="cargaHoraria">Carga Horaria</label>
					<div class="controls inline-inputs">
						<div class="input-append bootstrap-timepicker-component">
							<input id="cargaHoraria" type="text" class="timepicker-default input-small" value="<%= _date(app.parseDate(item.get('cargaHoraria'))).format('h:mm A') %>" />
							<span class="add-on"><i class="icon-time"></i></span>
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="proprioEventoInputContainer" class="control-group">
					<label class="control-label" for="proprioEvento">Proprio Evento</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="proprioEvento" placeholder="Proprio Evento" value="<%= _.escape(item.get('proprioEvento') || '') %>">
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
				<i class="icon-edit"></i> Editar Palestra
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="palestraModelContainer"></div>
		</div>
		<div class="modal-footer">
			<button id="saveCertificadoButton" class="btn btn-primary">Salvar</button>
			<button class="btn" data-dismiss="modal" >Cancelar</button>
		</div>
	</div>

	<div id="collectionAlert"></div>
	
	<div id="palestraCollectionContainer" class="collectionContainer">
	</div>

	<p id="newButtonContainer" class="buttonContainer">
		<button id="newPalestraButton" class="btn btn-primary">Cadastrar Palestra</button>
	</p>

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
