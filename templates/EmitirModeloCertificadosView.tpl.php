<?php
	$this->assign('title','Certificados - Certifica-μ');
	$this->assign('nav','certificados');

	$this->display('_Header.tpl.php');
	

	//PARA SELECIONAR NA TOOLBAR OS ITENS CARREGADOS DO BANCO
	$elementos = $this->ModeloCertificado->Elementos; 

	function selectedToolbar($formatacao=null,$onde,$item=false){	
		if($item == true)
			$grupo = 'span class\="(.*?)dbitem(.*?)';
		else
			$grupo = 'class\="(.*?)';
		
		return preg_match("/{$grupo}({$formatacao}).*?/",$onde); 
	}
?>

<script type="text/javascript">
	$LAB
		.script(base+"scripts/app/modelo-certificados.js?"+Math.floor((Math.random() * 1000) + 1))
		;
	//removi o tagit e jquery ui daqui e coloquei no header	
</script>

<link href="bootstrap/editor-textos/index.css" rel="stylesheet" />

<link href="scripts/tagit-dark-grey.css" rel="stylesheet" />

<?php print_r($_POST); ?>
	

<?php
	//REDIRECIONA PARA O CERTIFICADO DO PALESTRANTE, SE ESTIVER NO DO PARTICIPANTE, OU PARA A PAGINA OBTER CERTIFICADOS
	if(isset($_GET['palestrante']))
		$action = '/obter/'.$this->Palestra->IdPalestra.'?idPalestra='.$this->Palestra->IdPalestra;
	else
		$action = '/modelo/'.$this->Palestra->IdPalestra.'?idPalestra='.$this->Palestra->IdPalestra.'&palestrante=true';
?>

<form id="frmRedirComParticipantes" name="frmRedirComParticipantes" method="post" action="<?php echo $this->ROOT_URL.'emitir-certificados'.$action ?>">
	<input type="hidden" id="idPalestra" name="idPalestra" value="<?php $this->eprint($this->Palestra->IdPalestra); ?>"/>
	<input type="hidden" id="idUsuario" name="idUsuario" value="<?php $this->eprint($this->CURRENT_USER->IdUsuario); ?>"/>
	
	<?php
	//ENCAMINHA POST DE PARTICIPANTES
	if(isset($_POST['cbParticipante'])){
		foreach($_POST['cbParticipante'] as $key => $value)
		{
		  echo '<input type="hidden" name="cbParticipante[]" value="'. $value. '">';
		}
	}
	?>
<form>

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
			<li class="visited"><a id="btnObterAta" href="emitir-certificados/baixar-ata/<?php $this->eprint($this->Palestra->IdPalestra); ?>/"><i class="icon-book"></i>Obter ata <?php echo ($this->Palestra->ProprioEvento) ? 'do evento' : 'da atividade'; ?></a></li>
			<li class="visited"><a href="emitir-certificados/participantes/<?php $this->eprint($this->Palestra->IdPalestra); ?>/?idPalestra=<?php $this->eprint($this->Palestra->IdPalestra); ?>"><i class="icon-group"></i>Participantes</a></li>
			<li class="current"><span><i class="icon-certificate"></i>Formatação</a></span></li>
			<li class="visited"><span class="muted"><i class="icon-certificate"></i>Obter certificados</a></span></li>
		</ol>
		
		
		<div id="alertaDownloadAta" class="hide alert alert-success text-large" style="padding-left:8px; margin:5px 0 0 0;">
	
			<button type="button" class="close" data-dismiss="alert" aria-label="Fechar"><span aria-hidden="true">&times;</span></button>
			
			<i class="icon-download icon-big icon-margin-right icon-large" style="font-size:3em; vertical-align:middle; opacity:0.7;"></i>
			
			O documento da ata foi baixado para o seu dispositivo.
			
		</div>

		
</nav>	

<div id="conteudo" class="container hero-unit">	

<form id="dadosAta" class="form-horizontal" onsubmit="return false;">

	<h3>
			Emitir Certificados da atividade <?php $this->eprint($this->Palestra->Nome); ?>
			<span id="loader" class="loader progress progress-striped active"><span class="bar"></span></span>
	</h3>

	<h1 class="pull-left block-sm"><i class="icon-certificate"></i> Modelo dos certificados dos <?php echo !isset($_GET['palestrante']) ? 'PARTICIPANTES' : 'PALESTRANTES'; ?></h1>
	
	<p class="pull-right">
		<button id="btnContinuar" type="button" class="btn btn-primary btn-large">
			Continuar 
			<i class="icon-arrow-right icon-margin-left"></i> 
			<i class="icon-spin icon-margin-left hidden icon-refresh center inline-block"></i>
		</button>
	</p>
	
<div class="clearfix"></div>

	<fieldset>
	
	
	
	
	
	
	
