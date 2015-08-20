<?php
/** @package    Certificados FAROL::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/Certificado.php");

/**
 * CertificadoController is the controller class for the Certificado object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package Certificados FAROL::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class CertificadoController extends AppBaseController
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

		// Requer permissão de acesso
		$this->RequirePermission(Usuario::$P_ADMIN,
				'Usuario.LoginForm',
				'Autentique-se para acessar esta página',
				'Você não possui permissão para acessar essa página ou sua sessão expirou');
		
		//$this->RequerPermissao(Usuario::$PERMISSION_USER,'Usuario.LoginForm');
	}

	/**
	 * Displays a list view of Certificado objects
	 */
	public function ListView()
	{
		//$usuario = Controller::GetCurrentUser();
		//$this->Assign('usuario',$usuario);
		$this->Render();
	}

	/**
	 * API Method queries for Certificado records and render as JSON
	 */
	public function Query()
	{
		try
		{
			$criteria = new CertificadoCriteria();
			
			// TODO: this will limit results based on all properties included in the filter list 
			$filter = RequestUtil::Get('filter');
			if ($filter) $criteria->AddFilter(
				new CriteriaFilter('IdCertificado,DataEmissao,Livro,Folha,Codigo,IdUsuario'
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

				$certificados = $this->Phreezer->Query('Certificado',$criteria)->GetDataPage($page, $pagesize);
				$output->rows = $certificados->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = $certificados->TotalResults;
				$output->totalPages = $certificados->TotalPages;
				$output->pageSize = $certificados->PageSize;
				$output->currentPage = $certificados->CurrentPage;
			}
			else
			{
				// return all results
				$certificados = $this->Phreezer->Query('Certificado',$criteria);
				$output->rows = $certificados->ToObjectArray(true, $this->SimpleObjectParams());
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
	 * API Method retrieves a single Certificado record and render as JSON
	 */
	public function Read()
	{
		try
		{
			$pk = $this->GetRouter()->GetUrlParam('idCertificado');
			$certificado = $this->Phreezer->Get('Certificado',$pk);
			$this->RenderJSON($certificado, $this->JSONPCallback(), true, $this->SimpleObjectParams());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method inserts a new Certificado record and render response as JSON
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

			$certificado = new Certificado($this->Phreezer);

			// TODO: any fields that should not be inserted by the user should be commented out

			// this is an auto-increment.  uncomment if updating is allowed
			// $certificado->IdCertificado = $this->SafeGetVal($json, 'idCertificado');

			$certificado->DataEmissao = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'dataEmissao')));
			$certificado->Livro = $this->SafeGetVal($json, 'livro');
			$certificado->Folha = $this->SafeGetVal($json, 'folha');
			$certificado->Codigo = $this->SafeGetVal($json, 'codigo');
			$certificado->IdUsuario = $this->SafeGetVal($json, 'idUsuario');

			$certificado->Validate();
			$errors = $certificado->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Verifique erros no preenchimento do formulário',$errors);
			}
			else
			{
				$certificado->Save();
				$this->RenderJSON($certificado, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method updates an existing Certificado record and render response as JSON
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

			$pk = $this->GetRouter()->GetUrlParam('idCertificado');
			$certificado = $this->Phreezer->Get('Certificado',$pk);

			// TODO: any fields that should not be updated by the user should be commented out

			// this is a primary key.  uncomment if updating is allowed
			// $certificado->IdCertificado = $this->SafeGetVal($json, 'idCertificado', $certificado->IdCertificado);

			$certificado->DataEmissao = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'dataEmissao', $certificado->DataEmissao)));
			$certificado->Livro = $this->SafeGetVal($json, 'livro', $certificado->Livro);
			$certificado->Folha = $this->SafeGetVal($json, 'folha', $certificado->Folha);
			$certificado->Codigo = $this->SafeGetVal($json, 'codigo', $certificado->Codigo);
			$certificado->IdUsuario = $this->SafeGetVal($json, 'idUsuario', $certificado->IdUsuario);

			$certificado->Validate();
			$errors = $certificado->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Verifique erros no preenchimento do formulário',$errors);
			}
			else
			{
				$certificado->Save();
				$this->RenderJSON($certificado, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}


		}
		catch (Exception $ex)
		{


			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method deletes an existing Certificado record and render response as JSON
	 */
	public function Delete()
	{
		try
		{
						
			// TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

			$pk = $this->GetRouter()->GetUrlParam('idCertificado');
			$certificado = $this->Phreezer->Get('Certificado',$pk);

			$certificado->Delete();

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
