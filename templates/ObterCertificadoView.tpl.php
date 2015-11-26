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
	
	<h1 class="sem-margin-top"><i class="icon icon-certificate"></i> Obter Certificado(s)
		<span id="loader" class="hide loader progress progress-striped active"><span class="bar"></span></span>
	</h1>
	
	<?php if(!$this->Participante){ ?>
		<h3><i class="icon icon-user"></i> Informe o CPF do palestrante ou participante a qual o certificado pertence</h3>
	<?php } ?>

<?php if(isset($this->ArrPalestraParticipantes)){ ?>


<h1 class="text-success"><i class="icon icon-ok"></i> Aqui está a lista de certificados dos eventos/atividades que essa pessoa participou</h1>

<p>Participante: <strong><?php echo $this->Participante->Nome; ?></strong><br>
   CPF: <strong><?php echo $this->Participante->Cpf; ?></strong></p>

<?php if(sizeof($this->ArrPalestraParticipantes) === 0){ ?>

<h1 class="text-warning">Esse participante não possui nenhum certificado disponível para obtenção</h1>

	<p>
	<ul>
		<li>A presença dele pode não ter sido confirmada no evento/atidade, logo o certificado não está disponível para obtenção;</li>
		<li>Se você acha que a pessoa com o CPF informado participou de um evento ou atividade, por favor entre em contato conosco:<br>
			<strong><?php echo $this->Configuracao->NomeInstituicao; ?></strong><br>
			<i class="icon icon-phone"></i> <?php echo $this->Configuracao->Telefone; ?>
		</li>
	</ul>
	</p>

<?php

} else {

foreach($this->ArrPalestraParticipantes as $palestraParticipante){ ?>

	<hr style="height:1px; border:none; background:#ccc;">
	
	<p>Dados do certificado: <strong>Registro nº <?php echo $palestraParticipante['Certificado']->Codigo.'/'.date('y',strtotime($palestraParticipante['Certificado']->DataEmissao)); ?> Folha <?php echo $palestraParticipante['Certificado']->Folha; ?> do livro nº <?php echo $palestraParticipante['Certificado']->Livro; ?></strong><br>
	Ele foi emitido ao participar <?php echo ($palestraParticipante['Palestra']->ProprioEvento) ? 'do evento' : 'da atividade' ; ?> <strong><?php echo $palestraParticipante['Palestra']->Nome; ?></strong> no dia <strong><?php echo date('d/m/Y',strtotime($palestraParticipante['Palestra']->Data)); ?></strong></p>
	
	<!--<p>Dados do certificado: <strong>Registro nº <?php echo $certificado->Certificado.'/'.date('y',strtotime($this->Certificado->DataEmissao)); ?> Folha <?php echo $this->Certificado->Folha; ?> do livro nº <?php echo $this->Certificado->Livro; ?></strong></p>
	
	<p>Ele foi emitido para <strong><?php $this->eprint($this->Participante->Nome); ?></strong> ao participar <?php echo ($this->Palestra->ProprioEvento) ? 'do evento' : 'da atividade' ; ?> <strong><?php echo $this->Palestra->Nome; ?></strong> no dia <strong><?php echo date('d/m/Y',strtotime($this->Palestra->Data)); ?></strong></p>-->
	
	<!--TROCAR ID PALESTRA POR $this->Certificado->IdCertificado -->
	<p><a id="btnObterCertificado" href="./api/downloadcertificado/<?php echo $palestraParticipante['Certificado']->IdCertificado; ?>" class="btn btn-success margin-right-bigger-sm margin-bottom-5px" style="color:white;">
		<i class="icon-file-pdf-o icon-margin-right"></i> Obter cópia do certificado em PDF
	</a>
	
	<a id="btnObterCertificado" href="./api/downloadcertificado/<?php echo $palestraParticipante['Certificado']->IdCertificado; ?>" class="btn btn-default margin-right-bigger-sm margin-bottom-5px">
		<i class="icon-print icon-margin-right"></i> Imprimir
	</a>
	
	<a id="btnObterCertificado" href="./api/downloadcertificado/<?php echo $palestraParticipante['Certificado']->IdCertificado; ?>" class="btn btn-default margin-bottom-5px">
		<i class="icon-envelope icon-margin-right"></i> Enviar para o e-mail do participante
	</a>
	
	</p>
<?php 
} //foreach 
} //sizeofarray
?>

<?php } else if(isset($_GET['cpf']) && $this->CPFValido == false){ ?>

	<h1 class="text-error"><i class="icon icon-remove"></i> Não foi possível encontrar no sistema certificados para a pessoa com o CPF informado</h1>
	
	<p>CPF informado: <strong> <?php echo $_GET['cpf']; ?></strong></p>
	
	<p>O que fazer?</p>
	<p>
	<ul>
		<li>Você pode ter digitado errado. Confira no formulário abaixo;</li>
		<li>A presença dele pode não ter sido confirmada no evento/atidade, logo o certificado não está disponível para obtenção;</li>
		<li>Se você acha que a pessoa com o CPF informado participou de um evento ou atividade, por favor entre em contato conosco:<br>
			<strong><?php echo $this->Configuracao->NomeInstituicao; ?></strong><br>
			<i class="icon icon-phone"></i> <?php echo $this->Configuracao->Telefone; ?>
		</li>
	</ul>
	</p>
<?php } ?>

<form id="dadosCertificado" class="form-horizontal" method="get">
	<fieldset>
		<div class="well grey">
			<div id="livroInputContainer" class="control-group">
				<label class="control-label" for="codigo">Nº do CPF:</label>
				<div class="controls inline-inputs">
					<input value="<?php if(isset($_GET['cpf'])) echo $_GET['cpf']; ?>" type="text" class="input" name="cpf" id="cpf" placeholder="Nº do CPF:">
				</div>
			</div>
			
		</div>	
		
		<button id="btnContinuarEmitir" type="submit" class="btn btn-primary btn-large">
			<i class="icon-certificate icon-margin-right"></i> Obter certificado(s)
		</button>
	
	</fieldset>
</form>
	
</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>


