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





<?php if($this->Palestra){ ?>

<nav class="container hero-unit small">
		<ol class="cd-multi-steps text-top">		
			<li class="visited"><a href="evento/<?php $this->eprint( $this->Palestra->IdEvento . '/'. AppBaseController::parseURL($this->Evento->Nome ) . '/' ); ?>"><i class="icon-tags"></i><?php $this->eprint($this->Evento->Nome); ?></a></li> <!-- Classe "visited" -->
			<li class="visited"><a href="evento/<?php $this->eprint( $this->Palestra->IdEvento . '/atividades/' . $this->Palestra->IdPalestra . '/'. AppBaseController::parseURL($this->Palestra->Nome) . '/' ); ?>"><i class="icon-tags"></i><?php echo ($this->Palestra->ProprioEvento) ? 'Detalhes do evento' : $this->eprint( $this->Palestra->Nome ) ; ?></a></li>
			<li class="visited"><a href="evento/<?php $this->eprint($this->Palestra->IdEvento . '/atividades/' . $this->Palestra->IdPalestra . '/'. AppBaseController::parseURL($this->Palestra->Nome) . '/palestrantes/' ); ?>"><i class="icon-microphone"></i>Palestrantes</a></li>	
			<li class="current"><span><i class="icon-group"></i>Participantes</span></li>
		</ol>
</nav>		

<div class="container hero-unit">


<p><a href="evento/<?php $this->eprint( $this->Palestra->IdEvento . '/atividades/' . $this->Palestra->IdPalestra . '/'. AppBaseController::parseURL($this->Palestra->Nome) . '/' ); ?>">
	<button class="btn btn-default block-sm"><i class="icon-arrow-left"></i>Outras informações <?php echo ($this->Palestra->ProprioEvento) ? 'do evento' : 'da atividade'; ?></button>
</a></p>

<?php } ?>


<h1>
	<i class="icon-group"></i> Participantes <?php echo ($this->Palestra->ProprioEvento) ? 'do evento' : 'da atividade'; ?> <?php $this->eprint($this->Palestra->Nome); ?> 
	<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
</h1>

<div class="new-and-search-container clearfix">
	<span id="newButtonContainer" class="buttonContainer pull-left">
		<button id="newParticipanteButton" class="btn btn-primary"><i class="icon-cog"></i> Gerenciar Participantes</button>
	</span>
	<span class='input-append pull-right searchContainer'>
		<input id='filter' type="text" placeholder="Buscar..." />
		<button class='btn add-on'><i class="icon-search"></i></button>
	</span>
</div>



	<!-- underscore template for the collection -->
	<script type="text/template" id="participanteCollectionTemplate">
	
	<% if(items.length === 0){ %>
			<hr>
			<h3>Nenhum participante encontrado</h3>
	<% } else { %>
		
	<p class="ordemCadastro" id="ordemCadastro_IdParticipante">
		<a href="#">
		<% if (page.orderBy == 'IdParticipante') { %> 
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
				<th id="header_Nome">Nome<% if (page.orderBy == 'Nome') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Email">Email<% if (page.orderBy == 'Email') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Cpf">Cpf<% if (page.orderBy == 'Cpf') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('idParticipante')) %>">
				<td><%= _.escape(item.get('nome') || '') %></td>
				<td><%= _.escape(item.get('email') || '') %></td>
				<td><%= _.escape(item.get('cpf') || '') %></td>
			</tr>
		<% }); %>
		</tbody>
		</table>

		</div>
		
		<%=  view.getPaginationHtml(page) %>
		
		<%}%>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="participanteModelTemplate">		
		<form class="form-horizontal" onsubmit="return false;">
			
			<p class="clearfix">
				<!--hide - Tirar o botao excluir = tirar classe clearfix = e deixar somente no menu de contexto por problemas de seleção dele-->
				<button tabindex="3" id="NAO_USAR_remove_car" class="hide intext-btn btn btn-danger"><i class="icon-trash icon-white"></i> Excluir Participante Selecionado</button>
				
				<span class="pull-left">
					<span class="input-append searchContainer margin-right-bigger-sm">
						<input id="search_field" type="text" placeholder="Buscar..." style="padding:9px 15px;" />
						<span class="btn add-on" style="padding:9px 10px;"><i class="icon-search"></i></span>
					</span>
					
					<a id="add_car" class="intext-btn block-sm"><i class="icon-plus icon-white"></i> Adicionar Novo Participante</a>
				</span>
				
				<!--Adicionar pull-right no span se tiver botão excluir-->
				<span class="pull-right">
					<button id="save_car" class="intext-btn btn btn-primary block-sm"><i class="icon-save icon-white"></i> Salvar</button>
				</div>
			</p>
			
			<div id="table-participantes"></div>
			
			<div id="savingFloat" class="text-center alert alert-dark stick float-bottom-notification"><span class="icon-big icon-refresh icon-spin" style="font-size:28px; vertical-align:middle; text-align:center;"></span> Salvando</div>
			
		</form>

	</script>

	<!-- modal edit dialog -->
	<div class="modal hide fade animated bounceIn modal-on-top"  data-backdrop="static" id="participanteDetailDialog">
		<div class="modal-header">
			<a class="close btn btn-danger btn-big" data-dismiss="modal">&times;</a>
			<h3>
				<i class="icon-group"></i> Gerenciar Participantes
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

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
