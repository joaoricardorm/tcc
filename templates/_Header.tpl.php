<!DOCTYPE html>
<html lang="pt-BR">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-Frame-Options" content="deny">
		
		<!--forca IE a se comportar como edge-->
		<meta http-equiv="X-UA-Compatible" content="IE=IE-8" />
		
		<base href="<?php $this->eprint($this->ROOT_URL); ?>" />
		<title><?php $this->eprint($this->title); ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="description" content="Certifica-μ" />
		<meta name="author" content="João Ricardo Alves de Paula - joaoricardo.rm@gmail.com" />

		<!-- Le styles minificados pelo gulp cssmin -->
		<link href="bootstrap/css/min/bootstrap-original-unminified.css" rel="stylesheet" />
		
		<link href="styles/style.css" rel="stylesheet" />
		<link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
		<link href="bootstrap/css/font-awesome.min.css" rel="stylesheet" />
		<link href="styles/animate.min.css" rel="stylesheet" />
		<!--[if IE 7]>
		<link rel="stylesheet" href="bootstrap/css/font-awesome-ie7.min.css">
		<![endif]-->
		<link href="bootstrap/css/datepicker.css" rel="stylesheet" />
		<link href="bootstrap/css/timepicker.css" rel="stylesheet" />
		<link href="bootstrap/css/bootstrap-combobox.css" rel="stylesheet" />
		<link href="bootstrap/css/bootstrap-select.css" rel="stylesheet" />
		<link href="bootstrap/css/bootstrap-multiselect.css" rel="stylesheet" />
		<link href="bootstrap/css/bootstrap-modal.css" rel="stylesheet" />
		
		<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
			<script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<link rel="apple-touch-icon" sizes="57x57" href="images/favicon/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="images/favicon/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="images/favicon/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="images/favicon/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="images/favicon/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="images/favicon/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="images/favicon/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="images/favicon/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="images/favicon/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="images/favicon/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
		<link rel="manifest" href="images/favicon/manifest.json">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="images/favicon/ms-icon-144x144.png">
		<meta name="theme-color" content="#483d8b">

		<!-- Le fav and touch icons 
		<link rel="shortcut icon" href="images/favicon.ico" />
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/apple-touch-icon-114-precomposed.png" />
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/apple-touch-icon-72-precomposed.png" />
		<link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon-57-precomposed.png" /> -->

		
		
		<script type="text/javascript" src="scripts/libs/LAB.min.js"></script>
		<script type="text/javascript">
			var base = '<?php $this->eprint($this->ROOT_URL); ?>';
			$LAB.script(base+"scripts/jquery-1.8.2.min.js").wait()				
				.script(base+"bootstrap/js/bootstrap.min.js")
				.script(base+"bootstrap/js/bootstrap-datepicker.js")
				.script(base+"bootstrap/js/bootstrap-datepicker.pt-BR.js").wait()
				.script(base+"bootstrap/js/bootstrap-timepicker.js")
				.script(base+"bootstrap/js/bootstrap-combobox.js")
				.script(base+"bootstrap/js/bootstrap-select.js")
				.script(base+"bootstrap/js/bootstrap-multiselect.js")	
				.script(base+"bootstrap/js/bootstrap-confirmation.js")
				.script(base+"bootstrap/js/bootstrap-bootbox.min.js")
				.script(base+"bootstrap/js/bootstrap-tooltip.js")
				.script(base+"bootstrap/js/bootstrap-modalmanager.js")
				.script(base+"bootstrap/js/bootstrap-modal.js")
				.script(base+"scripts/libs/underscore-min.js").wait()
				.script(base+"scripts/libs/underscore.date.min.js")
				.script(base+"scripts/libs/backbone-min.js")
				.script(base+"scripts/global.js")
				.script(base+"scripts/jquery.maskedinput.min.js").wait()
				.script(base+"scripts/app.js")
				.script(base+"scripts/model.js").wait()
				.script(base+"scripts/view.js").wait()
		</script>
	
	</head>

	<body>
			<div class="navbar navbar-inverse navbar-fixed-top">
				<div class="navbar-inner">
					<div class="container">	
						<?php if ($this->CURRENT_USER) { ?>
							<div class="pull-left">
							<a tabindex="1" class="btn btn-navbar hidden-lg" data-toggle="collapse" data-target=".nav-collapse">
								<span class="icon-reorder sem-margin-right"></span>
								<span class="hidde-sxs"> Menu</span>
							</a>
							</div>
						
							<ul class="nav pull-right">
								<li class="dropdown">
								
								<a href="#" tabindex="2" class="dropdown-toggle hidden-xlg" data-toggle="dropdown"><i class="icon-lock"></i><?php $this->eprint($_SESSION['nomeUser']); ?><i class="caret"></i></a>
								
								<a href="#" tabindex="2" class="btn btn-navbar dropdown-toggle" data-toggle="dropdown"><i class="icon-lock sem-margin-right-xxs"></i><span class="hidden-xxs"><?php $this->eprint($_SESSION['nomeUser']); ?></span><i class="caret"></i></a>
								
								<ul class="dropdown-menu">
									<li class="disabled escondido visible-xxs"><a tabindex="-1"><?php $this->eprint($_SESSION['nomeUser']); ?></a></li>
									<li><a tabindex="2" href="./logout">Sair</a></li>
								</ul>
								</li>
							</ul>
						<?php } else { ?>
							<ul class="nav pull-right hidden-xlg">
								<li><a tabindex="2" href="loginform"><i class="icon-lock sem-margin-right-xxs"></i>Área restrita</a></li>
							</ul>
							
							<a tabindex="2" href="./loginform" class="btn btn-navbar pull-right">
									<span class="icon-lock sem-margin-right-xxs"></span><span class="hidden-xxs">Área restrita</span>
							</a>	
						<?php } ?>	
						
						
						<a class="brand" href="./" tabindex="1" autofocus="autofocus">
							<span>Certifica-<big>µ</big></span>
						</a>			
						
						<?php if($this->CURRENT_USER){ ?>
						<div class="nav-collapse collapse">
								<ul class="nav">
								<?php if($this->CURRENT_USER->TipoUsuario == Usuario::$P_ADMIN){ //menu completo disponível somente para administrador ?>
									<li <?php if ($this->nav=='configuracao') { echo 'class="active"'; } ?>><a tabindex="1" href="./configuracao/"><i class="icon-cog"></i> Configurações</a></li>
									<li <?php if ($this->nav=='usuarios') { echo 'class="active"'; } ?>><a tabindex="1" href="./usuarios/"><i class="icon-user"></i> Usuários</a></li>
									<li <?php if ($this->nav=='eventos') { echo 'class="active"'; } ?>><a tabindex="1" href="./eventos/"><i class="icon-calendar"></i> Eventos</a></li>
								<?php } ?>
									<li <?php if ($this->nav=='presenca') { echo 'class="active"'; } ?>><a tabindex="1" href="./presenca/participantes/"><i class="icon-group"></i> Presença</a></li>
								</ul>
								<ul class="nav">
									<li class="dropdown <?php if ($this->nav=='certificados') echo 'active'; ?>">
									<a tabindex="1" href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-certificate"></i> Certificados <b class="caret"></b></a>
										<ul class="dropdown-menu">
											<?php if($this->CURRENT_USER->TipoUsuario == Usuario::$P_ADMIN){ //menu completo disponível somente para administrador ?>
												<li <?php if (isset($this->navegacao) && $this->navegacao=='emitir-certificados') { echo 'class="active"'; } ?>><a href="./emitir-certificados/"><i class="icon-certificate"></i>Emitir Certificados</a></li>
											<?php } ?>
											<li <?php if (isset($this->navegacao) && $this->navegacao=='validar-certificado') { echo 'class="active"'; } ?>><a href="./validar-certificado/"><i class="icon-ok"></i>Validar Certificado</a></li>
											<li <?php if (isset($this->navegacao) && $this->navegacao=='obter-certificados') { echo 'class="active"'; } ?>><a href="./obter-certificado/"><i class="icon-certificate"></i>Obter Certificado(s)</a></li>
										</ul>
									</li>
								</ul>
								<!--<ul class="nav">
									<li class="dropdown">
									<a tabindex="1" href="#" class="dropdown-toggle" data-toggle="dropdown">Mais <b class="caret"></b></a>
										<ul class="dropdown-menu">
											<li <?php if ($this->nav=='modelocertificados') { echo 'class="active"'; } ?>><a  href="./modelocertificados/">ModeloCertificados</a></li>
											<li <?php if ($this->nav=='certificados') { echo 'class="active"'; } ?>><a href="./certificados/">Certificados</a></li>
											<li <?php if ($this->nav=='palestras') { echo 'class="active"'; } ?>><a href="./palestras/">Palestras</a></li>
											<li <?php if ($this->nav=='palestrapalestrantes') { echo 'class="active"'; } ?>><a href="./palestrapalestrantes/">PalestraPalestrantes</a></li>
											<li <?php if ($this->nav=='palestraparticipantes') { echo 'class="active"'; } ?>><a href="./palestraparticipantes/">PalestraParticipantes</a></li>
											<li <?php if ($this->nav=='palestrantes') { echo 'class="active"'; } ?>><a href="./palestrantes/">Palestrantes</a></li>
											<li <?php if ($this->nav=='participantes') { echo 'class="active"'; } ?>><a href="./participantes/">Participantes</a></li>
										</ul>
									</li>
								</ul>-->
							
						</div><!--/.nav-collapse -->
						<?php } ?>
					</div>
				</div>
			</div>