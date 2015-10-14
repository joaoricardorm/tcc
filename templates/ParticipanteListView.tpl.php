<?php
	$this->assign('title','Certificados FAROL | Participantes');
	$this->assign('nav','participantes');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB.script(base+"scripts/app/participantes.js").wait(function(){
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
	<i class="icon-th-list"></i> Participantes
	<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
	<span class='input-append pull-right searchContainer'>
		<input id='filter' type="text" placeholder="Buscar..." />
		<button class='btn add-on'><i class="icon-search"></i></button>
	</span>
</h1>

	<!-- underscore template for the collection -->
	<script type="text/template" id="participanteCollectionTemplate">
		<table class="collection table table-hover table-striped">
		<thead>
			<tr>
				<th id="header_IdParticipante">Id Participante<% if (page.orderBy == 'IdParticipante') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Nome">Nome<% if (page.orderBy == 'Nome') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Email">Email<% if (page.orderBy == 'Email') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Cpf">Cpf<% if (page.orderBy == 'Cpf') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('idParticipante')) %>">
				<td><%= _.escape(item.get('idParticipante') || '') %></td>
				<td><%= _.escape(item.get('nome') || '') %></td>
				<td><%= _.escape(item.get('email') || '') %></td>
				<td><%= _.escape(item.get('cpf') || '') %></td>
			</tr>
		<% }); %>
		</tbody>
		</table>

		<%=  view.getPaginationHtml(page) %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="participanteModelTemplate">
		<form class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div id="idParticipanteInputContainer" class="control-group">
					<label class="control-label" for="idParticipante">Id Participante</label>
					<div class="controls inline-inputs">
						<span class="input-xlarge uneditable-input" id="idParticipante"><%= _.escape(item.get('idParticipante') || '') %></span>
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
				<div id="emailInputContainer" class="control-group">
					<label class="control-label" for="email">Email</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="email" placeholder="Email" value="<%= _.escape(item.get('email') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="cpfInputContainer" class="control-group">
					<label class="control-label" for="cpf">Cpf</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="cpf" placeholder="Cpf" value="<%= _.escape(item.get('cpf') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
			</fieldset>
		</form>

		<!-- delete button is is a separate form to prevent enter key from triggering a delete -->
		<form id="deleteParticipanteButtonContainer" class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
						<button id="deleteParticipanteButton" class="btn btn-danger"><i class="icon-trash icon-white"></i> Excluir Participante</button>
						<span id="confirmDeleteParticipanteContainer" class="hide">
							<button id="cancelDeleteParticipanteButton" class="btn">Cancelar</button>
							<button id="confirmDeleteParticipanteButton" class="btn btn-success">Confirmar</button>
						</span>
					</div>
				</div>
			</fieldset>
		</form>
	</script>

	<!-- modal edit dialog -->
	<div class="modal hide fade animated bounceIn modal-on-top"  data-backdrop="static" id="participanteDetailDialog">
		<div class="modal-header">
			<a class="close btn btn-danger btn-big" data-dismiss="modal">&times;</a>
			<h3>
				<i id="icone-acao-modal" class="icon-edit"></i> <span id="titulo-modal">Editar</span> Participante
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="participanteModelContainer"></div>
		</div>
		<div class="modal-footer">
			<button id="saveCertificadoButton" class="btn btn-primary">Salvar</button>
			<button class="btn" data-dismiss="modal" >Cancelar</button>
		</div>
	</div>

	<div id="collectionAlert"></div>
	
	<div id="participanteCollectionContainer" class="collectionContainer">
	</div>

	<p id="newButtonContainer" class="buttonContainer">
		<button id="newParticipanteButton" class="btn btn-primary">Cadastrar Participante</button>
	</p>

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
