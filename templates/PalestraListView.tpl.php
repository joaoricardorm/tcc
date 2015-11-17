<?php
	$this->assign('title','Atividades - Certifica-μ');
	$this->assign('nav','eventos');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">

	$LAB.script(base+"scripts/app/palestras.js").wait(function(){
		$(document).ready(function(){
			page.init();			
		});
		
		// hack for IE9 which may respond inconsistently with document.ready
		setTimeout(function(){
			if (!page.isInitialized) page.init();
		},1000);
	});
</script>

<nav class="container hero-unit small">
		<ol class="cd-multi-steps text-top">
			<li class="visited"><a href="evento/<?php $this->eprint($this->Evento->IdEvento . '/'. AppBaseController::parseURL($this->Evento->Nome)); ?>/"><i class="icon-tag"></i><?php $this->eprint($this->Evento->Nome); ?></a></span></li> <!-- Classe "visited" -->
			<li class="current"><span><span class="remove-on-single"><i class="icon-tags"></i>Atividades</span><span class="show-on-single"><i class="icon-tag"></i>Detalhes do evento</span></span></li>
			<li>	
				<span class="hidden show-on-single">
					<a id="link-palestrantes-breadcrumb" href="#"><i class="icon-microphone"></i>Palestrantes</a>
				</span>
				<span class="muted remove-on-single"><i class="icon-microphone"></i>Palestrantes</span>
			</li>
			
			<li>
				<span class="hidden show-on-single">
					<a id="link-participantes-breadcrumb" href="#"><i class="icon-group"></i>Participantes</a>
				</span>
				<span class="muted remove-on-single"><i class="icon-group"></i>Participantes</span>
			</li>
		</ol>
</nav>		

<div class="container hero-unit">





<div id="alertaConfirmarExclusao"  class="alert alert-danger alert-dismissible hide" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Excluir atividade!</strong> Selecione uma das atividades da listagem para exclusão
</div>

<div id="modalConfirmarExclusao" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <a class="close btn btn-danger btn-big" data-dismiss="modal">×</a>
        <h4 class="modal-title">Excluir atividade</h4>
      </div>
      <div class="modal-body">
        <p>Tem certeza que deseja excluir a atividade?</p>
      </div>
      <div class="modal-footer">
	    <button id="btnConfirmarExclusao" type="button" class="btn btn-primary">Sim</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Não</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  
</div><!-- /.modal -->





<?php if($this->Evento){ ?>
	<p><a href="evento/<?php $this->eprint($this->Evento->IdEvento . '/'. AppBaseController::parseURL($this->Evento->Nome)); ?>/">
		<button class="btn btn-default block-sm"><i class="icon-arrow-left"></i>Outras informações do evento</button>
	</a></p>
<?php } ?>

<h1>
	<i class="icone-acao icon-tags"></i> <span class="titulo">Atividade<span class="remove-on-single">s</span> <?php if($this->Evento) $this->eprint(' do evento '.$this->Evento->Nome); ?></span>
	<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
</h1>

<div class="new-and-search-container clearfix">
	<span id="newButtonContainer" class="buttonContainer pull-left">
		<button id="newPalestraButton" class="btn btn-primary"><i class="icon-plus"></i> Cadastrar Atividade</button>
	</span>
	<span class='input-append pull-right searchContainer'>
		<input id='filter' type="text" placeholder="Buscar..." />
		<button class='btn add-on'><i class="icon-search"></i></button>
	</span>
