<?php
	$this->assign('title','Certificados FAROL | Participantes');
	$this->assign('nav','participantes');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB
	.script(base+"scripts/handsontable/handsontable.full.js")
	.script(base+"scripts/app/participantes.js").wait(function(){
		$(document).ready(function(){
			page.init();
		});
		
		// hack for IE9 which may respond inconsistently with document.ready
		setTimeout(function(){
			if (!page.isInitialized) page.init();
		},1000);
	});
</script>

<link href="scripts/handsontable/handsontable.full.css" rel="stylesheet" />

<div class="container">


<div id="modalNaoPodeExcluirVarios" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <a class="close btn btn-danger btn-big" data-dismiss="modal">×</a>
        <h4 class="modal-title">Não foi possível excluir</h4>
      </div>
      <div class="modal-body">
        <p>É necessário excluir um participante por vez</p>
      </div>
      <div class="modal-footer">
	    <button id="btnOkNaoPodeExcluirVarios" type="button" data-dismiss="modal" class="btn btn-primary">Ok</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div id="modalConfirmarExclusao" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <a id="btnCloseExclusao" class="close btn btn-danger btn-big" data-dismiss="modal">×</a>
        <h4 class="modal-title">Excluir participante</h4>
      </div>
      <div class="modal-body">
        <p>Tem certeza que deseja excluir o(a) participante?</p>
		<h4></h4>
      </div>
      <div class="modal-footer">
	    <button id="btnConfirmarExclusao" type="button" class="btn btn-primary">Sim</button>
        <button id="btnCancelarExclusao" type="button" class="btn btn-default" data-dismiss="modal">Não</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


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
			
			<p>
				<button tabindex="3" id="remove_car" class="intext-btn btn btn-danger block-sm"><i class="icon-trash icon-white"></i> Excluir Participante Selecionado</button>
				
				<span class="pull-right">
					<button id="save_car" class="intext-btn btn btn-primary block-sm"><i class="icon-save icon-white"></i> Salvar</button>
				</div>
			</p>
			<p class="clearfix">
				<span class="pull-right">
					<a id="add_car" class="intext-btn margin-right-bigger-sm block-sm"><i class="icon-plus icon-white"></i> Adicionar Novo Participante</a>
					
					<span class="input-append searchContainer">
						<input id="search_field" type="text" placeholder="Buscar..." />
						<span class="btn add-on"><i class="icon-search"></i></span>
					</span>
				</span>
			</p>
			
			<div id="table-participantes"></div>
			
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
