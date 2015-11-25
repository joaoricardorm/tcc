<?php
	$this->assign('title','Emitir Certificados - Participantes - Certifica-μ');
	$this->assign('nav','certificados');
	$this->assign('navegacao','emitir-certificados');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB
	.script(base+"scripts/app/participantes-certificados.js")
	.wait(function(){
		$(document).ready(function(){
			page.init();
		});
		
		// hack for IE9 which may respond inconsistently with document.ready
		setTimeout(function(){
			if (!page.isInitialized) page.init();
		},1000);
	});
</script>

<?php if($this->Palestra){ ?>

<nav class="container hero-unit small small margin-bottom-5px">
		<ol class="cd-multi-steps text-top">		
			<li class="visited"><a href="evento/<?php $this->eprint( $this->Palestra->IdEvento . '/'. AppBaseController::parseURL($this->Evento->Nome ) . '/' ); ?>"><i class="icon-tags"></i><?php $this->eprint($this->Evento->Nome); ?></a></li> <!-- Classe "visited" -->
			<li class="visited"><a href="evento/<?php $this->eprint( $this->Palestra->IdEvento . '/atividades/' . $this->Palestra->IdPalestra . '/'. AppBaseController::parseURL($this->Palestra->Nome) . '/' ); ?>"><i class="icon-tags"></i><?php echo ($this->Palestra->ProprioEvento) ? 'Detalhes do evento' : $this->eprint( $this->Palestra->Nome ) ; ?></a></li>
			<li class="visited"><a href="evento/<?php $this->eprint($this->Palestra->IdEvento . '/atividades/' . $this->Palestra->IdPalestra . '/'. AppBaseController::parseURL($this->Palestra->Nome) . '/palestrantes/' ); ?>"><i class="icon-microphone"></i>Palestrantes</a></li>	
			<li class="visited"><a href="evento/<?php $this->eprint($this->Palestra->IdEvento . '/atividades/' . $this->Palestra->IdPalestra . '/'. AppBaseController::parseURL($this->Palestra->Nome) . '/participantes/' ); ?>"><i class="icon-group"></i>Participantes</a></li>
			<li class="current"><span><i class="icon-certificate"></i>Emitir Certificados</span></li>
		</ol>
</nav>	


<nav class="container hero-unit small text-center" style="line-height:normal;">
		<ol class="cd-breadcrumb triangle sem-margem-bottom inline-block" style="width:auto;">		
			<li class="visited"><a href="emitir-certificados/"><i class="icon-tags"></i>Selecionar outro evento/atividade</a></li> <!-- Classe "visited" -->
			<li class="visited"><a id="btnObterAta" href="emitir-certificados/baixar-ata/<?php $this->eprint($this->Palestra->IdPalestra); ?>/"><i class="icon-book"></i>Obter ata <?php echo ($this->Palestra->ProprioEvento) ? 'do evento' : 'da atividade'; ?></a></li>
			<li class="current"><span><i class="icon-group"></i>Participantes</a></span></li>
			<li class="visited"><span class="muted"><i class="icon-certificate"></i>Formatação</a></span></li>
			<li class="visited"><span class="muted"><i class="icon-certificate"></i>Obter certificados</a></span></li>
		</ol>
		
		
		<div id="alertaDownloadAta" class="hide alert alert-success text-large" style="padding-left:8px; margin:5px 0 0 0;">
	
			<button type="button" class="close" data-dismiss="alert" aria-label="Fechar"><span aria-hidden="true">&times;</span></button>
			
			<i class="icon-download icon-big icon-margin-right icon-large" style="font-size:3em; vertical-align:middle; opacity:0.7;"></i>
			
			O documento da ata foi baixado para o seu dispositivo.
			
		</div>

		
</nav>	
	
<?php } ?>

<form action="emitir-certificados/modelo/<?php $this->eprint($this->Palestra->IdPalestra); ?>/?idPalestra=<?php $this->eprint($this->Palestra->IdPalestra); ?>" method="post">

<input type="hidden" name="idPalestra" value="<?php $this->eprint($this->Palestra->IdPalestra); ?>">
	