</div>

	<!-- underscore template for the collection -->
	<script type="text/template" id="palestraCollectionTemplate">
		
		<!-- verifica se é o proprio evento aqui -->
		<% 
			if(items.length > 0){
				var firstItem = items.models[0].attributes;
				
				var proprioEvento = firstItem.proprioEvento;
			}
		%>
		
		<% if(proprioEvento == 1){ %>
			<p>
			
				<?php if($this->Evento){ ?>
				
					<a class="btn btn-primary margin-right-bigger-sm block-sm"  href="atividade/<%=firstItem.idPalestra%>/<%=app.parseURL(firstItem.nome)%>/palestrantes/">
						<i class="icon-microphone"></i>Palestrantes
					</a>
					
					<a class="btn btn-primary margin-right-bigger-sm block-sm"  href="atividade/<%=firstItem.idPalestra%>/<%=app.parseURL(firstItem.nome)%>/participantes/">
						<i class="icon-group"></i>Participantes
					</a>
				
				<?php } ?>
			</p>
		<% } %>
		
		<% if(items.length === 0){ %>
			<hr>
			<h3>Nenhuma atividade encontrada</h3>
		<% } else { %>
		
		<p class="ordemCadastro" id="ordemCadastro_IdPalestra">
			<a href="#">
			<% if (page.orderBy == 'IdPalestra') { %> 
				<%= page.orderDesc ? "Mostrar antigas primeiro <i class='icon-arrow-down' />" : "Mostrar recentes primeiro <i class='icon-arrow-up' />" %>
			<% } else { %>
				Ordenar pelo cadastro no sistema <i class='icon-arrow-down' />
			<% } %>
			</a>
		</p>
		
		<div id="no-more-tables">
		
		<table class="collection table table-hover table-striped responsible-table">
		<thead>
			<tr>
				<% if(proprioEvento == 0){ %>
					<th id="header_Nome"><i class="icon icon-tags"></i>Nome da atividade<% if (page.orderBy == 'Nome') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
					<th id="header_Data"><i class="icon icon-calendar"></i>Data<% if (page.orderBy == 'Data') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<% } %>
				<th id="header_CargaHoraria"><i class="icon icon-time"></i>Carga horária<% if (page.orderBy == 'CargaHoraria') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<!--<th><i class="icon icon-certificate"></i>Modelo do certificado<% if (page.orderBy == 'NomeModeloCertificado') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>-->
				<th><i class="icon icon-user"></i>Palestrantes<% if (page.orderBy == 'Palestrantes') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('idPalestra')) %>">
				<% if(proprioEvento == 0){ %>
					<td><%= _.escape(item.get('nome') || '') %></td>
					<td><%if (item.get('data')) { %><%= _date(app.parseDate(item.get('data'))).format('DD/MM/YYYY') %><% } else { %>Sem data<% } %></td>
				<% } %>
				<td>
					<%if (item.get('cargaHoraria')) { %>
						<%
						var tempo = _date(app.parseDate(item.get('cargaHoraria'))).format('HH:mm') + ' horas';
						if(_date(app.parseDate(item.get('cargaHoraria'))).format('HH') == '00')
							tempo = _date(app.parseDate(item.get('cargaHoraria'))).format('mm') + ' minutos';
						%>
						<%= tempo %>
					<% } else { %>Sem carga horária<% } %>
				</td>
				<!--<td><%= _.escape(item.get('nomeModeloCertificado') || '') %></td>-->
				<td class="lista-palestrantes"><span class="muted">Nenhum palestrante</span></td>
			</tr>
		<% }); %>
		</tbody>
		</table>
		
		</div>

		<%=  view.getPaginationHtml(page) %>
		
		<% } %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="palestraModelTemplate">
	
		<nav class="passos-evento">
		<ol class="cd-multi-steps text-top">
			<li class="visited">
				<?php if($this->Evento){ ?>
					<a href="evento/<?php $this->eprint($this->Evento->IdEvento . '/'. AppBaseController::parseURL($this->Evento->Nome)); ?>/">
						<i class="icon-tag"></i><?php $this->eprint($this->Evento->Nome); ?>
					</a>
				<?php } ?>
			</li> <!-- Classe "visited" -->
			<li class="current"><span><i class="icon-tags"></i><% if(item.get('proprioEvento') == 1){ %>Detalhes do evento<% } else { %>Atividade<% } %></span></li>
			<li>
				<span>
					<a href="atividade/<%=item.get('idPalestra')%>/<%=app.parseURL(item.get('nome'))%>/palestrantes/">
						<i class="icon-microphone"></i>Palestrantes
					</a>
				</span>
			</li>
			<li>
				<span>
					<a href="atividade/<%=item.get('idPalestra')%>/<%=app.parseURL(item.get('nome'))%>/participantes/">
						<i class="icon-group"></i>Participantes
					</a>
				</span>
			</li>
		</ol>
		</nav>
	
		<form class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<input type="hidden" id="proprioEvento" value="<%= _.escape(item.get('proprioEvento') || '0') %>">
				<?php if($this->Evento){ ?>
				<input type="hidden" id="idEvento" name="idEvento" value="<?php $this->eprint($this->Evento->IdEvento); ?>">
				<?php } ?>

				<div id="nomeInputContainer" class="control-group hide-on-single">
					<label class="control-label" for="nome">Nome</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="nome" placeholder="Nome" value="<%= _.escape(item.get('nome') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="dataInputContainer" class="control-group hide-on-single">
					<label class="control-label" for="data">Data</label>
					<div class="controls inline-inputs">
						<div class="input-prepend" data-date-format="dd-mm-yyyy">
							<span class="add-on"><i class="icon-calendar"></i></span>
							<input id="data" type="date" class="input-large" value="<% if(item.get('data')){ %><%= _date(app.parseDate(item.get('data'))).format('YYYY-MM-DD') %><% } %>" />
						</div>
						<span class="help-inline"></span>
					</div>
				</div>				
				<div id="cargaHorariaInputContainer" class="control-group">
					<label class="control-label" for="cargaHoraria">Carga horária</label>
					<div class="controls inline-inputs">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-time"></i></span>
							<input id="cargaHoraria" type="time" class="input-small" value="<%if (item.get('cargaHoraria')) { %><%= _date(app.parseDate(item.get('cargaHoraria'))).format('HH:mm') %><% } else { %>00:00<% } %>" />
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="idModeloCertificadoInputContainer" class="hide control-group">
					<label class="control-label" for="idModeloCertificado">Modelo do certificado</label>
					<div class="controls inline-inputs">
						<select id="idModeloCertificado" name="idModeloCertificado">
						</select>
						<span class="help-inline"></span>
					</div>
				</div>
				
				
				<div class="control-group">
				
				<div id="PalestranteInputContainer" class="PalestranteInputContainer" class="control-group">
					<label class="control-label" for="palestrantes">Palestrantes</label>
					<div class="controls inline-inputs">
						<select id="palestrantes" name="palestrantes" size="1" multiple class="margin-right-bigger-sm block-sm"">
						</select> 
						<span class="help-inline"></span>
						
						<a class="vertical-align-super" title="Cadastrar novo palestrante para esta atividade" href="atividade/<%= item.get('idPalestra') %>/<%= app.parseURL(item.get('nome')) %>/palestrantes/novo/"><i class="icon-microphone"></i>Cadastrar novo palestrante</a>
					</div>
				</div>
				
				</div>

				
				<% if(item.get('idPalestra')){ %>
				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
			
						<a class="btn btn-primary margin-right-bigger-sm block-sm" id="palestrantesButton" href="atividade/<%= item.get('idPalestra') %>/<%= app.parseURL(item.get('nome')) %>/palestrantes/">
							<i class="icon-microphone"></i>Detalhes dos Palestrantes
						</a>
						
						<a class="btn btn-primary margin-right-bigger-sm block-sm" id="participantesButton" href="atividade/<%= item.get('idPalestra') %>/<%= app.parseURL(item.get('nome')) %>/participantes/">
							<i class="icon-group"></i>Participantes
						</a>
					
					</div>
				</div>
				<% } %>
			</fieldset>
		</form>

		<!-- delete button is is a separate form to prevent enter key from triggering a delete -->
		<form id="deletePalestraButtonContainer" class="form-horizontal remove-on-single" onsubmit="return false;">
			<fieldset>
				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
						<button id="deletePalestraButton" class="btn btn-danger"><i class="icon-trash icon-white"></i> Excluir Atividade</button>
						<span id="confirmDeletePalestraContainer" class="hide">
							<button id="cancelDeletePalestraButton" class="btn">Cancelar</button>
							<button id="confirmDeletePalestraButton" class="btn btn-success">Confirmar</button>
						</span>
					</div>
				</div>
			</fieldset>
		</form>
	</script>

	<!-- modal edit dialog -->
	<div class="modal hide fade animated bounceIn modal-on-top"  data-backdrop="static" id="palestraDetailDialog">
		<div class="modal-header">
			<a class="close btn btn-danger btn-big" data-dismiss="modal">&times;</a>
			<h3>
				<i class="icone-acao-modal icon-edit"></i> <span class="titulo-modal">Editar Atividade</span>
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="palestraModelContainer"></div>
		</div>
		<div class="modal-footer">
			<?php if($this->Evento){ ?>
				<a class="btn btn-default show-on-single margin-right-bigger-sm block-sm" href="evento/<?php $this->eprint($this->Evento->IdEvento . '/'. AppBaseController::parseURL($this->Evento->Nome)); ?>/">
					<i class="icon-arrow-left"></i>Voltar
				</a>
			<?php } ?>		
			<button id="savePalestraButton" class="btn btn-primary block-sm"><span>Salvar</span></button>
			<button id="cancelarSavePalestraButton" class="btn block-sm" data-dismiss="modal">Cancelar</button>
		</div>
	</div>

	<div id="collectionAlert"></div>
	
	<div id="palestraCollectionContainer" class="collectionContainer">
	</div>

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
