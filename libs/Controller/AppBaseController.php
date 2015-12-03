<?php
/** @package    Certificados FAROL::Controller */

/** import supporting libraries */
require_once("verysimple/Phreeze/Controller.php");

/**
 * AppBaseController is a base class Controller class from which
 * the front controllers inherit.  it is not necessary to use this
 * class or any code, however you may use if for application-wide
 * functions such as authentication
 *
 * @package Certificados FAROL::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class AppBaseController extends Controller
{

	static $DEFAULT_PAGE_SIZE = 10;

	public $Configuracao;
	
	//Esta função manda o arquivo para download
	public function send_download($file, $new_filename = false)
	{
		if(!$new_filename) $new_filename = $file;
		header('Content-type: application/zip');
		header('Content-Disposition: attachment; filename="'.$new_filename.'"');
		readfile($file); 
	}
	
	/* GERA ARQUIVO EM PDF */
	public function geraPDF($arquivo, $caminho, $html, $papel='a4', $orientacao='landscape'){
		// Incluímos a biblioteca DOMPDF
		require_once("./vendor/dompdf/dompdf_config.inc.php");
		// Instanciamos a classe
		$dompdf = new DOMPDF();
		// Passamos o conteúdo que será convertido para PDF
		$dompdf->load_html($html);
		// Definimos o tamanho do papel e
		// sua orientação (retrato ou paisagem)
		$dompdf->set_paper($papel,$orientacao);
		// O arquivo é convertido
		$dompdf->render();
		
		//CRIA A PASTA SE NÃO EXISTIR
		if (!file_exists($caminho)) {
		   mkdir($caminho, 0777, true);
		}
		// Salvo no diretório do sistema
		file_put_contents($caminho.$arquivo, $dompdf->output());
	}
	
	//Esta função manda o arquivo para download
	public function downloadArquivo($arquivo, $nome_download)
	{
		$ext = pathinfo($arquivo, PATHINFO_EXTENSION);
		header('Content-type: application/'.$ext);
		header('Content-Disposition: attachment; filename="'.$nome_download.'.'.$ext.'"');
		readfile($arquivo); 
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
	
	//Compacta arquivos
	public function compactar($arquivos, $novosNomes = false, $destino = './', $nomefinal,$overwrite=false){
		$files = $arquivos;
		
		$zipname = $destino.$nomefinal.'.zip';
		$zipname=iconv("UTF-8", "ISO-8859-1//TRANSLIT", $zipname);
		
		$zip = new ZipArchive;
		$zip->open($zipname, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE);
		
		$i=0;
		foreach ($files as $file) {
			//echo  $file ;
			if($novosNomes)
				$new_filename = $novosNomes[$i];
			else
				$new_filename = substr($file,strrpos($file,'/') + 1);
			
			$zip->addFile($file,$new_filename);
		 // $zip->addFile($file);
		 $i++;
		}
		$zip->close();

		return $zipname;
		
		///////////////////////////////////////////$this->send_download($arq);
	}
	
	
	/**
	 * Init is called by the base controller before the action method
	 * is called.  This provided an oportunity to hook into the system
	 * for all application actions.  This is a good place for authentication
	 * code.
	 */
	protected function Init()
	{
		// TODO: add app-wide bootsrap code
		
		// EXAMPLE: require authentication to access the app
		/*
		if ( !in_array($this->GetRouter()->GetUri(),array('login','loginform','logout')) )
		{
			require_once("App/ExampleUser.php");
			$this->RequirePermission(ExampleUser::$PERMISSION_ADMIN,'SecureExample.LoginForm');
		}
		//*/
		
		//Dados da configuracao do sistema		
		try {
			$configuracao = $this->Phreezer->Get('Configuracao',1);
			$this->Assign('Configuracao',$configuracao);
			$this->Configuracao = $configuracao;
			
		} catch(Exception $ex){
			$c = new Configuracao($this->Phreezer);
			$this->Assign('Configuracao',$c);
			throw new Exception("O banco de dados do sistema ainda não foi configurado, ou foi configurado incorretamente. Entre em contato com o administrador do servidor ou o desenvolvedor do sistema. Código de erro #0x42CFG");
		}
	}
	
	static public function parseURL($text)
	{ 
	  // replace non letter or digits by -
	  $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

	  // trim
	  $text = trim($text, '-');

	  // transliterate
	  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

	  // lowercase
	  $text = strtolower($text);

	  // remove unwanted characters
	  $text = preg_replace('~[^-\w]+~', '', $text);

	  if (empty($text))
	  {
		return 'n-a';
	  }

	  return $text;
	}

	/**
	 * Returns the number of records to return per page
	 * when pagination is used
	 */
	protected function GetDefaultPageSize()
	{
		return self::$DEFAULT_PAGE_SIZE;
	}

	/**
	 * Returns the name of the JSONP callback function (if allowed)
	 */
	protected function JSONPCallback()
	{
		// TODO: uncomment to allow JSONP
		// return RequestUtil::Get('callback','');

		return '';
	}

	/**
	 * Return the default SimpleObject params used when rendering objects as JSON
	 * @return array
	 */
	protected function SimpleObjectParams()
	{
		return array('camelCase'=>true);
	}

	/**
	 * Helper method to get values from stdClass without throwing errors
	 * @param stdClass $json
	 * @param string $prop
	 * @param string $default
	 */
	protected function SafeGetVal($json, $prop, $default='')
	{
		return (property_exists($json,$prop))
			? $json->$prop
			: $default;
	}

	/**
	 * Helper utility that calls RenderErrorJSON
	 * @param Exception
	 */
	protected function RenderExceptionJSON(Exception $exception)
	{
		$this->RenderErrorJSON($exception->getMessage(),null,$exception);
	}

	/**
	 * Output a Json error message to the browser
	 * @param string $message
	 * @param array key/value pairs where the key is the fieldname and the value is the error
	 */
	protected function RenderErrorJSON($message, $errors = null, $exception = null)
	{
		$err = new stdClass();
		$err->success = false;
		$err->message = $message;
		$err->errors = array();

		if ($errors != null)
		{
			foreach ($errors as $key=>$val)
			{
				$err->errors[lcfirst($key)] = $val;
			}
		}

		if ($exception)
		{
			$err->stackTrace = explode("\n#", substr($exception->getTraceAsString(),1) );
		}

		@header('HTTP/1.1 401 Unauthorized');
		$this->RenderJSON($err,RequestUtil::Get('callback'));
	}

}
?>