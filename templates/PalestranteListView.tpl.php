<?php
	$this->assign('title','Certificados FAROL | Palestrantes');
	$this->assign('nav','palestrantes');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB.script(base+"scripts/app/palestrantes.js").wait(function(){
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
	<i class="icon-th-list"></i> Palestrantes
	<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
	<span class='input-append pull-right searchContainer'>
		<input id='filter' type="text" placeholder="Buscar..." />
		<button class='btn add-on'><i class="icon-search"></i></button>
	</span>
</h1>

	<!-- underscore template for the collection -->
	<script type="text/template" id="palestranteCollectionTemplate">
		<table class="collection table table-hover table-striped">
		<thead>
			<tr>
				<th id="header_IdPalestrante">Id Palestrante<% if (page.orderBy == 'IdPalestrante') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Nome">Nome<% if (page.orderBy == 'Nome') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Email">Email<% if (page.orderBy == 'Email') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Cpf">Cpf<% if (page.orderBy == 'Cpf') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Cargo">Cargo<% if (page.orderBy == 'Cargo') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
<!-- UNCOMMENT TO SHOW ADDITIONAL COLUMNS
				<th id="header_ImagemAssinatura">Imagem Assinatura<% if (page.orderBy == 'ImagemAssinatura') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
-->
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('idPalestrante')) %>">
				<td><%= _.escape(item.get('idPalestrante') || '') %></td>
				<td><%= _.escape(item.get('nome') || '') %></td>
				<td><%= _.escape(item.get('email') || '') %></td>
				<td><%= _.escape(item.get('cpf') || '') %></td>
				<td><%= _.escape(item.get('cargo') || '') %></td>
<!-- UNCOMMENT TO SHOW ADDITIONAL COLUMNS
				<td><%= _.escape(item.get('imagemAssinatura') || '') %></td>
-->
			</tr>
		<% }); %>
		</tbody>
		</table>

		<%=  view.getPaginationHtml(page) %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="palestranteModelTemplate">
		<form class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div id="idPalestranteInputContainer" class="control-group">
					<label class="control-label" for="idPalestrante">Id Palestrante</label>
					<div class="controls inline-inputs">
						<span class="input-xlarge uneditable-input" id="idPalestrante"><%= _.escape(item.get('idPalestrante') || '') %></span>
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
				<div id="cargoInputContainer" class="control-group">
					<label class="control-label" for="cargo">Cargo</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="cargo" placeholder="Cargo" value="<%= _.escape(item.get('cargo') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="imagemAssinaturaInputContainer" class="control-group">
					<label class="control-label" for="imagemAssinatura">Imagem Assinatura</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="imagemAssinatura" placeholder="Imagem Assinatura" value="<%= _.escape(item.get('imagemAssinatura') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
			</fieldset>
		</form>

		<!-- delete button is is a separate form to prevent enter key from triggering a delete -->
		<form id="deletePalestranteButtonContainer" class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
						<button id="deletePalestranteButton" class="btn btn-danger"><i class="icon-trash icon-white"></i> Excluir Palestrante</button>
						<span id="confirmDeletePalestranteContainer" class="hide">
							<button id="cancelDeletePalestranteButton" class="btn">Cancelar</button>
							<button id="confirmDeletePalestranteButton" class="btn btn-success">Confirmar</button>
						</span>
					</div>
				</div>
			</fieldset>
		</form>
	</script>

	<!-- modal edit dialog -->
	<div class="modal hide fade" id="palestranteDetailDialog">
		<div class="modal-header">
			<a class="close btn btn-danger btn-big" data-dismiss="modal">&times;</a>
			<h3>
				<i id="icone-acao-modal" class="icon-edit"></i> <span id="titulo-modal">Editar</span> Palestrante
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="palestranteModelContainer"></div>
		</div>
		<div class="modal-footer">
			<button id="savePalestranteButton" class="btn btn-primary">Salvar</button>
			<button class="btn" data-dismiss="modal" >Cancelar</button>
		</div>
	</div>

	<div id="collectionAlert"></div>
	
	<div id="palestranteCollectionContainer" class="collectionContainer">
	</div>

	<p id="newButtonContainer" class="buttonContainer">
		<button id="newPalestranteButton" class="btn btn-primary">Cadastrar Palestrante</button>
	</p>

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