<div class="accordion" id="accordeonTextos">
  <div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle alert alert-dark not-selectable" onclick="$('.accordion-body').toggleClass('in')">
        <!--<i class="icon-edit"></i> Clique para alterar a formatação e o conteúdo do certificado dos <?php echo !isset($_GET['palestrante']) ? 'PARTICIPANTES' : 'PALESTRANTES'; ?>-->
		<i class="icon-edit"></i> Alterar a formatação do conteúdo do certificado dos <?php echo !isset($_GET['palestrante']) ? 'PARTICIPANTES' : 'PALESTRANTES'; ?>
      </a>
    </div>
    <div id="collapseOne" class="accordion-body collapse animated">
      <div class="accordion-inner">
        
		<!--REMOVER ESSE O HIDE SE CONSEGUIR FAZER FUNCIONAR-->
		<div style="display:block; margin-bottom:25px;">
		
				<div class="caixa verde">
					<div class="t"><i class="icon-move"></i>Arraste aqui os elementos que deverão aparecer nos certificados dos participantes</div>
					<ul id="sortable1" class="connectedSortable">
					</ul>
				</div>
				 
				<div class="caixa vermelha">
					<div class="t"><i class="icon icon-trash icon-large"></i> Arraste aqui os elementos que não deverão aparecer nos certificados</div>
					 <ul id="sortable2" class="tagit connectedSortable">
					</ul>
				</div>
				
				<div class="clearfix"></div>
				
		</div>
				
		
				
				
	 <div class="btn-toolbar" data-spy="affix" data-offset-top="720" data-role="editor-toolbar" data-target="#editor" style="z-index:999;">
	  
	  <div class="pull-left text-center-sm block-sm">
	   
	   <legend class="text-center">Estilos para o conteúdo dinâmico</legend>
	   
	   <div>
		   <div class="ToolBarDbItem geral pull-left btn-group" data-target=".containerPDF .dbitem">
					<a <?php if(selectedToolbar('fontSizeSmall',$elementos,true)) echo 'data-selected="true"'; ?> class="btn unico" data-edit="fontSizeSmall" title="Tamanho pequeno"><i class="icon-font" style="font-size:10px;"></i></a>
					<a <?php if(selectedToolbar('fontSizeNormal',$elementos,true)) echo 'data-selected="true"'; ?> class="btn unico" data-edit="fontSizeNormal" title="Tamanho normal"><i class="icon-font"></i></a>
					<a <?php if(selectedToolbar('fontSizeBig',$elementos,true)) echo 'data-selected="true"'; ?> class="btn unico" data-edit="fontSizeBig" title="Tamanho grande"><i class="icon-font icon-big" style="font-size:18px;"></i></a>
			</div>
			<div class="ToolBarDbItem btn-group" data-target=".containerPDF .dbitem">	
				<a <?php if(selectedToolbar('center-block',$elementos,true)) echo 'data-selected="true"'; ?> style="line-height:0.9em;" class="btn btn-small" data-target=".nomeParticipante, .nomePalestrante" data-edit="center-block" title="Centralizar o nome do participante/palestrante no certificado">Centralizar<br>Nome</a>
			</div>
			<div class="ToolBarDbItem btn-group" data-target=".containerPDF .dbitem">
				<?php echo selectedToolbar('bold',$elementos,true); ?>
				<a <?php if(selectedToolbar('bold',$elementos,true)) echo 'data-selected="true"'; ?> class="btn" data-edit="bold" title="Negrito"><i class="icon-bold"></i></a>
				<a <?php if(selectedToolbar('italic',$elementos,true)) echo 'data-dbitem="true"'; ?> class="btn" data-edit="italic" title="Itálico"><i class="icon-italic"></i></a>
				<a <?php if(selectedToolbar('underline',$elementos,true)) echo 'data-selected="true"'; ?> class="btn" data-edit="underline" title="Sublinhado"><i class="icon-underline"></i></a>
				<input class="inputcordbitem btn btn-default small" type="color"  title="Cor do texto do certificado" onchange="$($(this).parent().data('target')).css('color',$(this).val())">
			</div>
		  </div>
	  </div>
	  
	  
	  <div class="pull-right text-center-sm block-sm">
	  
		  <legend class="text-center">Estilos para todo o certificado</legend>
			
			
		  <div class="ToolBarGeral ToolBarOrientacao geral pull-left btn-group" data-target=".ToolBarOrientacao, .containerA4preview, .containerPDF, .containerCertificado, .assinaturas">
			<a <?php if(selectedToolbar('A4portrait',$elementos)) echo 'data-selected="true"'; ?> id="btnOrientacao" class="btn" data-edit="A4portrait" title="Orientação do papel"><i class="icon-file"></i></a>
		  </div>	
			
		  <div class="ToolBarGeral geral pull-left btn-group" data-target=".containerPDF">
			<a <?php if(selectedToolbar('fontSizeSmall',$elementos)) echo 'data-selected="true"'; ?> class="btn unico" data-edit="fontSizeSmall" title="Tamanho pequeno"><i class="icon-font" style="font-size:10px;"></i></a>
			<a <?php if(selectedToolbar('fontSizeNormal',$elementos)) echo 'data-selected="true"'; ?>  class="btn unico" data-edit="fontSizeNormal" title="Tamanho normal"><i class="icon-font"></i></a>
		  </div>
				
		  <div class="ToolBarGeral geral btn-group pull-right" data-target=".containerPDF, .assinaturas">
		   
			<a class="btn unico" data-edit="justifyleft" title="Alinhar à esquerda"><i class="icon-align-left"></i></a>
			<a class="btn unico" data-edit="justifycenter" title="Centralizar"><i class="icon-align-center"></i></a>
			<a class="btn unico" data-edit="justifyright" title="Alinhar à direita"><i class="icon-align-right"></i></a>
			<a class="btn unico" data-edit="justifyfull" title="Justificar"><i class="icon-align-justify"></i></a>
		
			<a class="btn" data-edit="bold" title="Negrito"><i class="icon-bold"></i></a>
			<a class="btn" data-edit="italic" title="Itálico"><i class="icon-italic"></i></a>
			<input class="btn btn-default small" type="color"  title="Cor do texto do certificado" onchange="$($(this).parent().data('target')).css('color',$(this).val())">
		  </div>
	  
	  </div>
	  
	  <div class="clearfix"></div>
	  
    </div>	
		
		
      </div>
    </div>
  </div>
