<?php
/** @package Tcc::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/Upload.php");

/**
 * DefaultController is the entry point to the application
 *
 * @package Tcc::Controller
 * @author ClassBuilder
 * @version 1.0
 */

class UploadController extends AppBaseController
{

	/**
	 * API Method updates an existing Configuracao record and render response as JSON
	 */
	public function UpdateConfiguracao($img)
	{
		
	}

	/**
	 * Override here for any controller-specific functionality
	 */
	protected function Init()
	{
		parent::Init();
		
		 //$this->UpdateConfiguracao();
		
		// TODO: add controller-wide bootstrap code
		
		// TODO: if authentiation is required for this entire controller, for example:
		// $this->RequirePermission(ExampleUser::$PERMISSION_USER,'SecureExample.LoginForm');
		
		// Requer permissao de acesso
		$this->RequirePermission(Usuario::$P_ADMIN,
				'SecureExample.LoginForm',
				'Autentique-se para acessar esta página',
				'Você não possui permissão para acessar essa página ou sua sessão expirou');
		
	}
	
	public function UploadImagem(){
		 try {
			 $img = new Upload();
			 
			 $img->campo = 'img';
			 $img->pasta = 'images/uploads/logos/';
			 
			 $arquivo = $img->FileUploadCropit();
			 
			 $img->Validate();
			 $errors = $img->GetValidationErrors();
		
			if (count($errors) > 0) {
				//Erro
				$result['status'] = 'error';
				$result['errors'] = $errors;
			} else {
				//Sucesso
				$result['status'] = 'success';
				$result['message'] = $arquivo;	
				
				//Salva imagem no banco
				// try	{
					// require_once("Model/Configuracao.php");
						
					// $configuracao = $this->Phreezer->Get('Configuracao',1);
					// $configuracao->ImagemLogo = $result['message'];
					
					// $configuracao->Validate();
					// $errors = $configuracao->GetValidationErrors();
					
					// if (count($errors) > 0)
					// {
						// Exception ao selecionar a configuracao
						// $result['status'] = 'error';
						// $result['errors'] = array('Formato de imagem inválido para o banco de dados. ' => $ex->getMessage());
					// }
					// else
					// {
						// $configuracao->Save();
						// $this->RenderJSON($configuracao, $this->JSONPCallback(), true, $this->SimpleObjectParams());
					// }
				// } catch (Exception $ex) {
					// Exception ao selecionar a configuracao
					// $result['status'] = 'error';
					// $result['errors'] = array('Exceção ' => $ex->getMessage());
				// }
				
			}
		} catch (Exception $ex){
			//Exception
			$result['status'] = 'error';
			$result['errors'] = array('Exceção ' => $ex->getMessage());
		}
		
		echo json_encode($result);
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