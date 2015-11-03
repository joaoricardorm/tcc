<?php
	$this->assign('title','Certifica-µ');
	$this->assign('nav','home');

	$this->display('_Header.tpl.php');
?>

<div class="container">

	<h1>Erro!</h1>

	<!-- this is used by app.js for scraping -->
	<!-- ERROR <?php $this->eprint($this->message); ?> /ERROR -->

	<h3 onclick="$('#stacktrace').show('slow');" class="well" style="cursor: pointer;"><?php $this->eprint($this->message); ?></h3>

	<p>Se esse erro continuar acontecendo, por favor informe ao suporte.</p>

	<div id="stacktrace" class="well hide">
		<p style="font-weight: bold;">Detalhes do erro:</p>
		<?php if ($this->stacktrace) { ?>
			<span style="white-space: nowrap; overflow: auto; margin-top:0; padding-bottom: 15px; font-family: courier new, courier; font-size: 8pt;"><pre><?php $this->eprint($this->stacktrace); ?></pre></span>
		<?php } ?>
	</div>

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>