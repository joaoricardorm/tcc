<?php
	$this->assign('title','Modelo dos Certificados - Certifica-µ');
	$this->assign('nav','modelocertificados');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB.script(base+"scripts/app/modelocertificados.js").wait(function(){
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
	<i class="icon-th-list"></i> ModeloCertificados
	<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
	<span class='input-append pull-right searchContainer'>
		<input id='filter' type="text" placeholder="Buscar..." />
		<button class='btn add-on'><i class="icon-search"></i></button>
	</span>
</h1>

	<!-- underscore template for the collection -->
	<script type="text/template" id="modeloCertificadoCollectionTemplate">
		<table class="collection table table-hover table-striped">
		<thead>
			<tr>
				<th id="header_IdModeloCertificado">Id Modelo Certificado<% if (page.orderBy == 'IdModeloCertificado') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Nome">Nome<% if (page.orderBy == 'Nome') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_TextoParticipante">Texto Participante<% if (page.orderBy == 'TextoParticipante') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_TextoPalestrante">Texto Palestrante<% if (page.orderBy == 'TextoPalestrante') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_ArquivoCss">Arquivo Css<% if (page.orderBy == 'ArquivoCss') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
<!-- UNCOMMENT TO SHOW ADDITIONAL COLUMNS
				<th id="header_Elementos">Elementos<% if (page.orderBy == 'Elementos') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
-->
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('idModeloCertificado')) %>">
				<td><%= _.escape(item.get('idModeloCertificado') || '') %></td>
				<td><%= _.escape(item.get('nome') || '') %></td>
				<td><%= _.escape(item.get('textoParticipante') || '') %></td>
				<td><%= _.escape(item.get('textoPalestrante') || '') %></td>
				<td><%= _.escape(item.get('arquivoCss') || '') %></td>
<!-- UNCOMMENT TO SHOW ADDITIONAL COLUMNS
				<td><%= _.escape(item.get('elementos') || '') %></td>
-->
			</tr>
		<% }); %>
		</tbody>
		</table>

		<%=  view.getPaginationHtml(page) %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="modeloCertificadoModelTemplate">
		<form class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div id="idModeloCertificadoInputContainer" class="control-group">
					<label class="control-label" for="idModeloCertificado">Id Modelo Certificado</label>
					<div class="controls inline-inputs">
						<span class="input-xlarge uneditable-input" id="idModeloCertificado"><%= _.escape(item.get('idModeloCertificado') || '') %></span>
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
				<div id="textoParticipanteInputContainer" class="control-group">
					<label class="control-label" for="textoParticipante">Texto Participante</label>
					<div class="controls inline-inputs">
						<textarea class="input-xlarge" id="textoParticipante" rows="3"><%= _.escape(item.get('textoParticipante') || '') %></textarea>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="textoPalestranteInputContainer" class="control-group">
					<label class="control-label" for="textoPalestrante">Texto Palestrante</label>
					<div class="controls inline-inputs">
						<textarea class="input-xlarge" id="textoPalestrante" rows="3"><%= _.escape(item.get('textoPalestrante') || '') %></textarea>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="arquivoCssInputContainer" class="control-group">
					<label class="control-label" for="arquivoCss">Arquivo Css</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="arquivoCss" placeholder="Arquivo Css" value="<%= _.escape(item.get('arquivoCss') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="elementosInputContainer" class="control-group">
					<label class="control-label" for="elementos">Elementos</label>
					<div class="controls inline-inputs">
						<textarea class="input-xlarge" id="elementos" rows="3"><%= _.escape(item.get('elementos') || '') %></textarea>
						<span class="help-inline"></span>
					</div>
				</div>
			</fieldset>
		</form>

		<!-- delete button is is a separate form to prevent enter key from triggering a delete -->
		<form id="deleteModeloCertificadoButtonContainer" class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
						<button id="deleteModeloCertificadoButton" class="btn btn-danger"><i class="icon-trash icon-white"></i> Excluir ModeloCertificado</button>
						<span id="confirmDeleteModeloCertificadoContainer" class="hide">
							<button id="cancelDeleteModeloCertificadoButton" class="btn">Cancelar</button>
							<button id="confirmDeleteModeloCertificadoButton" class="btn btn-success">Confirmar</button>
						</span>
					</div>
				</div>
			</fieldset>
		</form>
	</script>

	<!-- modal edit dialog -->
	<div class="modal hide fade animated bounceIn modal-on-top"  data-backdrop="static" id="modeloCertificadoDetailDialog">
		<div class="modal-header">
			<a class="close btn btn-danger btn-big" data-dismiss="modal">&times;</a>
			<h3>
				<i id="icone-acao-modal" class="icon-edit"></i> <span id="titulo-modal">Editar</span> ModeloCertificado
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="modeloCertificadoModelContainer"></div>
		</div>
		<div class="modal-footer">
			<button id="saveModeloCertificadoButton" class="btn btn-primary">Salvar</button>
			<button class="btn" data-dismiss="modal" >Cancelar</button>
		</div>
	</div>

	<div id="collectionAlert"></div>
	
	<div id="modeloCertificadoCollectionContainer" class="collectionContainer">
	</div>

	<p id="newButtonContainer" class="buttonContainer">
		<button id="newModeloCertificadoButton" class="btn btn-primary">Cadastrar ModeloCertificado</button>
	</p>

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
