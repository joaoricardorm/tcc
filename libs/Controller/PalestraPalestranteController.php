<?php
/** @package    Certificados FAROL::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/PalestraPalestrante.php");

/**
 * PalestraPalestranteController is the controller class for the PalestraPalestrante object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package Certificados FAROL::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class PalestraPalestranteController extends AppBaseController
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
		
		// TODO: if authentiation is required for this entire controller, for example:
		// $this->RequirePermission(ExampleUser::$PERMISSION_USER,'SecureExample.LoginForm');
	}

	/**
	 * Displays a list view of PalestraPalestrante objects
	 */
	public function ListView()
	{
		$this->Render();
	}

	/**
	 * API Method queries for PalestraPalestrante records and render as JSON
	 */
	public function Query()
	{
		try
		{
			$criteria = new PalestraPalestranteCriteria();
			
			
			//Filtra pelo evento caso ele exista na URL (e no arquivo js correspondente a esse controller)
			$palestrante_not_in = RequestUtil::Get('idPalestrante');
			
			if($palestrante_not_in) $criteria->AddFilter(
				new CriteriaFilter('IdPalestrante', $palestrante_not_in)
			);
			
			if(RequestUtil::Get('idPalestrante')){
				$criteria->IdPalestrante_Equals = RequestUtil::Get('idPalestrante');
			}
			
			if(RequestUtil::Get('temCertificado')){
				$criteria->TemCertificado = RequestUtil::Get('temCertificado');
			}
			
			if(RequestUtil::Get('orderByNomePalestrante')){
				$criteria->OrderByNomePalestrante = true;
			}
			
			// TODO: this will limit results based on all properties included in the filter list 
			$filter = RequestUtil::Get('filter');
			if ($filter) $criteria->AddFilter(
				new CriteriaFilter('Id,IdPalestrante,IdPalestra,IdCertificado'
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

				$palestrapalestrantes = $this->Phreezer->Query('PalestraPalestranteReporter',$criteria)->GetDataPage($page, $pagesize);
				$output->rows = $palestrapalestrantes->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = $palestrapalestrantes->TotalResults;
				$output->totalPages = $palestrapalestrantes->TotalPages;
				$output->pageSize = $palestrapalestrantes->PageSize;
				$output->currentPage = $palestrapalestrantes->CurrentPage;
			}
			else
			{
				// return all results
				$palestrapalestrantes = $this->Phreezer->Query('PalestraPalestranteReporter',$criteria);
				$output->rows = $palestrapalestrantes->ToObjectArray(true, $this->SimpleObjectParams());
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
	 * API Method retrieves a single PalestraPalestrante record and render as JSON
	 */
	public function Read()
	{
		try
		{
			$pk = $this->GetRouter()->GetUrlParam('id');
			$palestrapalestrante = $this->Phreezer->Get('PalestraPalestrante',$pk);
			$this->RenderJSON($palestrapalestrante, $this->JSONPCallback(), true, $this->SimpleObjectParams());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method inserts a new PalestraPalestrante record and render response as JSON
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

			$palestrapalestrante = new PalestraPalestrante($this->Phreezer);

			// TODO: any fields that should not be inserted by the user should be commented out

			// this is an auto-increment.  uncomment if updating is allowed
			// $palestrapalestrante->Id = $this->SafeGetVal($json, 'id');

			$palestrapalestrante->IdPalestrante = $this->SafeGetVal($json, 'idPalestrante');
			$palestrapalestrante->IdPalestra = $this->SafeGetVal($json, 'idPalestra');
			$palestrapalestrante->IdCertificado = $this->SafeGetVal($json, 'idCertificado');

			$palestrapalestrante->Validate();
			$errors = $palestrapalestrante->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Verifique erros no preenchimento do formulário',$errors);
			}
			else
			{
				$palestrapalestrante->Save();
				$this->RenderJSON($palestrapalestrante, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method updates an existing PalestraPalestrante record and render response as JSON
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

			$pk = $this->GetRouter()->GetUrlParam('id');
			$palestrapalestrante = $this->Phreezer->Get('PalestraPalestrante',$pk);

			// TODO: any fields that should not be updated by the user should be commented out

			// this is a primary key.  uncomment if updating is allowed
			// $palestrapalestrante->Id = $this->SafeGetVal($json, 'id', $palestrapalestrante->Id);

			$palestrapalestrante->IdPalestrante = $this->SafeGetVal($json, 'idPalestrante', $palestrapalestrante->IdPalestrante);
			$palestrapalestrante->IdPalestra = $this->SafeGetVal($json, 'idPalestra', $palestrapalestrante->IdPalestra);
			$palestrapalestrante->IdCertificado = $this->SafeGetVal($json, 'idCertificado', $palestrapalestrante->IdCertificado);

			$palestrapalestrante->Validate();
			$errors = $palestrapalestrante->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Verifique erros no preenchimento do formulário',$errors);
			}
			else
			{
				$palestrapalestrante->Save();
				$this->RenderJSON($palestrapalestrante, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}


		}
		catch (Exception $ex)
		{


			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method deletes an existing PalestraPalestrante record and render response as JSON
	 */
	public function Delete()
	{
		try
		{
						
			// TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

			$pk = $this->GetRouter()->GetUrlParam('id');
			$palestrapalestrante = $this->Phreezer->Get('PalestraPalestrante',$pk);

			$palestrapalestrante->Delete();

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
