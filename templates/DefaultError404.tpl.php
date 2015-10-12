<?php
	$this->assign('title','Página não encontrada - Erro 404! - Certificados FAROL');
	$this->assign('nav','home');

	$this->display('_Header.tpl.php');
?>

<div class="container">

	<!-- this is used by app.js for scraping -->
	<!-- ERROR The page you requested was not found /ERROR -->

	<div class="hero-unit">
		<h1>Página não encontrada - Erro 404!</h1>
		<p>A página solicitada não foi encontrada no servidor. Verifique se você inseriu o link correto na URL do site.</p>
	</div>

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>