<?php
	$this->assign('title','Certifica-μ');
	$this->assign('nav','home');

	$this->display('_Header.tpl.php');
?>

	<div class="modal hide fade animated bounceIn modal-on-top"  data-backdrop="static" id="getStartedDialog">
		<div class="modal-header">
			<a class="close btn btn-danger btn-big" data-dismiss="modal">&times;</a>
			<h3>Seja bem vindo(a) ao gerenciador de certificados da <?php echo $this->Configuracao->NomeInstituicao; ?></h3>
			<h4>O que você deseja fazer?</h4>
		</div>
		<div class="modal-body" style="max-height: 300px">

			<h4>UI Controls</h4>

			<p>The UI controls for editing fields are generated based on the database column types.
			The generator doesn't know the <i>purpose</i> of each field, though.  For example an INT
			field may be best displayed as a regular input, a slider or an on/off switch.  It's
			possible that the field shouldn't be editable by the user at all.
			The generator doesn't know these things and so it makes a best guess based on
			column types and sizes.  You will most likely have to switch out UI controls that
			are best for your application.  Bootstrap provides a lot of great UI controls
			for you to use.</p>

		</div>
		<div class="modal-footer">
			<button id="okButton" data-dismiss="modal" class="btn btn-primary">Let's Rock...</button>
		</div>
	</div>

	<div class="container">

		<div class="hero-unit">			
			<h3 class="sem-margin-top">Gerenciador de certificados <?php $this->eprint($this->Configuracao->NomeInstituicao); ?></h3>
			<h1 class="sem-margin-bottom">O que você deseja fazer?</h1>	

			<!--<iframe src="./gera_pdf.php" width="100%" height="500">-->
			
			<div class="row margem-itens first">
				<?php if($this->CURRENT_USER){ //menu disponível apenas para usuários logados ?>
				<div class="item span3">
					
					<a class="btn btn-block btn-primary" data-toggle="modal" href-usar-para-modal="#getStartedDialog" href="./presenca/">
					  <h3 class="white-text">
						<i class="icon-group"></i>
						<span class="block-md">Gerenciar presença de participantes</span>
					  </h3>
					</a>
				
				</div>
				
				<?php if($this->CURRENT_USER->TipoUsuario == Usuario::$P_ADMIN){ //menu disponível somente para usuário administrador ?>
				<div class="item span3">
					
					<a class="btn btn-block btn-primary" href="./configuracao/">
					  <h3 class="white-text">
						<i class="icon-cog"></i>
						<span class="block-md">Alterar configurações do sistema</span>
					  </h3>
					</a>
					
				</div>
				<div class="item span3">
					
					<a class="btn btn-block btn-primary" href="./usuarios/">
					  <h3 class="white-text">
						<i class="icon-user"></i>
						<span class="block-md">Gerenciar usuários do sistema</span>
					  </h3>
					</a>
					
				</div>
				<div class="item span3">
					
					<a class="btn btn-block btn-primary" href="./eventos/">
					  <h3 class="white-text">
						<i class="icon-calendar"></i>
						<span class="block-md">Gerenciar eventos</span>
					  </h3>
					</a>
					
				</div>
				<?php } ?>
				
				<?php } ?>
			</div>
			
			<div class="row margem-itens">
			
				<?php if($this->CURRENT_USER && $this->CURRENT_USER->TipoUsuario == Usuario::$P_ADMIN){ //menu disponível somente para usuário administrador ?>
				<div class="item span3">
					
					<a class="btn btn-block btn-primary" href="./emitir-certificados/">
					  <h3 class="white-text">
						<i class="icon-certificate"></i>
						<span class="block-md">Emitir certificados</span>
					  </h3>
					</a>
					
				</div>
				<?php } ?>
			
				<div class="item span3">
					
					<a class="btn btn-block btn-primary" href="./validar-certificado/">
					  <h3 class="white-text">
						<i class="icon-ok"></i>
						<span class="block-md">Validar certificado</span>
					  </h3>
					</a>
				
				</div>
				<div class="item span3">
					
					<a class="btn btn-block btn-primary" href="./obter-certificado/">
					  <h3 class="white-text">
						<i class="icon-certificate"></i>
						<span class="block-md">Obter certificado(s)</span>
					  </h3>
					</a>
					
				</div>
			</div>
			
		</div>

	</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>