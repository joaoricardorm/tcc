<?php
	$this->assign('title','Certificados - Certifica-μ');
	$this->assign('nav','certificados');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB.script(base+"scripts/app/emitir-certificados.js");
</script>

<?php if($this->Palestra){ ?>

<nav class="container hero-unit small margin-bottom-5px">
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
			<li class="current"><span><i class="icon-book"></i>Informações da ata</span></li>
			<li class="visited"><span class="muted"><i class="icon-group"></i>Selecionar palestrantes/participantes</a></span></li>
			<li class="visited"><span class="muted"><i class="icon-certificate"></i>Obter certificados</a></span></li>
		</ol>
</nav>	

<?php } else { ?>

<nav class="container hero-unit small text-center" style="line-height:normal;">
		<ol class="cd-breadcrumb triangle sem-margem-bottom inline-block" style="width:auto;">		
			<li class="current"><span><i class="icon-tags"></i>Selecionar evento/atividade</span></li> <!-- Classe "visited" -->
			<li><span class="muted"><i class="icon-certificate"></i>Informações da ata</span></li>
			<li class="visited"><span class="muted"><i class="icon-group"></i>Selecionar palestrantes/participantes</a></span></li>
			<li class="visited"><span class="muted"><i class="icon-certificate"></i>Obter certificados</a></span></li>
		</ol>
</nav>	

<?php } ?>

<div id="conteudo" class="container hero-unit">

<h1>
	<i class="icon-certificate"></i> Emitir Certificados
	
	<span id="loader" class="<?php if(!$this->Palestra) echo 'hidden'; ?> loader progress progress-striped active"><span class="bar"></span></span>
	<?php echo ($this->Palestra) ? '<h3>Informações da ata para a atividade '.$this->Palestra->Nome.'</h3>' : ''; ?>
</h1>

<?php if(!$this->Palestra){ ?>

<form class="form-horizontal" id="frmAtividade" method="get">

	<h3>Selecione o evento/atividade para qual deseja emitir os certificados:</h3>
	
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

<form id="dadosAta" class="form-horizontal" onsubmit="return false;">
	<fieldset>
		<div class="well grey">
			<div id="livroInputContainer" class="control-group">
				<label class="control-label" for="livro">Nº da Ata:</label>
				<div class="controls inline-inputs">
					<input type="number" class="input-small" name="livro" id="livro" placeholder="Nº da Ata" value="<?php $this->eprint($this->UltimoElemento->Livro); ?>">
					<span class="help-inline"></span>
				</div>
			</div>
			<div id="folhaInputContainer" class="control-group">
				<label class="control-label" for="folha">Nº da página inicial p/ esta atividade na ata:</label>
				<div class="controls inline-inputs">
					<input type="number" class="input-small" name="folha" id="folha" placeholder="Nº da página" value="<?php echo ($this->UltimoElemento->Folha) ? $this->UltimoElemento->Folha : '1'; ?>">
					<span id="totalFolhas" class="help-inline"></span>
				</div>
			</div>
			<div id="codigoInputContainer" class="control-group sem-margin-bottom">
				<label class="control-label" for="codigo">Código do certificado inicial para ata:</label>
				<div class="controls inline-inputs">
					<input type="number" class="input-small" name="codigo" id="codigo" placeholder="Código inicial" value="<?php $this->eprint($this->UltimoElemento->Codigo); ?>">
					<span id="totalCertificados" class="help-inline"></span>
				</div>
			</div>
			<input type="hidden" id="idPalestra" name="idPalestra" value="<?php $this->eprint($this->Palestra->IdPalestra); ?>"/>
			<input type="hidden" id="idUsuario" name="idUsuario" value="<?php $this->eprint($this->CURRENT_USER->IdUsuario); ?>"/>
		</div>	
		
		<button id="btnContinuarEmitir" type="submit" class="btn btn-primary btn-large">
			Continuar <i class="icon-arrow-right icon-margin-left"></i>
		</button>
		
		<div id="alertaEmitirCertificados" class="hide alert alert-dark text-large" style="padding-left:8px; margin:15px 0 0 0;">
			Verifique se os dados informados acima estão corretos, pois não será possível alterá-los depois.
			<div>
				<button id="btnEmitirCertificados" class="btn btn-primary">
					SIM, ESTÃO CORRETOS
				</button>
			</div>
		</div>		
	</fieldset>
</form>
<div class="row text-center">
<div id="progresso" class="hide span6 block text-center center inline-block">
	<h3 class="acao"></h3>
	<div class="progress progress-striped active">
	  <div class="progress-bar bar-success bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
		0%
	  </div>
	</div>
</div>
</div>

<div id="alertaDownloadAta" class="hide alert alert-success text-large" style="padding-left:8px;">
	<i class="icon-download icon-big icon-margin-right icon-large" style="font-size:3em; vertical-align:middle; opacity:0.7;"></i>
	
	O documento da ata foi criado e baixado para o seu dispositivo.

	<a href="./certificados-emitidos/" class="btn btn-primary btn-large" style="margin-left:8px;">
		Continuar <i class="icon-arrow-right icon-margin-left"></i>
	</a>
	
</div>

<?php } //só se houver idPalestra na url ?>	
	
</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
