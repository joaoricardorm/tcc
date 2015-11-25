<?php
	$this->assign('title','Validar Certificado - Certifica-μ');
	$this->assign('nav','certificados');
	$this->assign('navegacao','validar-certificado');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	//$LAB.script(base+"scripts/app/emitir-certificados.js?"+Math.floor((Math.random() * 1000) + 1));
</script>

<div id="conteudo" class="container hero-unit">
	
	<h1 class="sem-margin-top"><i class="icon icon-certificate"></i> Validar Certificado
		<span id="loader" class="hide loader progress progress-striped active"><span class="bar"></span></span>
	</h1>

<?php if($this->CertificadoValido){ ?>


<h1 class="text-success"><i class="icon icon-ok"></i> O certificado com os dados informados é válido</h1>
	
	<p>Dados do certificado: <strong>Registro nº <?php echo $this->Certificado->Codigo.'/'.date('y',strtotime($this->Certificado->DataEmissao)); ?> Folha <?php echo $this->Certificado->Folha; ?> do livro nº <?php echo $this->Certificado->Livro; ?></strong></p>
	
	<p>Ele foi emitido para <strong><?php $this->eprint($this->Participante->Nome); ?></strong> ao participar <?php echo ($this->Palestra->ProprioEvento) ? 'do evento' : 'da atividade' ; ?> <strong><?php echo $this->Palestra->Nome; ?></strong> no dia <strong><?php echo date('d/m/Y',strtotime($this->Palestra->Data)); ?></strong></p>
	
	<!--TROCAR ID PALESTRA POR $this->Certificado->IdCertificado -->
	<p><a id="btnObterCertificado" href="./api/downloadcertificado/<?php echo $this->Certificado->IdCertificado; ?>" type="submit" class="btn btn-success btn-large">
		<i class="icon-file-pdf-o icon-margin-right"></i> Obter cópia do certificado em PDF
	</a></p>
	
<?php } ?>

<?php if(isset($_GET['codigo']) && !isset($this->FaltaParametros)){ ?>

	<h1 class="text-error"><i class="icon icon-remove"></i> Não foi possível encontrar no sistema o certificado com os dados informados</h1>
	
	<p>Dados informados: <strong>Registro nº <?php echo $_GET['codigo']; ?> Folha <?php echo $_GET['folha']; ?> do livro nº <?php echo $_GET['livro']; ?></strong></p>
	
	<p>O que fazer?</p>
	<p>
	<ul>
		<li>Você pode ter digitado alguma coisa errada. Confira no formulário abaixo;</li>
		<li>Se você acha que o certificado informado é realmente válido, por favor entre em contato conosco:<br>
			<strong><?php echo $this->Configuracao->NomeInstituicao; ?></strong><br>
			<i class="icon icon-phone"></i> <?php echo $this->Configuracao->Telefone; ?>
		</li>
	</ul>
	</p>
	
<?php } else if ((isset($_GET['livro']) or isset($_GET['folha']) or isset($_GET['codigo'])) && $this->FaltaParametros == true){ echo '<h3 class="text-warning">Faltou preencher algum campo. Verifique.</h3>'; } ?>

<form id="dadosCertificado" class="form-horizontal" method="get">
	<fieldset>
		<div class="well grey">
			<div id="livroInputContainer" class="control-group">
				<label class="control-label" for="codigo">Nº do Registro:</label>
				<div class="controls inline-inputs">
					<input value="<?php if(isset($_GET['codigo'])) echo $_GET['codigo']; ?>" type="text" class="input input-small" name="codigo" id="codigo" placeholder="Nº do Registro:">
				</div>
			</div>
			<div id="folhaInputContainer" class="control-group">
				<label class="control-label" for="folha">Nº da folha:</label>
				<div class="controls inline-inputs">
					<input value="<?php if(isset($_GET['folha'])) echo $_GET['folha']; ?>" type="number" class="input-small" name="folha" id="folha" placeholder="Nº da folha">
				</div>
			</div>
			<div id="codigoInputContainer" class="control-group sem-margin-bottom">
				<label class="control-label" for="livro">Nº do livro:</label>
				<div class="controls inline-inputs">
					<input value="<?php if(isset($_GET['livro'])) echo $_GET['livro']; ?>" type="number" class="input-small" name="livro" id="livro" placeholder="Nº do livro">
				</div>
			</div>
		</div>	
		
		<button id="btnContinuarEmitir" type="submit" class="btn btn-primary btn-large">
			<i class="icon-certificate icon-margin-right"></i> Validar certificado
		</button>
	
	</fieldset>
</form>
	
</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>


