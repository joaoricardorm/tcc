<?php
	$this->assign('title','Emitir Certificados - Participantes - Certifica-μ');
	$this->assign('nav','certificados');
	$this->assign('navegacao','emitir-certificados');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB
	.script(base+"scripts/app/participantes-certificados.js")
	.script(base+"scripts/app/presenca.js")
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

<nav class="container hero-unit small text-center" style="line-height:normal;">
		<ol class="cd-breadcrumb triangle sem-margem-bottom inline-block" style="width:auto;">		
			<li class="visited"><a href="./presenca/participantes/"><i class="icon-tags"></i>Selecionar outro evento/atividade</a></li> <!-- Classe "visited" -->
			<li class="current"><span><i class="icon-group"></i>Lista de presença</a></span></li>
		</ol>		
</nav>	
	
<?php } else { ?>

<nav class="container hero-unit small text-center" style="line-height:normal;">
		<ol class="cd-breadcrumb triangle sem-margem-bottom inline-block" style="width:auto;">		
			<li class="current"><a href="./presenca/participantes/"><i class="icon-tags"></i>Selecionar evento/atividade</a></li> <!-- Classe "visited" -->
			<li><span class="muted"><i class="icon-group"></i>Lista de presença</a></span></li>
		</ol>		
</nav>	

<?php } ?>


<div class="container hero-unit">


<?php if(!$this->Palestra){ ?>



<form class="form-horizontal" action='./presenca/participantes/' id="frmAtividade" method="get">
	
	<h1><i class="icon icon-group"></i> Gerenciar presença de participantes
	
	<h3><i class="icon icon-tag"></i> Selecione o evento/atividade para qual deseja a lista de presença:</h3>
	
	<p>
		<select id="selectEventos" class="form-control metade">
			<option selected disabled>Selecione um evento/atividade</option>
			<?php 
				foreach($this->ListaEventos as $evento){
					echo '<option value="'.$evento->idEvento.'">'.$evento->nome.'</option>';
				} 
			?>
		</select>		
	</p>
	
	<div id="listaAtividades" class="hide">
		<h3><i class="icon icon-tags"></i> Agora selecione para qual atividade deseja a lista de presença:</h3>
		
		<p>		
			<select id="selectAtividades" name="idPalestra" class="form-control metade">
				<option value="" selected disabled>Atividades</option>
			</select>
		</p>
	</div>
	
	<button id="btnContinuar" type="submit" class="hide btn btn-primary btn-large">
			Continuar <i class="icon-arrow-right icon-margin-left"></i>
	</button>
	
</form>	
	
<?php } else { ?>

<form onsubmit="return:false;">

<input type="hidden" name="idPalestra" value="<?php $this->eprint($this->Palestra->IdPalestra); ?>">

	<h3>
			Lista de presença<?php echo ($this->Palestra->ProprioEvento) ? ' do evento ' : ' da atividade '; $this->eprint($this->Palestra->Nome); ?>
			<span id="loader" class="loader progress progress-striped active"><span class="bar"></span></span>
	</h3>

	<h1><i class="icon-group"></i> Marque a presença ou ausência dos participantes</h1>	    
	
	

<div class="clearfix"></div>



	<!-- underscore template for the collection -->
	<script type="text/template" id="participanteCollectionTemplate">
	
	<% if(items.length === 0){ %>
			<hr>
			<h3>Nenhum participante encontrado</h3>
	<% } else { %>		
		
		<div class="new-and-search-container clearfix">
			
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
				<th id="header_CbParticipante" style="width:14px; text-align:center;"><i class="icon icon-ok" style="margin-right:0;"><span class="hide">Presente</span></i></th>
				<th id="header_Nome"><i class="icon icon-user"></i>Nome</th>
				<th id="header_Email"><i class="icon icon-envelope"></i>E-mail</th>
				<th id="header_Cpf"><i class="icon icon-user"></i>CPF</th>
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			
			<tr id="<%= _.escape(item.get('idParticipante')) %>" title="Tornar participante presente ou ausente">
				
				<td>
				
				<input id="cbPresencaParticipante<%= _.escape(item.get('idParticipante')) %>" name="cbPresencaParticipante[<%= _.escape(item.get('idParticipante')) %>]" class="checkbox-custom .cbPresenca" name="checkbox-3" type="checkbox">
				<label for="cbPresencaParticipante<%= _.escape(item.get('idParticipante')) %>"class="checkbox-custom-label"></label>   
				
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

	
	<div id="collectionAlert"></div>
	
	<div id="participanteCollectionContainer" class="collectionContainer">
	</div>

</form>

<?php } ?><!--SE TIVER ID NA PALESTRA, AÍ NÃO PEDE SELEÇÃO-->


</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
