<?php
	$this->assign('title','Usuários - Certifica-μ');
	$this->assign('nav','usuarios');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB.script(base+"scripts/app/usuarios.js").wait(function(){
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
	<i class="icon-user"></i> Usuários
	<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
</h1>

<div class="new-and-search-container clearfix">
	<span id="newButtonContainer" class="buttonContainer pull-left">
			<button id="newUsuarioButton" class="btn btn-primary"><i class="icon-plus"></i> Cadastrar Usuário</button>
	</span>
	<span class='input-append pull-right searchContainer'>
					<input id='filter' type="text" placeholder="Buscar..." />
					<button class='btn add-on'><i class="icon-search"></i></button>
	</span>
</div>

	<!-- underscore template for the collection -->
	<script type="text/template" id="usuarioCollectionTemplate">
	
	<% if(items.length === 0){ %>
			<hr>
			<h3>Nenhum usuário encontrado</h3>
	<% } else { %>
	
	<p class="ordemCadastro" id="ordemCadastro_IdUsuario">
		<a href="#">
		<% if (page.orderBy == 'IdUsuario') { %> 
			<%= page.orderDesc ? "Mostrar antigos primeiro <i class='icon-arrow-down' />" : "Mostrar recentes primeiro <i class='icon-arrow-up' />" %>
		<% } else { %>
			Ordenar pelo cadastro no sistema <i class='icon-arrow-down' />
		<% } %>
		</a>
	</p>
	
	<div id="no-more-tables">
	
		<table class="collection table table-hover table-striped responsive-table">
			<thead>
				<tr>
					<th id="header_Nome"><i class="icon icon-user"></i>Nome<% if (page.orderBy == 'Nome') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
					<th id="header_Email"><i class="icon icon-envelope"></i>E-mail<% if (page.orderBy == 'Email') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
					<th id="header_Login"><i class="icon icon-smile"></i>Usuário<% if (page.orderBy == 'Login') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
					<th id="header_TipoUsuario"><i class="icon icon-lock"></i>Tipo de usuário<% if (page.orderBy == 'TipoUsuario') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				</tr>
			</thead>
			<tbody>
			<% items.each(function(item) { %>
				<tr id="<%= _.escape(item.get('idUsuario')) %>">
					<td><%= _.escape(item.get('nome') || '') %></td>
					<td><%= _.escape(item.get('email') || '') %></td>
					<td><%= _.escape(item.get('login') || '') %></td>
					<td><%= _.escape(item.get('tipoUsuario')) == '1' ? 'Administrador' : 'Padrão' %></td>
				</tr>
			<% }); %>
			</tbody>
			</table>
			
		</div>

		<%=  view.getPaginationHtml(page) %>
		
	<% } %>
	
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="usuarioModelTemplate">
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
							<input type="text" class="input-xlarge" id="email" placeholder="Email" value="<%= _.escape(item.get('email') || '') %>">
						</div>
						<span class="help-inline"></span>
					</div>				
				</div>
				<div id="loginInputContainer" class="control-group">
					<label class="control-label" for="login">Usuário</label>
					<div class="controls inline-inputs">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-smile"></i></span>
							<input type="text" class="input-xlarge" id="login" placeholder="Usuário" value="<%= _.escape(item.get('login') || '') %>">
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="tipoUsuarioInputContainer" class="control-group">
					<label class="control-label" for="tipoUsuario">Tipo de usuário</label>
					<div class="controls inline-inputs">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-lock"></i></span>
							<select id="tipoUsuario" class="t" name="tipoUsuario">
								<option value="padrao" <%= _.escape(item.get('tipoUsuario')) == '0' ? 'selected' : '' %>>Padrão</option>
								<option value="admin" <%= _.escape(item.get('tipoUsuario')) == '1' ? 'selected' : '' %>>Administrador</option>
							</select>
						</div>
						<span class="help-inline">
						<span class="hide padrao">Só pode gerenciar a presença de participantes de um evento</span>
						<span class="hide admin">Pode realizar qualquer ação no sistema</span></span>
					</div>
				</div>
				<div id="alterarSenhaInputContainer" class="hide">
					<div class="controls">
							<button id="alterarSenhaUsuarioButton" class="btn btn-primary"><i class="icon-key icon-white"></i> Alterar Senha</button>
					</div>
				</div>
				<div id="alterarSenhaUsuarioContainer">
					<div id="senhaInputContainer" class="control-group">
						<label class="control-label" for="senha">Senha</label>
						<div class="controls inline-inputs">
							<div class="input-prepend">
								<span class="add-on"><i class="icon-key"></i></span>
								<input type="password" autocomplete="off" class="input-xlarge" id="senha" placeholder="Senha">
							</div>
							<span class="help-inline"></span>
						</div>
					</div>
					<div id="confirmarSenhaInputContainer" class="control-group">
						<label class="control-label" for="confirmarSenha">Confirmar Senha</label>
						<div class="controls inline-inputs">
							<div class="input-prepend">
								<span class="add-on"><i class="icon-key"></i></span>
								<input type="password" autocomplete="off" class="input-xlarge" id="confirmarSenha" name="confirmarSenha" placeholder="Confirmar senha">
							</div>
							<span class="help-inline"></span>
						</div>
					</div>
				</div>
			</fieldset>
		</form>

		<!-- delete button is is a separate form to prevent enter key from triggering a delete -->
		<form id="deleteUsuarioButtonContainer" class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
						<button id="deleteUsuarioButton" class="btn btn-danger"><i class="icon-trash icon-white"></i> Excluir Usuário</button>
						<span id="confirmDeleteUsuarioContainer" class="hide">
							<button id="cancelDeleteUsuarioButton" class="btn">Cancelar</button>
							<button id="confirmDeleteUsuarioButton" class="btn btn-success">Confirmar</button>
						</span>
					</div>
				</div>
			</fieldset>
		</form>
	</script>

	<!-- modal edit dialog -->
	<div class="modal hide fade animated bounceIn modal-on-top"  data-backdrop="static" id="usuarioDetailDialog">
		<div class="modal-header">
			<a class="close btn btn-danger btn-big" data-dismiss="modal">&times;</a>
			<h3>
				<i id="icone-acao-modal" class="icon-edit"></i> <span id="titulo-modal">Editar</span> Usuário
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="usuarioModelContainer"></div>
		</div>
		<div class="modal-footer">
			<button id="saveUsuarioButton" class="btn btn-primary">Salvar</button>
			<button class="btn" data-dismiss="modal" >Cancelar</button>
		</div>
	</div>

	<div id="collectionAlert"></div>
	
	<div id="usuarioCollectionContainer" class="collectionContainer">
	</div>

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
