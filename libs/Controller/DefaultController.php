<?php
/** @package Tcc::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");

/**
 * DefaultController is the entry point to the application
 *
 * @package Tcc::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class DefaultController extends AppBaseController
{

	/**
	 * Override here for any controller-specific functionality
	 */
	protected function Init()
	{
		parent::Init();

		// TODO: add controller-wide bootstrap code
		
		// TODO: if authentiation is required for this entire controller, for example:
		// $this->RequirePermission(ExampleUser::$PERMISSION_USER,'SecureExample.LoginForm');
		
		// Requer permissão de acesso
		//$this->RequirePermission(Usuario::$P_ADMIN,
			//	'SecureExample.LoginForm',
			//	'Autentique-se para acessar esta página',
			//	'Você não possui permissão para acessar essa página ou sua sessão expirou');
		
	}
	
	/**
	 * Display the home page for the application
	 */
	public function HomeGeraPDF()
	{
		
$html = '
<html>
<head>
<style>

/* Type some style rules here */
@page, html { margin:0; padding:0; }
html { background:Moccasin; }
body {margin:30px; }
h3 { color:red; margin:30px;  }
</style>
</head>

<body>

<!-- Type some HTML here -->
<h3>Rafael <i>doissssdão</i></h3>';

for($i=0;$i<=25;$i++)
	$html .= 'LRICARDODOHSAO HFODAHSO HOFDHA OFDOASH'.$i.'<br>';
$html .= '
</body>
</html>
';

$arquivo = 'arquivo-em-pdf-22.pdf';
$caminho = GlobalConfig::$APP_ROOT.'/certificados-gerados/';
$this->geraPDF($arquivo, $caminho, $html);
$this->compactar($arquivo,$arquivo);

//echo '<iframe src="http://docs.google.com/gview?url='.GlobalConfig::$ROOT_URL.$arquivo.'&embedded=true" style="width:718px; height:700px;" frameborder="0"></iframe>';

echo '
<script type="text/javascript">
function printPDF() 
{
    var w = window.open("'.GlobalConfig::$ROOT_URL.$arquivo.'", "", "toolbar=no, scrollbars=no, resizable=yes, top=50, left=50, width="+(screen.width-100)+", height="+(screen.height-200)+"");
	setTimeout(function(){ w.print(); },500);
}
</script>

<a onclick="printPDF()">Imprimir PDF</a>

<embed id="iwc" name="iwc" src="'.GlobalConfig::$ROOT_URL.$arquivo.'" width="885" height="628" wmode="transparent" type="application/pdf" style="display:block; margin:0 auto;">';

		$this->Render();
	}
	
	/**
	 * Display the home page for the application
	 */
	public function Home()
	{
		$this->Render();
	}

	/**
	 * Displayed when an invalid route is specified
	 */
	public function Error404()
	{
		$this->Render();
	}

	/**
	 * Display a fatal error message
	 */
	public function ErrorFatal()
	{
		$this->Render();
	}

	public function ErrorApi404()
	{
		$this->RenderErrorJSON('An unknown API endpoint was requested.');
	}

}
?>