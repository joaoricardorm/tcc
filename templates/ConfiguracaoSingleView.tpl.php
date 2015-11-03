<?php
	$this->assign('title','Configurações - Certifica-μ');
	$this->assign('nav','configuracao');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">	
	$LAB
	.script(base+"scripts/Simple-Ajax-Uploader/SimpleAjaxUploader.js").wait()
	.script(base+"scripts/app/configuracoes.js").wait(function(){
		$(document).ready(function(){			
			page.init();	
		});
		
		// hack for IE9 which may respond inconsistently with document.ready
		setTimeout(function(){
			if (!page.isInitialized) page.init();	},1000);
	});
</script>

<link href="scripts/Simple-Ajax-Uploader/styles.css" type="text/css" rel="stylesheet">

<div class="container">
	
<div class="container hero-unit">

	<h1>
		<i class="icon-cogs"></i> Configurações
		<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
	</h1>

	<p id="newButtonContainer" class="buttonContainer">
			<button id="newConfiguracaoButton" class="btn btn-primary"><i id="icone-acao-modal" class="icon-edit"></i> <span id="titulo-modal">Editar</span> configurações</button>
	</p>

	<!-- underscore template for the collection -->
	<script type="text/template" id="configuracaoCollectionTemplate">
	
	 <div id="no-more-tables">
		<table class="collection table table-hover table-striped no-hover responsive-table">
		<thead>
			<tr>
				<th id="header_NomeInstituicao"><i class="icon icon-briefcase"></i>Nome da instituição</th>
				<th id="header_ImagemLogo"><i class="icon icon-picture"></i>Logotipo</th>
				<th id="header_Cnpj"><i class="icon icon-briefcase"></i>CNPJ</th>
				<th id="header_Telefone"><i class="icon icon-phone"></i>Telefone</th>
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('idConfiguracao')) %>">
				<td data-title="vai"><%= _.escape(item.get('nomeInstituicao') || '') %></td>
				<td><% if (_.escape(item.get('imagemLogo')) != '') { %><img class="thumbnail" src="images/uploads/logos/small/<%= _.escape(item.get('imagemLogo') || '') %>" /><% } %></td>
				<td><%= _.escape(item.get('cnpj') || '') %></td>
				<td><%= _.escape(item.get('telefone') || '') %></td>
			</tr>
		<% }); %>
		</tbody>
		</table>
	</div>

		<%=  view.getPaginationHtml(page) %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="configuracaoModelTemplate">	
		<form class="form-horizontal" enctype="multipart/form-data" onsubmit="return false;">
			<fieldset>				
				<div id="nomeInstituicaoInputContainer" class="control-group">
					<label class="control-label" for="nomeInstituicao">Nome da instituição</label>
					<div class="controls inline-inputs">
					
						<div class="input-prepend">
							<span class="add-on"><i class="icon-briefcase"></i></span>					
							<input type="text" class="input-xlarge" id="nomeInstituicao" placeholder="Nome da instituição" value="<%= _.escape(item.get('nomeInstituicao') || '') %>">
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="imagemLogoInputContainer" class="control-group">
					<label class="control-label">Imagem de logotipo</label>
					<div class="controls inline-inputs">
					
						<label id="lblAltImagem" for="ckbAltImagem" style="margin-bottom:0;">
							<input type="checkbox" id="ckbAltImagem"> 
							<button id="btnAltImagem" class="btn btn-primary"><i class="icon-picture"></i> Alterar imagem</button>
						</label>
						
						<!--upload-->
						<div id="UploadAltImagem">
							<div>
							  <button id="uploadBtn" class="btn btn-primary"><i class="icon-picture"></i> Escolher arquivo</button><input id="nomeArquivo" value="Nenhum arquivo selecionado" type="button" disabled="disabled" class="btn text-small">
							</div>
							<div>
							  <div id="progressOuter" class="progress progress-striped active" style="display:none;">
								<div id="progressBar" class="progress-bar progress-bar-success"  role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
								</div>
							  </div>
							</div>
				
							<span id="msgBox" class="help-inline error"></span>
							
							<div>
								<img class="up-image-preview thumbnail" id="preview" src="#" />
							</div>
						</div>
						<!--upload-->
					
					</div>
				</div>
				<div id="cnpjInputContainer" class="control-group">
					<label class="control-label" for="cnpj">CNPJ</label>
					<div class="controls inline-inputs">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-briefcase"></i></span>
							<input type="text" class="input-xlarge" id="cnpj" placeholder="CNPJ" value="<%= _.escape(item.get('cnpj') || '') %>">
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="telefoneInputContainer" class="control-group">
					<label class="control-label" for="telefone">Telefone</label>
					<div class="controls inline-inputs">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-phone"></i></span>
							<input type="text" class="input-xlarge" id="telefone" placeholder="Telefone" value="<%= _.escape(item.get('telefone') || '') %>">
						</div>
						<span class="help-inline"></span>
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
				<i id="icone-acao-modal" class="icon-edit"></i> <span id="titulo-modal">Editar</span> Configurações
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
			