<div class="container hero-unit">

	<p class="pull-right block-sm">
		<button id="btnContinuar" type="submit" class="btn btn-primary btn-large">
			Continuar 
			<i class="icon-arrow-right icon-margin-left"></i> 
			<i class="icon-spin icon-margin-left hidden icon-refresh center inline-block"></i>
		</button>
	</p>

	<h3>
			Emitir Certificados da atividade <?php $this->eprint($this->Palestra->Nome); ?>
			<span id="loader" class="loader progress progress-striped active"><span class="bar"></span></span>
	</h3>

	<h1><i class="icon-group"></i> Selecione os participantes que receberão os certificados</h1>	    
	
	

<div class="clearfix"></div>



	<!-- underscore template for the collection -->
	<script type="text/template" id="participanteCollectionTemplate">
	
	<% if(items.length === 0){ %>
			<hr>
			<h3>Nenhum participante encontrado</h3>
	<% } else { %>		
		
		<div class="new-and-search-container clearfix">
			
			<label class="btn pull-left block-sm margin-right-bigger-sm">
				<input checked="checked" class="hide" name="selecionarTudo" id="selecionarTudo" type="checkbox">
				<span>Selecionar todos os participantes</span>
			</label>
			
			<small class="block-sm">Total de participantes na atividade: <%= items.totalResults %></small>
			
			<div class="pull-right block-sm">
			<span class='input-append searchContainer' style="position:relative">
			
				<span class="a" id="limparBusca" title="Limpar filtro de busca" class="pull-right" style="position:absolute; right: 60px; top:2px; z-index: 3; font-size:16px"><i class="icon icon-remove"></i></span>
				
				<input id='filter' type="text" placeholder="Buscar..." />
				<button class='btn add-on'><i class="icon-search"></i></button>
			</span>
			</div>
		</div>
	
	
	<div id="no-more-tables">
	
		<table class="collection table table-hover table-striped">
		<thead>
			<tr id="trHeader">
				<th id="header_CbParticipante" style="width:14px; text-align:center;"><i class="icon icon-ok" style="margin-right:0;"><span class="hide">Receber certificado?</span></i></th>
				<th id="header_Nome"><i class="icon icon-user"></i>Nome<% if (page.orderBy == 'Nome') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Email"><i class="icon icon-envelope"></i>E-mail<% if (page.orderBy == 'Email') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Cpf"><i class="icon icon-user"></i>CPF<% if (page.orderBy == 'Cpf') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			
			<tr id="<%= _.escape(item.get('idParticipante')) %>" title="Marcar/Desmarcar esse participante">
				
				<td>
				
				<input id="cbParticipante<%= _.escape(item.get('idParticipante')) %>" name="cbParticipante[<%= _.escape(item.get('idParticipante')) %>]" class="checkbox-custom" name="checkbox-3" type="checkbox">
				<label for="cbParticipante<%= _.escape(item.get('idParticipante')) %>"class="checkbox-custom-label"></label>   
				
				</td>
		
				<td><%= _.escape(item.get('nome') || '') %></td>
				<td><%= _.escape(item.get('email') || '') %></td>
				<td><%= _.escape(item.get('cpf') || '') %></td>

			</tr>

		<% }); %>
		</tbody>
		</table>
		
		<h3 id='nenhumResultado' class="hide" style="margin-top:-10px;">Nenhum participante encontrado com os termo(s) pesquisado(s)</h3>

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
			
			<div id="savingFloat" class="text-center alert alert-dark stick float-bottom-notification"><span class="icon-big icon-refresh icon-spin" style="font-size:28px; vertical-align:middle; text-align:center;"></span> <span class="texto">Salvando</span></div>
			
		</form>

	</script>

	<!-- modal edit dialog -->
	<div class="modal hide fade animated bounceIn modal-on-top"  data-backdrop="static" id="participanteDetailDialog">
		<div class="modal-header">
			<a id="btnCloseModalPrincipal" class="close btn btn-danger btn-big">&times;</a>
			<h3>
				<i class="icon-group"></i> Gerenciar Participantes
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div class="alert alert-warning alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Dica</strong> Você pode colar o conteúdo de outra planilha de participantes aqui. =)
			</div>
			<div id="modelAlert"></div>
			<div id="participanteModelContainer"></div>
		</div>
	</div>

	<div id="collectionAlert"></div>
	
	<div id="participanteCollectionContainer" class="collectionContainer">
	</div>

</div> <!-- /container -->

</form>

<?php
	$this->display('_Footer.tpl.php');
?>
