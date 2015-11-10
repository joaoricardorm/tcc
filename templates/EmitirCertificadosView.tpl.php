<?php
	$this->assign('title','Certificados - Certifica-μ');
	$this->assign('nav','certificados');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB
	<?php if($this->Palestra){ ?>
	.script(base+"scripts/app/certificados.js")
	<?php } else { ?>
	.script(base+"scripts/app/emitir-certificados.js")
	<?php } ?>
	.wait(function(){
		
		<?php if($this->Palestra){ ?>
			$(document).ready(function(){
				page.init();
			});
			// hack for IE9 which may respond inconsistently with document.ready
			setTimeout(function(){
				 if (!page.isInitialized) page.init();				
			},1000);
		<?php } ?>
		
	});
</script>


<?php if($this->Palestra){ ?>

<nav class="container hero-unit small">
		<ol class="cd-multi-steps text-top">		
			<li class="visited"><a href="evento/<?php $this->eprint( $this->Palestra->IdEvento . '/'. AppBaseController::parseURL($this->Evento->Nome ) . '/' ); ?>"><i class="icon-tags"></i><?php $this->eprint($this->Evento->Nome); ?></a></li> <!-- Classe "visited" -->
			<li class="visited"><a href="evento/<?php $this->eprint( $this->Palestra->IdEvento . '/atividades/' . $this->Palestra->IdPalestra . '/'. AppBaseController::parseURL($this->Palestra->Nome) . '/' ); ?>"><i class="icon-tags"></i><?php echo ($this->Palestra->ProprioEvento) ? 'Detalhes do evento' : $this->eprint( $this->Palestra->Nome ) ; ?></a></li>
			<li class="visited"><a href="evento/<?php $this->eprint($this->Palestra->IdEvento . '/atividades/' . $this->Palestra->IdPalestra . '/'. AppBaseController::parseURL($this->Palestra->Nome) . '/palestrantes/' ); ?>"><i class="icon-microphone"></i>Palestrantes</a></li>	
			<li class="visited"><a href="evento/<?php $this->eprint($this->Palestra->IdEvento . '/atividades/' . $this->Palestra->IdPalestra . '/'. AppBaseController::parseURL($this->Palestra->Nome) . '/participantes/' ); ?>"><i class="icon-group"></i>Participantes</a></li>
			<li class="current"><span><i class="icon-certificate"></i>Emitir Certificados</span></li>
		</ol>
</nav>	

<?php } ?>

<div class="container hero-unit">

<h1>
	<i class="icon-certificate"></i> Emitir Certificados
	
	<span id="loader" class="<?php if(!$this->Palestra) echo 'hidden'; ?> loader progress progress-striped active"><span class="bar"></span></span>
	<?php echo ($this->Palestra) ? '<h3>Atividade '.$this->Palestra->Nome.'</h3>' : ''; ?>
</h1>

<?php if(!$this->Palestra){ ?>

<form class="form-horizontal" id="frmAtividade" method="get">

	<h3>Selecione o evento/atividade para qual deseja emitir os certificados:</h3>
	
	<p>
		<select id="selectEventos" class="form-control metade">
			<option value="" selected disabled>Selecione um evento/atividade</option>
			<?php 
				foreach($this->ListaEventos as $evento){
					echo '<option value="'.$evento->idEvento.'">'.$evento->nome.'</option>';
				} 
			?>
		</select>		
	</p>
	
	<div id="listaAtividades" class="hide">
		<h3>Agora selecione para qual atividade deseja emitir os certificados:</h3>
		
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

<div class="new-and-search-container clearfix">
	<span id="newButtonContainer" class="buttonContainer pull-left">
		<button id="newCertificadoButton" class="btn btn-primary"><i class="icon-plus"></i> Emitir Certificados</button>
	</span>
</div>

	<!-- underscore template for the collection -->
	<script type="text/template" id="certificadoCollectionTemplate">
		<table class="collection table table-hover table-striped">
		<thead>
			<tr>
				<th id="header_IdCertificado">Id Certificado<% if (page.orderBy == 'IdCertificado') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_DataEmissao">Data Emissao<% if (page.orderBy == 'DataEmissao') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Livro">Livro<% if (page.orderBy == 'Livro') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Folha">Folha<% if (page.orderBy == 'Folha') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Codigo">Codigo<% if (page.orderBy == 'Codigo') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
<!-- UNCOMMENT TO SHOW ADDITIONAL COLUMNS
				<th id="header_IdUsuario">Id Usuario<% if (page.orderBy == 'IdUsuario') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
-->
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('idCertificado')) %>">
				<td><%= _.escape(item.get('idCertificado') || '') %></td>
				<td><%if (item.get('dataEmissao')) { %><%= _date(app.parseDate(item.get('dataEmissao'))).format('MMM D, YYYY h:mm A') %><% } else { %>NULL<% } %></td>
				<td><%= _.escape(item.get('livro') || '') %></td>
				<td><%= _.escape(item.get('folha') || '') %></td>
				<td><%= _.escape(item.get('codigo') || '') %></td>
