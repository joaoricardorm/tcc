<?php
	$this->assign('title','Validar Certificado - Certifica-μ');
	$this->assign('nav','certificados');
	$this->assign('navegacao','validar-certificado');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript" src="./scripts/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="./scripts/jquery.maskedinput.min.js"></script>
<script type="text/javascript" src="./scripts/app.js"></script>

<script type="text/javascript">
	//$LAB.script(base+"scripts/app/emitir-certificados.js?"+Math.floor((Math.random() * 1000) + 1));
	
	$(document).ready(function(){
		//mascara para telefone e cnpj
		$("#cpf").mask("999.999.999-99",{placeholder:"_", autoclear: false });
		
		$('.btnImprimirCertificado').click(function(e){
			e.preventDefault();
			var urlPDFImpressao = $(this).attr('href');
			app.printPDF(urlPDFImpressao);
		});
		
		if(typeof app.getUrlParameter('s') !== 'undefined' && app.getUrlParameter('s') !== ''){
			app.alertaAnimado('body', 'mousemove', '#alertaEmailEnviado',7000);
			
			setTimeout(function(){
				$('body').unbind('mousemove');
				$('#alertaEmailEnviado').addClass('hidden');
			}, 7000);
		}

	});
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
	
	<?php 
		if(isset($this->Participante->IdPalestrante)){
			$urlDownload = './api/downloadcertificadopalestrante/'.$palestraParticipante['Palestra']->IdPalestra.'/'.$this->Participante->IdPalestrante.'/';			
			$urlImprimir = './api/mesclarcertificados/palestra/'.$palestraParticipante['Palestra']->IdPalestra.'?palestrantes=['.$this->Participante->IdPalestrante.']';
			$urlEmail = './api/enviaremailcertificados/palestra/'.$palestraParticipante['Palestra']->IdPalestra.'?palestrantes=['.$this->Participante->IdPalestrante.']&voltar=true';
		} else {
			$urlDownload = './api/downloadcertificadoparticipante/'.$palestraParticipante['Palestra']->IdPalestra.'/'.$this->Participante->IdParticipante.'/';
			$urlImprimir = './certificados-gerados/'.AppBaseController::ParseUrl($palestraParticipante['Palestra']->Nome).'-'.$palestraParticipante['Palestra']->IdPalestra.'/palestra'.$this->Participante->IdParticipante.'.pdf';
			$urlEmail = './api/enviaremailcertificados/palestra/'.$palestraParticipante['Palestra']->IdPalestra.'?participantes=['.$this->Participante->IdParticipante.']&voltar=true';				
		}
		
		//VERIFICA SE EXISTE ARQUIVO PARA DOWNLOAD
		$handle = curl_init($this->ROOT_URL.$urlDownload);
		curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

		/* Get the HTML or whatever is linked in $url. */
		$response = curl_exec($handle);

		/* Check for 404 (file not found). */
		$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
		if($httpCode == 401 or $httpCode == 404) {
			$temArquivoDownload = false;
		} else {
			$temArquivoDownload = true;
		}

		curl_close($handle);
	?>
	
	<p>
	
	<?php if($temArquivoDownload){ ?>
		<a id="btnObterCertificado" href="<?php echo $urlDownload; ?>" type="submit" class="btn btn-success margin-right-bigger-sm margin-bottom-5px">
			<i class="icon-file-pdf-o icon-margin-right"></i> Obter cópia do certificado em PDF
		</a>
	
		<a id="btnImprimirCertificado" href="<?php echo $urlImprimir; ?>" class="btnImprimirCertificado btn btn-default margin-right-bigger-sm margin-bottom-5px">
			<i class="icon-print icon-margin-right"></i> Imprimir
		</a>
		
		<?php if($this->Participante->Email != ''){ ?>
			<a id="btnObterCertificado" href="<?php echo $urlEmail; ?>" class="btn btn-default margin-bottom-5px">
				<i class="icon-envelope icon-margin-right"></i> Enviar para o e-mail do participante
			</a>
		<?php } ?>
		
	<?php } else { ?>
		<div class="well grey well-small">
		<small>
		<p class="sem-margin-bottom">O arquivo do certificado para <?php echo ($palestraParticipante['Palestra']->ProprioEvento) ? 'este evento' : 'esta atividade' ; ?> ainda não foi gerado pelo administrador do sistema.</p>
		<p class="sem-margin-bottom">Aguarde alguns dias ou entre em contato conosco:<br>
			<strong><?php echo $this->Configuracao->NomeInstituicao; ?></strong><br>
			<i class="icon icon-phone"></i> <?php echo $this->Configuracao->Telefone; ?>
		</p>
		</small>
		</div>
	<?php } ?>	
	
	</p>	
	
<?php 
} //foreach 
} //sizeofarray
?>

<div id="alertaHabilitarPopup" class="hide alert alert-dark text-large" style="padding-left:8px; margin:-5px 0 20px 0;">
	
			<button type="button" class="close" data-dismiss="alert" aria-label="Fechar"><span aria-hidden="true">&times;</span></button>
			
			<i class="icon-print icon-big icon-margin-right icon-large" style="font-size:2em; vertical-align:middle; opacity:0.7;"></i>
			<i class="icon-exclamation icon-margin-right icon-large" style="font-size:1.2em; vertical-align:middle; opacity:0.7;"></i>
			
			Talvez seja necessário desabilitar o bloqueador de popups de seu navegador para realizar a impressão.
			
</div>	

<div id="alertaEmailEnviado" class="hide alert alert-dark text-large" style="padding-left:8px; margin:-5px 0 20px 0;">
	
			<button type="button" class="close" data-dismiss="alert" aria-label="Fechar"><span aria-hidden="true">&times;</span></button>
			
			<i class="icon-envelope icon-big icon-margin-right icon-large" style="font-size:2em; vertical-align:middle; opacity:0.7;"></i>
			<i class="icon-ok icon-margin-right icon-large" style="font-size:1.2em; vertical-align:middle; opacity:0.7;"></i>
			
			O certificado foi enviado para o e-mail.
			
</div>	

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