</div>
	
	
	
   
	
	
	
	
	
	
		
		<div id="framePDF"></div>

		
		<div class="fs-container" style="position:relative;">
		
		<div class="fs containerA4preview">		
		
			  <div class="floatRightGroupBtn">		
				<a onclick="exitFullscreen()" id="btnPreviewCertificadoPDF" class="btn btn-primary btn-small" style="">
					<i class="icon-margin-right icon icon-file-pdf-o"></i> Ver em PDF <i class="icon-margin-left hidden carregando icon-spin icon-refresh"></i>
				</a>
				
				<button id="enter-exit-fs" onkeypress="return false;" onfocus="this.blur()" onclick="enterFullscreen();" title="Visualizar em tela cheia" class="btn btn-primary" style="padding:3px 10px; box-shadow:1px 1px 0 rgba(255,255,255,1); border:1px solid white;"><i class="icon icon-fullscreen"></i></button>
			  </div>
			
			<div class="outer">
				<div id="previewCertificado" class="inner text-center containerCertificado">
				
					<!--Certificado enviado para PDF-->
					<div class="containerPDF">
						<div class="center-block">
							<img id="ImagemLogo" src="<?php $this->eprint($this->ROOT_URL); ?>images/uploads/logos/small/<?php $this->eprint($this->Configuracao->ImagemLogo); ?>" />
						</div>
						
						<div id="TituloCertificado" class="center-block reset-css">Certificado</div>
						
						<div class="center-block">
							<img id="TituloMarcador" src="<?php echo $this->ROOT_URL; ?>styles/certificados/images/marcador-titulo-padrao.png" />
						</div>
						
						<div id="containerDinamico">
							<i class="icon-big icon-spin icon-refresh center inline-block" style="margin-top:10%; font-size:36px;"></i>
						</div>
					</div>
					<!--Certificado enviado para PDF-->
					
					<!--<div class="registro bottom justifyleft fixed-pdf">
						<div class="assinatura center-block metade">
							<img id="AssinaturaPalestrante" src="images/uploads/logos/small/32dc6fa8ca13a53ebcad2053e87165fb.png" />
							<hr>
						</div>
						<div id="AssinaturaParticipante" class="assinatura center-block metade">
						</div>
					</div>
					
					<div class="linhasAssinaturas">
							<hr class="metade">
							<hr class="metade">
					</div>-->
					
					<table class="assinaturas <?php if(isset($_GET['palestrante'])) echo 'palestrante"'; ?>">
						<tr>
							<td><img id="AssinaturaPalestrante" class="assinatura" src="<?php $this->eprint($this->ROOT_URL); ?>images/uploads/logos/small/32dc6fa8ca13a53ebcad2053e87165fb.png" /></td>
							<?php if(!isset($_GET['palestrante'])) echo '<td class="hide-palestrante"></td>'; ?>
						</tr>
						<tr>
							<td><hr></td>
							<?php if(!isset($_GET['palestrante'])) echo '<td class="hide-palestrante"><hr></td>'; ?>
						</tr>
						<tr>
							<td><small><strong>Nome do Palestrantre</strong><br>Cargo do Palestrante</small></td>
							<?php 
								if(!isset($_GET['palestrante'])) 
									echo '<td class="hide-palestrante"><small><strong>Nome do Participante</strong></small></td>';
							?>
						</tr>
					</table>
					
					<div class="rodapeCertificado registro bottom-left justifyleft fixed-pdf">
							Registro nº 9081/15 folha 86 do livro nº 2
					</div>
					
					<div class="rodapeCertificado justifycenter autenticidade bottom-right fixed-pdf">
						confirme a autenticidade deste certificado em
						<span class="siteCertificado"><?php $this->eprint($this->ROOT_URL.'validar-certificado/'); ?></span>
					</div>
					
					

				</div>
			</div>
		
		</div>

		</div>	
		
	</fieldset>
	
</form>
	
</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
