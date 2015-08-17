<!DOCTYPE html>
<html lang="pt-BR">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-Frame-Options" content="deny">
		<base href="<?php $this->eprint($this->ROOT_URL); ?>" />
		<title><?php $this->eprint($this->title); ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="description" content="Certificados FAROL" />
		<meta name="author" content="phreeze builder | phreeze.com" />

		<!-- Le styles -->
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" />
		<link href="styles/style.css" rel="stylesheet" />
		<link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
		<link href="bootstrap/css/font-awesome.min.css" rel="stylesheet" />
		<!--[if IE 7]>
		<link rel="stylesheet" href="bootstrap/css/font-awesome-ie7.min.css">
		<![endif]-->
		<link href="bootstrap/css/datepicker.css" rel="stylesheet" />
		<link href="bootstrap/css/timepicker.css" rel="stylesheet" />
		<link href="bootstrap/css/bootstrap-combobox.css" rel="stylesheet" />
		
		<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
			<script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- Le fav and touch icons -->
		<link rel="shortcut icon" href="images/favicon.ico" />
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/apple-touch-icon-114-precomposed.png" />
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/apple-touch-icon-72-precomposed.png" />
		<link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon-57-precomposed.png" />

		<script type="text/javascript" src="scripts/libs/LAB.min.js"></script>
		<script type="text/javascript">
			$LAB.script("scripts/jquery-1.8.2.js").wait()
				.script("bootstrap/js/bootstrap.min.js")
				.script("bootstrap/js/bootstrap-datepicker.js")
				.script("bootstrap/js/bootstrap-timepicker.js")
				.script("bootstrap/js/bootstrap-combobox.js")
				.script("scripts/libs/underscore-min.js").wait()
				.script("scripts/libs/underscore.date.min.js")
				.script("scripts/libs/backbone-min.js")
				.script("scripts/app.js")
				.script("scripts/model.js").wait()
				.script("scripts/view.js").wait()
		</script>

	</head>

	<body>
			<div class="navbar navbar-inverse navbar-fixed-top">
				<div class="navbar-inner">
					<div class="container">
						<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</a>
						<a class="brand" href="./">Certificados FAROL</a>
						<div class="nav-collapse collapse">
							<ul class="nav">
								<li <?php if ($this->nav=='certificados') { echo 'class="active"'; } ?>><a href="./certificados">Certificados</a></li>
								<li <?php if ($this->nav=='configuracoes') { echo 'class="active"'; } ?>><a href="./configuracoes">Configuracoes</a></li>
								<li <?php if ($this->nav=='eventos') { echo 'class="active"'; } ?>><a href="./eventos">Eventos</a></li>
								<li <?php if ($this->nav=='modelocertificados') { echo 'class="active"'; } ?>><a href="./modelocertificados">ModeloCertificados</a></li>
							</ul>
							<ul class="nav">
								<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">More <b class="caret"></b></a>
								<ul class="dropdown-menu">
								<li <?php if ($this->nav=='palestras') { echo 'class="active"'; } ?>><a href="./palestras">Palestras</a></li>
								<li <?php if ($this->nav=='palestrapalestrantes') { echo 'class="active"'; } ?>><a href="./palestrapalestrantes">PalestraPalestrantes</a></li>
								<li <?php if ($this->nav=='palestraparticipantes') { echo 'class="active"'; } ?>><a href="./palestraparticipantes">PalestraParticipantes</a></li>
								<li <?php if ($this->nav=='palestrantes') { echo 'class="active"'; } ?>><a href="./palestrantes">Palestrantes</a></li>
								<li <?php if ($this->nav=='participantes') { echo 'class="active"'; } ?>><a href="./participantes">Participantes</a></li>
								<li <?php if ($this->nav=='usuarios') { echo 'class="active"'; } ?>><a href="./usuarios">Usuarios</a></li>
								</ul>
								</li>
							</ul>
							<?php  if ($this->CURRENT_USER) { ?>
							<ul class="nav pull-right">
								<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-lock"></i> Olá <?php $this->eprint($this->CURRENT_USER->Nome); ?> <i class="caret"></i></a>
								<ul class="dropdown-menu">
									<li><a href="./logout">Sair</a></li>
									<li class="divider"></li>
									<li><a href="./secureuser">Example User Page <i class="icon-lock"></i></a></li>
									<li><a href="./secureadmin">Example Admin Page <i class="icon-lock"></i></a></li>
								</ul>
								</li>
							</ul>
							<?php } else { ?>
							<ul class="nav pull-right">
								<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-lock"></i> Login <i class="caret"></i></a>
								<ul class="dropdown-menu">
									<li><a href="./loginform">Login</a></li>
									<li class="divider"></li>
									<li><a href="./secureuser">Example User Page <i class="icon-lock"></i></a></li>
									<li><a href="./secureadmin">Example Admin Page <i class="icon-lock"></i></a></li>
								</ul>
								</li>
							</ul>
							<?php } ?>
						</div><!--/.nav-collapse -->
					</div>
				</div>
			</div>