<?php
	$this->assign('title','Certificados FAROL | Autenticação');
	$this->assign('nav','secureexample');

	$this->display('_Header.tpl.php');
?>

<div class="container frm-login">

<?php //echo '<pre>'; var_dump($this->mandaurl); ECHO '<PRE>'; ?>
	<!-- #### this view/tempalate is used for multiple pages.  the controller sets the 'page' variable to display differnet content ####  -->
	
	<!--<div class="hero-unit">
			<h1>Exemplo de autenticação</h1>
			<p>Este é um exemplo da autenticação do framework.</p>
			<p>
				<a href="secureuser" class="btn btn-primary">Visitar a Página de Usuário</a>&nbsp;&nbsp;&nbsp;
				<a href="secureadmin" class="btn btn-primary">Visitar a Página de Administrador</a>
				<?php //if (isset($this->currentUser)) { ?>
					<a href="logout" class="btn btn-primary btn-large">Logout</a>
				<?php //} ?>
			</p>
	</div>-->
	
	<?php if ($this->page == 'login') { ?>
	
		<form class="well formlogin" method="post" action="login">	
			<h1><i class="icon-user"></i> Área restrita</h1>
			
			<?php if ($this->feedback) { ?>
				<div class="alert alert-error">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<?php $this->eprint($this->feedback); ?>
				</div>
			<?php } ?>
			
			<fieldset>
			<legend>Entre com suas credenciais</legend>
				<div>
					<label>Usuário ou e-mail</label>
					<input id="username" class="input-block-level" name="username" type="text" placeholder="Usuário"" />
					</div>
					<div class="control-group">
					<label style="float:left;">Senha</label>
					<input id="password" class="input-block-level" name="password" type="password" placeholder="Senha" />
				</div>
				<div class="control-group">
				<button type="submit" class="btn btn-primary">Entrar</button>
				</div>
			</fieldset>
		</form>
	
	<?php } else { ?>
	
		<div class="hero-unit">
			<h1>Página segura de <?php $this->eprint($this->page == 'userpage' ? 'Usuário Padrão' : 'Usuário Administrador'); ?></h1>
			<p>Essa página está acessível apenas para <?php $this->eprint($this->page == 'userpage' ? 'usuários padrão' : 'admnistradores'); ?>.  
			Agora você está autenticado como '<strong><?php echo ($this->CURRENT_USER) ? $this->CURRENT_USER->Nome : ''; ?></strong>'</p>
		</div>
	<?php } ?>

	<?php
	$this->display('_Footer.tpl.php');
	?>
	
</div> <!-- /container -->