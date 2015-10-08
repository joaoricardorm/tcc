<?php
	$this->assign('title','Certificados FAROL | PalestraParticipantes');
	$this->assign('nav','palestraparticipantes');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB.script(base+"scripts/app/palestraparticipantes.js").wait(function(){
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
	<i class="icon-th-list"></i> PalestraParticipantes
	<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
	<span class='input-append pull-right searchContainer'>
		<input id='filter' type="text" placeholder="Buscar..." />
		<button class='btn add-on'><i class="icon-search"></i></button>
	</span>
</h1>

	<!-- underscore template for the collection -->
	<script type="text/template" id="palestraParticipanteCollectionTemplate">
		<table class="collection table table-hover table-striped">
		<thead>
			<tr>
				<th id="header_Id">Id<% if (page.orderBy == 'Id') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Presenca">Presenca<% if (page.orderBy == 'Presenca') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_IdParticipante">Id Participante<% if (page.orderBy == 'IdParticipante') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_IdPalestra">Id Palestra<% if (page.orderBy == 'IdPalestra') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_IdCertificado">Id Certificado<% if (page.orderBy == 'IdCertificado') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('id')) %>">
				<td><%= _.escape(item.get('id') || '') %></td>
				<td><%= _.escape(item.get('presenca') || '') %></td>
				<td><%= _.escape(item.get('idParticipante') || '') %></td>
				<td><%= _.escape(item.get('idPalestra') || '') %></td>
				<td><%= _.escape(item.get('idCertificado') || '') %></td>
			</tr>
		<% }); %>
		</tbody>
		</table>

		<%=  view.getPaginationHtml(page) %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="palestraParticipanteModelTemplate">
		<form class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div id="idInputContainer" class="control-group">
					<label class="control-label" for="id">Id</label>
					<div class="controls inline-inputs">
						<span class="input-xlarge uneditable-input" id="id"><%= _.escape(item.get('id') || '') %></span>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="presencaInputContainer" class="control-group">
					<label class="control-label" for="presenca">Presenca</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="presenca" placeholder="Presenca" value="<%= _.escape(item.get('presenca') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="idParticipanteInputContainer" class="control-group">
					<label class="control-label" for="idParticipante">Id Participante</label>
					<div class="controls inline-inputs">
						<select id="idParticipante" name="idParticipante"></select>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="idPalestraInputContainer" class="control-group">
					<label class="control-label" for="idPalestra">Id Palestra</label>
					<div class="controls inline-inputs">
						<select id="idPalestra" name="idPalestra"></select>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="idCertificadoInputContainer" class="control-group">
					<label class="control-label" for="idCertificado">Id Certificado</label>
					<div class="controls inline-inputs">
						<select id="idCertificado" name="idCertificado"></select>
						<span class="help-inline"></span>
					</div>
				</div>
			</fieldset>
		</form>

		<!-- delete button is is a separate form to prevent enter key from triggering a delete -->
		<form id="deletePalestraParticipanteButtonContainer" class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
						<button id="deletePalestraParticipanteButton" class="btn btn-danger"><i class="icon-trash icon-white"></i> Excluir PalestraParticipante</button>
						<span id="confirmDeletePalestraParticipanteContainer" class="hide">
							<button id="cancelDeletePalestraParticipanteButton" class="btn">Cancelar</button>
							<button id="confirmDeletePalestraParticipanteButton" class="btn btn-success">Confirmar</button>
						</span>
					</div>
				</div>
			</fieldset>
		</form>
	</script>

	<!-- modal edit dialog -->
	<div class="modal hide fade" id="palestraParticipanteDetailDialog">
		<div class="modal-header">
			<a class="close btn btn-danger btn-big" data-dismiss="modal">&times;</a>
			<h3>
				<i id="icone-acao-modal" class="icon-edit"></i> <span id="titulo-modal">Editar</span> PalestraParticipante
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="palestraParticipanteModelContainer"></div>
		</div>
		<div class="modal-footer">
			<button id="saveCertificadoButton" class="btn btn-primary">Salvar</button>
			<button class="btn" data-dismiss="modal" >Cancelar</button>
		</div>
	</div>

	<div id="collectionAlert"></div>
	
	<div id="palestraParticipanteCollectionContainer" class="collectionContainer">
	</div>

	<p id="newButtonContainer" class="buttonContainer">
		<button id="newPalestraParticipanteButton" class="btn btn-primary">Cadastrar PalestraParticipante</button>
	</p>

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
