<?php
	$this->assign('title','Certificados FAROL | Configuracoes');
	$this->assign('nav','configuracoes');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB.script(base+"scripts/app/configuracoes.js").wait(function(){
		$(document).ready(function(){
			page.init();
		});
		
		// hack for IE9 which may respond inconsistently with document.ready
		setTimeout(function(){
			if (!page.isInitialized) page.init();
		},1000);
	});
</script>

<div class="container hero-unit">

	<h1>
		<i class="icon-user"></i> Configurações
		<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
	</h1>

	<p id="newButtonContainer" class="buttonContainer">
			<button id="newConfiguracaoButton" class="btn btn-primary">Editar configurações</button>
			
			<span class='input-append pull-right searchContainer'>
					<input id='filter' type="text" placeholder="Buscar..." />
					<button class='btn add-on'><i class="icon-search"></i></button>
			</span>
	</p>

	<!-- underscore template for the collection -->
	<script type="text/template" id="configuracaoCollectionTemplate">
		<table class="collection table table-hover table-striped">
		<thead>
			<tr>
				<th id="header_IdConfiguracao">Id Configuracao<% if (page.orderBy == 'IdConfiguracao') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_NomeInstituicao">Nome Instituicao<% if (page.orderBy == 'NomeInstituicao') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_ImagemLogo">Imagem Logo<% if (page.orderBy == 'ImagemLogo') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Cnpj">Cnpj<% if (page.orderBy == 'Cnpj') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Telefone">Telefone<% if (page.orderBy == 'Telefone') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('idConfiguracao')) %>">
				<td><%= _.escape(item.get('idConfiguracao') || '') %></td>
				<td><%= _.escape(item.get('nomeInstituicao') || '') %></td>
				<td><%= _.escape(item.get('imagemLogo') || '') %></td>
				<td><%= _.escape(item.get('cnpj') || '') %></td>
				<td><%= _.escape(item.get('telefone') || '') %></td>
			</tr>
		<% }); %>
		</tbody>
		</table>

		<%=  view.getPaginationHtml(page) %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="configuracaoModelTemplate">
		<form class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div id="idConfiguracaoInputContainer" class="control-group">
					<label class="control-label" for="idConfiguracao">Id Configuracao</label>
					<div class="controls inline-inputs">
						<span class="input-xlarge uneditable-input" id="idConfiguracao"><%= _.escape(item.get('idConfiguracao') || '') %></span>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="nomeInstituicaoInputContainer" class="control-group">
					<label class="control-label" for="nomeInstituicao">Nome Instituicao</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="nomeInstituicao" placeholder="Nome Instituicao" value="<%= _.escape(item.get('nomeInstituicao') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="imagemLogoInputContainer" class="control-group">
					<label class="control-label" for="imagemLogo">Imagem Logo</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="imagemLogo" placeholder="Imagem Logo" value="<%= _.escape(item.get('imagemLogo') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="cnpjInputContainer" class="control-group">
					<label class="control-label" for="cnpj">Cnpj</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="cnpj" placeholder="Cnpj" value="<%= _.escape(item.get('cnpj') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="telefoneInputContainer" class="control-group">
					<label class="control-label" for="telefone">Telefone</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="telefone" placeholder="Telefone" value="<%= _.escape(item.get('telefone') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
			</fieldset>
		</form>

		<!-- delete button is is a separate form to prevent enter key from triggering a delete -->
		<form id="deleteConfiguracaoButtonContainer" class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
						<button id="deleteConfiguracaoButton" class="btn btn-danger"><i class="icon-trash icon-white"></i> Excluir Configuracao</button>
						<span id="confirmDeleteConfiguracaoContainer" class="hide">
							<button id="cancelDeleteConfiguracaoButton" class="btn">Cancelar</button>
							<button id="confirmDeleteConfiguracaoButton" class="btn btn-success">Confirmar</button>
						</span>
					</div>
				</div>
			</fieldset>
		</form>
	</script>

	<!-- modal edit dialog -->
	<div class="modal hide fade animated bounceIn modal-on-top"  data-backdrop="static" id="configuracaoDetailDialog">
		<div class="modal-header">
			<a class="close btn btn-danger btn-big" data-dismiss="modal">&times;</a>
			<h3>
				<i id="icone-acao-modal" class="icon-edit"></i> <span id="titulo-modal">Editar</span> Configuracao
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="configuracaoModelContainer"></div>
		</div>
		<div class="modal-footer">
			<button id="saveConfiguracaoButton" class="btn btn-primary">Salvar</button>
			<button class="btn" data-dismiss="modal" >Cancelar</button>
		</div>
	</div>

	<div id="collectionAlert"></div>
	
	<div id="configuracaoCollectionContainer" class="collectionContainer">
	</div>
</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
