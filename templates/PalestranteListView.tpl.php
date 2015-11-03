<?php
	$this->assign('title','Palestrantes - Certifica-μ');
	$this->assign('nav','palestrantes');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB
	.script(base+"scripts/Simple-Ajax-Uploader/SimpleAjaxUploader.js").wait()
	.script(base+"scripts/app/palestrantes.js").wait(function(){
		$(document).ready(function(){
			page.init();
		});
		
		// hack for IE9 which may respond inconsistently with document.ready
		setTimeout(function(){
			if (!page.isInitialized) page.init();
		},1000);
	});
</script>

<link href="scripts/Simple-Ajax-Uploader/styles.css" type="text/css" rel="stylesheet">

<?php if($this->Palestra){ ?>

<nav class="container hero-unit small">
		<ol class="cd-multi-steps text-top">		
			<li class="visited"><a href="evento/<?php $this->eprint( $this->Palestra->IdEvento . '/'. AppBaseController::parseURL($this->Evento->Nome ) . '/' ); ?>"><i class="icon-tags"></i><?php $this->eprint($this->Evento->Nome); ?></a></li> <!-- Classe "visited" -->
			<li class="visited"><a href="evento/<?php $this->eprint( $this->Palestra->IdEvento . '/atividades/' . $this->Palestra->IdPalestra . '/'. AppBaseController::parseURL($this->Palestra->Nome) . '/' ); ?>"><i class="icon-tags"></i><?php echo ($this->Palestra->ProprioEvento) ? 'Detalhes do evento' : $this->eprint( $this->Palestra->Nome ) ; ?></a></li>
			<li class="current"><span><i class="icon-microphone"></i>Palestrantes</span></li>	
			<li><a href="evento/<?php $this->eprint($this->Palestra->IdEvento . '/atividades/' . $this->Palestra->IdPalestra . '/'. AppBaseController::parseURL($this->Palestra->Nome) . '/participantes/' ); ?>"><i class="icon-group"></i>Participantes</a></li>
		</ol>
</nav>		

<div class="container hero-unit">


<p><a href="evento/<?php $this->eprint( $this->Palestra->IdEvento . '/atividades/' . $this->Palestra->IdPalestra . '/'. AppBaseController::parseURL($this->Palestra->Nome) . '/' ); ?>">
	<button class="btn btn-default block-sm"><i class="icon-arrow-left"></i>Outras informações <?php echo ($this->Palestra->ProprioEvento) ? 'do evento' : 'da atividade'; ?></button>
</a></p>

<?php } ?>


<h1>
	<i class="icon-microphone"></i> Palestrantes <?php echo ($this->Palestra->ProprioEvento) ? 'do evento' : 'da atividade'; ?> <?php $this->eprint($this->Palestra->Nome); ?> 
	<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
</h1>

<div class="new-and-search-container clearfix">
	<span id="newButtonContainer" class="buttonContainer pull-left">
		<button id="newPalestranteButton" class="btn btn-primary"><i class="icon-plus"></i> Cadastrar Palestrante</button>
	</span>
	<span class='input-append pull-right searchContainer'>
		<input id='filter' type="text" placeholder="Buscar..." />
		<button class='btn add-on'><i class="icon-search"></i></button>
	</span>
</div>

	<!-- underscore template for the collection -->
	<script type="text/template" id="palestranteCollectionTemplate">
	
	
	<% if(items.length === 0){ %>
			<hr>
			<h3>Nenhum palestrante encontrado</h3>
	<% } else { %>
		
	<p class="ordemCadastro" id="ordemCadastro_IdPalestrante">
		<a href="#">
		<% if (page.orderBy == 'IdPalestrante') { %> 
			<%= page.orderDesc ? "Mostrar antigos primeiro <i class='icon-arrow-down' />" : "Mostrar recentes primeiro <i class='icon-arrow-up' />" %>
		<% } else { %>
			Ordenar pelo cadastro no sistema <i class='icon-arrow-down' />
		<% } %>
		</a>
	</p>
	
	<div id="no-more-tables">
	
		<table class="collection table table-hover table-striped">
		<thead> 
			<tr>
				<th id="header_Nome"><i class="icon icon-user"></i>Nome<% if (page.orderBy == 'Nome') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Email"><i class="icon icon-envelope"></i>E-mail<% if (page.orderBy == 'Email') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Cpf"><i class="icon icon-user"></i>CPF<% if (page.orderBy == 'Cpf') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Cargo"><i class="icon icon-briefcase"></i>Cargo<% if (page.orderBy == 'Cargo') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_ImagemAssinatura"><i class="icon icon-picture"></i>Assinatura<% if (page.orderBy == 'ImagemAssinatura') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('idPalestrante')) %>">
				<td><%= _.escape(item.get('nome') || '') %></td>
				<td><%= _.escape(item.get('email') || '') %></td>
				<td><%= _.escape(item.get('cpf') || '') %></td>
				<td><%= _.escape(item.get('cargo') || '') %></td>
				<td><% if (_.escape(item.get('imagemAssinatura')) != '') { %><img class="thumbnail small" src="images/uploads/logos/small/<%= _.escape(item.get('imagemAssinatura') || '') %>" /><% } %></td>
			</tr>
		<% }); %>
		</tbody>
		</table>
		
		</div>

		<%=  view.getPaginationHtml(page) %>
		
	<%}%>
	
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="palestranteModelTemplate">
		<form class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div id="nomeInputContainer" class="control-group">
					<label class="control-label" for="nome">Nome</label>
					<div class="controls inline-inputs">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-user"></i></span>
							<input type="text" class="input-xlarge" id="nome" placeholder="Nome" value="<%= _.escape(item.get('nome') || '') %>">
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="emailInputContainer" class="control-group">
					<label class="control-label" for="email">E-mail</label>
					<div class="controls inline-inputs">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-envelope"></i></span>
							<input type="text" class="input-xlarge" id="email" placeholder="E-mail" value="<%= _.escape(item.get('email') || '') %>">
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="cpfInputContainer" class="control-group">
					<label class="control-label" for="cpf">CPF</label>
					<div class="controls inline-inputs">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-user"></i></span>
							<input type="text" class="input-xlarge" id="cpf" placeholder="CPF" value="<%= _.escape(item.get('cpf') || '') %>">
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="cargoInputContainer" class="control-group">
					<label class="control-label" for="cargo">Cargo</label>
					<div class="controls inline-inputs">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-briefcase"></i></span>
							<input type="text" class="input-xlarge" id="cargo" placeholder="Cargo" value="<%= _.escape(item.get('cargo') || '') %>">
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
				
				
				
				
				
				<div id="imagemLogoInputContainer" class="control-group">
					<label class="control-label">Assinatura</label>
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
					
					
						<% if (item.get('imagemAssinatura') != '') { %>
							<img class="thumb-imagem-alterar thumbnail small" src="images/uploads/logos/small/<%= _.escape(item.get('imagemAssinatura') || '') %>" />
						<% } %>
						
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
	<div class="modal hide fade animated bounceIn animated bounceIn modal-on-top"  data-backdrop="static" id="palestranteDetailDialog">
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

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
