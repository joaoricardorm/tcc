<?php
	$this->assign('title','Obter Certificados - Certifica-μ');
	$this->assign('nav','certificados');
	$this->assign('navegacao','emitir-certificados');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB.script(base+"scripts/app/obter-certificados.js");
</script>

<?php if($this->Palestra){ ?>

<!--Lista de participantes que receberão os certificados-->

<!--["111","112","113","115","116","117","118"]-->

<textarea name="listaParticipantes" id="listaParticipantes">
<?php if(isset($_POST['cbParticipante'])) echo json_encode($_POST['cbParticipante']); ?>
</textarea>

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
			<li class="visited"><a href="emitir-certificados/participantes/<?php $this->eprint($this->Palestra->IdPalestra); ?>/?idPalestra=<?php $this->eprint($this->Palestra->IdPalestra); ?>"><i class="icon-group"></i>Participantes</a></li>
			<li class="visited"><a href="emitir-certificados/modelo/<?php $this->eprint($this->Palestra->IdPalestra); ?>/?idPalestra=<?php $this->eprint($this->Palestra->IdPalestra); ?>"><i class="icon-certificate"></i>Formatação</a></li>
			<li class="current"><span class="muted"><i class="icon-certificate"></i>Obter certificados</a></span></li>
		</ol>
		
		
		<div id="alertaDownloadAta" class="hide alert alert-success text-large" style="padding-left:8px; margin:5px 0 0 0;">
	
			<button type="button" class="close" data-dismiss="alert" aria-label="Fechar"><span aria-hidden="true">&times;</span></button>
			
			<i class="icon-download icon-big icon-margin-right icon-large" style="font-size:3em; vertical-align:middle; opacity:0.7;"></i>
			
			O documento da ata foi baixado para o seu dispositivo.
			
		</div>

		
</nav>	
	
<?php } ?>


	<div class="container">

		<div class="hero-unit">		


			<h3 class="sem-margin-top">
				Emitir Certificados da atividade <?php $this->eprint($this->Palestra->Nome); ?>
				<span id="loader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>

			<h1 class="sem-margin-bottom"><i class="icon-certificate"></i> Como você deseja obter os certificados?</h1>	   

			<!--<iframe src="./gera_pdf.php" width="100%" height="500">-->
			
			<div class="row margem-itens first">

				<div class="item span4">
					
					<label for="cbkImprimir" class="btn btn-default btn-block">
					  <h3>
						<big><i class="icon-print icon-large"></i></big>
						<span class="block-md">	
						Imprimir
						<div class="margin-top-10px">
							<input checked id="cbkImprimir" class="checkbox-custom" name="checkbox-3" type="checkbox">
							<label for="cbkImprimir"class="checkbox-custom-label"></label>   
						</div>
						</span>
						
					  </h3>
					</a>
					
				</div>
				
				
				<div class="item span4">
					
					<label for="cbkPDF" class="btn btn-default btn-block">
					  <h3>
						<big><i class="icon-file-pdf-o icon-large"></i></big>
						<span class="block-md">	
						PDF
						<div class="margin-top-10px">
							<input checked="true" id="cbkPDF" class="checkbox-custom" name="checkbox-3" type="checkbox">
							<label for="cbkPDF"class="checkbox-custom-label"></label>   
						</div>
						</span>
						
					  </h3>
					</a>
					
				</div>
				
				<div class="item span4">
					
					<label for="cbkEmail" class="btn btn-default btn-block">
					  <h3>
						<big><i class="icon-envelope icon-large"></i></big>
						<span class="block-md">	
						E-mail *
						<div class="margin-top-10px">
							<input id="cbkEmail" class="checkbox-custom" name="checkbox-3" type="checkbox">
							<label for="cbkEmail"class="checkbox-custom-label"></label>   
						</div>
						</span>						
					  </h3>
					</a>
					
				</div>				
				
			</div>
			
			<div class="clearfix"></div>
			
			<button id="btnObterCertificados" type="submit" class="hide btn btn-primary pull-left btn-large margin-top-30px">
				<i class="icon margin-right-10px icon-certificate"></i>OBTER CERTIFICADOS
				<i class="icon-spin icon-margin-left hidden icon-refresh center inline-block"></i>
			</button>
			
			<div class="margin-top-20px pull-right block-sm" style="font-size:16px; margin-left:30px; line-height:normal; padding-top:10px; max-width:500px; text-align:right;">
				<small>* os certificados serão enviados para para <?php $this->eprint($this->CURRENT_USER->Email); ?>, para o(s) palestrante(s) e para os participantes com presença confirmada</small>
			</div>
			
			<div class="clearfix"></div>
			
			
		<div id="alertaDownloadCertificados" class="hide alert alert-success text-large" style="padding-left:8px; margin:15px 0 0 0;">
	
			<button type="button" class="close" data-dismiss="alert" aria-label="Fechar"><span aria-hidden="true">&times;</span></button>
			
			<i class="icon-download icon-big icon-margin-right icon-large" style="font-size:3em; vertical-align:middle; opacity:0.7;"></i>
			
			Os arquivos de certificados dos palestrantes e de participantes foram baixados para o seu dispositivo.
			
		</div>
			
			
			
			<div class="row text-center">
			<div id="progresso" class="hidden span6 block text-center center inline-block" style="display:block;">
				<h3 class="acao"></h3>
				<div class="progress progress-striped active">
				  <div class="progress-bar bar-success bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
					0%
				  </div>
				</div>
			</div>
			</div>
			
			
		</div>

	</div> <!-- /container -->
	
<?php
	$this->display('_Footer.tpl.php');
?>