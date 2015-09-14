<?php
/** @package    Certificados FAROL::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/Configuracao.php");

/**
 * ConfiguracaoController is the controller class for the Configuracao object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package Certificados FAROL::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class ConfiguracaoController extends AppBaseController
{

	/**
	 * Override here for any controller-specific functionality
	 *
	 * @inheritdocs
	 */
	protected function Init()
	{
		parent::Init();

		// TODO: add controller-wide bootstrap code
		
		// DO SOME CUSTOM AUTHENTICATION FOR THIS PAGE
		// Requer permissão de acesso
		$this->RequirePermission(Usuario::$P_ADMIN,
				'SecureExample.LoginForm', 
				'Autentique-se para acessar esta página',
				'Você não possui permissão para acessar essa página ou sua sessão expirou');
	}

	/**
	 * Displays a list view of Configuracao objects
	 */
	public function SingleView()
	{		
		$this->Render();
	}
	
	/**
	 * Displays a list view of Configuracao objects
	 */
	public function ListView()
	{
		$this->Render();
	}

	/**
	 * API Method queries for Configuracao records and render as JSON
	 */
	public function Query()
	{
		try
		{
			$criteria = new ConfiguracaoCriteria();
			
			// TODO: this will limit results based on all properties included in the filter list 
			$filter = RequestUtil::Get('filter');
			if ($filter) $criteria->AddFilter(
				new CriteriaFilter('IdConfiguracao,NomeInstituicao,ImagemLogo,Cnpj,Telefone'
				, '%'.$filter.'%')
			);

			// TODO: this is generic query filtering based only on criteria properties
			foreach (array_keys($_REQUEST) as $prop)
			{
				$prop_normal = ucfirst($prop);
				$prop_equals = $prop_normal.'_Equals';

				if (property_exists($criteria, $prop_normal))
				{
					$criteria->$prop_normal = RequestUtil::Get($prop);
				}
				elseif (property_exists($criteria, $prop_equals))
				{
					// this is a convenience so that the _Equals suffix is not needed
					$criteria->$prop_equals = RequestUtil::Get($prop);
				}
			}

			$output = new stdClass();

			// if a sort order was specified then specify in the criteria
 			$output->orderBy = RequestUtil::Get('orderBy');
 			$output->orderDesc = RequestUtil::Get('orderDesc') != '';
 			if ($output->orderBy) $criteria->SetOrder($output->orderBy, $output->orderDesc);

			$page = RequestUtil::Get('page');

			if ($page != '')
			{
				// if page is specified, use this instead (at the expense of one extra count query)
				$pagesize = $this->GetDefaultPageSize();

				$configuracoes = $this->Phreezer->Query('Configuracao',$criteria)->GetDataPage($page, $pagesize);
				$output->rows = $configuracoes->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = $configuracoes->TotalResults;
				$output->totalPages = $configuracoes->TotalPages;
				$output->pageSize = $configuracoes->PageSize;
				$output->currentPage = $configuracoes->CurrentPage;
			}
			else
			{
				// return all results
				$configuracoes = $this->Phreezer->Query('Configuracao',$criteria);
				$output->rows = $configuracoes->ToObjectArray(true, $this->SimpleObjectParams());
				$output->totalResults = count($output->rows);
				$output->totalPages = 1;
				$output->pageSize = $output->totalResults;
				$output->currentPage = 1;
			}


			$this->RenderJSON($output, $this->JSONPCallback());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method retrieves a single Configuracao record and render as JSON
	 */
	public function Read()
	{
		try
		{
			$pk = $this->GetRouter()->GetUrlParam('idConfiguracao');
			$configuracao = $this->Phreezer->Get('Configuracao',$pk);
			$this->RenderJSON($configuracao, $this->JSONPCallback(), true, $this->SimpleObjectParams());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method inserts a new Configuracao record and render response as JSON
	 */
	public function Create()
	{
		try
		{
						
			$json = json_decode(RequestUtil::GetBody());

			if (!$json)
			{
				throw new Exception('The request body does not contain valid JSON');
			}

			$configuracao = new Configuracao($this->Phreezer);

			// TODO: any fields that should not be inserted by the user should be commented out

			// this is an auto-increment.  uncomment if updating is allowed
			// $configuracao->IdConfiguracao = $this->SafeGetVal($json, 'idConfiguracao');

			$configuracao->NomeInstituicao = $this->SafeGetVal($json, 'nomeInstituicao');
			$configuracao->ImagemLogo = $this->SafeGetVal($json, 'imagemLogo');
			$configuracao->Cnpj = $this->SafeGetVal($json, 'cnpj');
			$configuracao->Telefone = $this->SafeGetVal($json, 'telefone');

			$configuracao->Validate();
			$errors = $configuracao->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Verifique erros no preenchimento do formulário',$errors);
			}
			else
			{
				$configuracao->Save();
				$this->RenderJSON($configuracao, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}
	
	/**
	 * API Method updates an existing Configuracao record and render response as JSON
	 */
	public function Update()
	{
		try
		{
						
			$json = json_decode(RequestUtil::GetBody());

			if (!$json)
			{
				throw new Exception('The request body does not contain valid JSON');
			}

			$pk = $this->GetRouter()->GetUrlParam('idConfiguracao');
			$configuracao = $this->Phreezer->Get('Configuracao',$pk);

			// TODO: any fields that should not be updated by the user should be commented out

			// this is a primary key.  uncomment if updating is allowed
			// $configuracao->IdConfiguracao = $this->SafeGetVal($json, 'idConfiguracao', $configuracao->IdConfiguracao);

			$configuracao->NomeInstituicao = $this->SafeGetVal($json, 'nomeInstituicao', $configuracao->NomeInstituicao);
			$configuracao->ImagemLogo = $this->SafeGetVal($json, 'imagemLogo', $configuracao->ImagemLogo);
			$configuracao->Cnpj = $this->SafeGetVal($json, 'cnpj', $configuracao->Cnpj);
			$configuracao->Telefone = $this->SafeGetVal($json, 'telefone', $configuracao->Telefone);

			$configuracao->Validate();
			$errors = $configuracao->GetValidationErrors();
			

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Verifique erros no preenchimento do formulário',$errors);
			}
			else
			{
				$configuracao->Save();
				$this->RenderJSON($configuracao, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}


		}
		catch (Exception $ex)
		{


			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method deletes an existing Configuracao record and render response as JSON
	 */
	public function Delete()
	{
		try
		{
						
			// TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

			$pk = $this->GetRouter()->GetUrlParam('idConfiguracao');
			$configuracao = $this->Phreezer->Get('Configuracao',$pk);

			$configuracao->Delete();

			$output = new stdClass();

			$this->RenderJSON($output, $this->JSONPCallback());

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}
}

?>
