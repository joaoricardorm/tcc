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

	
	//Gera arquivo compactado em zip com os arquivos selecionados
	public function create_zip($files = array(),$destination = '',$overwrite = false) {
		//if the zip file already exists and overwrite is false, return false
		if(file_exists($destination) && !$overwrite) { return false; }
		//vars
		$valid_files = array();
		//if files were passed in...
		if(is_array($files)) {
			//cycle through each file
			foreach($files as $file) {
				//make sure the file exists
				if(file_exists($file)) {
					$valid_files[] = $file;
				}
			}
		}
		//if we have good files...
		if(count($valid_files)) {
			//create the archive
			$zip = new ZipArchive();
			if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
				return false;
			}
			//add the files
			foreach($valid_files as $file) {
				$zip->addFile($file,$file);
			}
			//debug
			//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
			
			//close the zip -- done!
			$zip->close();
			
			//check to make sure the file exists
			return file_exists($destination);
		}
		else
		{
			return false;
		}
	}

	//Esta função manda o arquivo para download
	public function send_download($file)
	{
		header('Content-type: application/zip');
		header('Content-Disposition: attachment; filename="'.$file.'"');
		readfile($file); 
	}
	
	//Compacta arquivos
	public function compactar($arquivos, $nomefinal){
		$files = array($arquivos);
		$zipname = $nomefinal.'.zip';
		$zip = new ZipArchive;
		$zip->open($zipname, ZipArchive::CREATE);
		foreach ($files as $file) {
		  $zip->addFile($file);
		}
		$zip->close();
		
		$arq = './'.$zipname;
		
		///////////////////////////////////////////$this->send_download($arq);
	}
	
	private function geraPDF($arquivo, $html){
		// Incluímos a biblioteca DOMPDF
		require_once("./vendor/dompdf/dompdf_config.inc.php");
		// Instanciamos a classe
		$dompdf = new DOMPDF();
		// Passamos o conteúdo que será convertido para PDF
		$dompdf->load_html($html);
		// Definimos o tamanho do papel e
		// sua orientação (retrato ou paisagem)
		$dompdf->set_paper('a4','landscape');
		// O arquivo é convertido
		$dompdf->render();
		// Salvo no diretório do sistema
		file_put_contents($arquivo, $dompdf->output());
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
$this->geraPDF($arquivo, $html);
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