<!-- UNCOMMENT TO SHOW ADDITIONAL COLUMNS
				<td><%= _.escape(item.get('idUsuario') || '') %></td>
-->
			</tr>
		<% }); %>
		</tbody>
		</table>

		<%=  view.getPaginationHtml(page) %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="certificadoModelTemplate">
		<form class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div id="idCertificadoInputContainer" class="control-group">
					<label class="control-label" for="idCertificado">Id Certificado</label>
					<div class="controls inline-inputs">
						<span class="input-xlarge uneditable-input" id="idCertificado"><%= _.escape(item.get('idCertificado') || '') %></span>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="dataEmissaoInputContainer" class="control-group">
					<label class="control-label" for="dataEmissao">Data Emissao</label>
					<div class="controls inline-inputs">
						<div class="input-append date date-picker" data-date-format="yyyy-mm-dd">
							<input id="dataEmissao" type="text" value="<%= _date(app.parseDate(item.get('dataEmissao'))).format('YYYY-MM-DD') %>" />
							<span class="add-on"><i class="icon-calendar"></i></span>
						</div>
						<div class="input-append bootstrap-timepicker-component">
							<input id="dataEmissao-time" type="text" class="timepicker-default input-small" value="<%= _date(app.parseDate(item.get('dataEmissao'))).format('h:mm A') %>" />
							<span class="add-on"><i class="icon-time"></i></span>
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="livroInputContainer" class="control-group">
					<label class="control-label" for="livro">Livro</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="livro" placeholder="Livro" value="<%= _.escape(item.get('livro') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="folhaInputContainer" class="control-group">
					<label class="control-label" for="folha">Folha</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="folha" placeholder="Folha" value="<%= _.escape(item.get('folha') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="codigoInputContainer" class="control-group">
					<label class="control-label" for="codigo">Codigo</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="codigo" placeholder="Codigo" value="<%= _.escape(item.get('codigo') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="idUsuarioInputContainer" class="control-group">
					<label class="control-label" for="idUsuario">Id Usuario</label>
					<div class="controls inline-inputs">
						<select id="idUsuario" name="idUsuario"></select>
						<span class="help-inline"></span>
					</div>
				</div>
			</fieldset>
		</form>

		<!-- delete button is is a separate form to prevent enter key from triggering a delete -->
		<form id="deleteCertificadoButtonContainer" class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
						<button id="deleteCertificadoButton" class="btn btn-danger"><i class="icon-trash icon-white"></i> Excluir Certificado</button>
						<span id="confirmDeleteCertificadoContainer" class="hide">
							<button id="cancelDeleteCertificadoButton" class="btn">Cancelar</button>
							<button id="confirmDeleteCertificadoButton" class="btn btn-success">Confirmar</button>
						</span>
					</div>
				</div>
			</fieldset>
		</form>
	</script>

	<!-- modal edit dialog -->
	<div class="modal hide fade animated bounceIn modal-on-top"  data-backdrop="static" id="certificadoDetailDialog">
		<div class="modal-header">
			<a class="close btn btn-danger btn-big" data-dismiss="modal">&times;</a>
			<h3>
				<i id="icone-acao-modal" class="icon-edit"></i> <span id="titulo-modal">Editar</span> Certificado
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="certificadoModelContainer"></div>
		</div>
		<div class="modal-footer">
			<button id="saveCertificadoButton" class="btn btn-primary">Salvar</button>
			<button class="btn" data-dismiss="modal" >Cancelar</button>
		</div>
	</div>

	<div id="collectionAlert"></div>
	
	<div id="certificadoCollectionContainer" class="collectionContainer">
	</div>

<?php } //só se houver idPalestra na url ?>	
	
</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
