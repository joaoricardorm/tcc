<?php
/** @package    Certificados FAROL::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/PalestraParticipante.php");

/**
 * PalestraParticipanteController is the controller class for the PalestraParticipante object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package Certificados FAROL::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class PalestraParticipanteController extends AppBaseController
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
	 * Displays a list view of PalestraParticipante objects
	 */
	public function ListView()
	{
		$this->Render();
	}

	/**
	 * API Method queries for PalestraParticipante records and render as JSON
	 */
	public function Query()
	{
		try
		{
			$criteria = new PalestraParticipanteCriteria();
			
			
			if(RequestUtil::Get('idParticipante')){
				$criteria->IdParticipante_Equals = RequestUtil::Get('idPalestrante');
			}
			
			if(RequestUtil::Get('temCertificado')){
				$criteria->TemCertificado = RequestUtil::Get('temCertificado');
			}
			
			if(RequestUtil::Get('naoTemCertificado')){
				$criteria->NaoTemCertificado = RequestUtil::Get('naoTemCertificado');
			}
			
			
			// TODO: this will limit results based on all properties included in the filter list 
			$filter = RequestUtil::Get('filter');
			if ($filter) $criteria->AddFilter(
				new CriteriaFilter('Id,Presenca,IdParticipante,IdPalestra,IdCertificado'
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

				$palestraparticipantes = $this->Phreezer->Query('PalestraParticipanteReporter',$criteria)->GetDataPage($page, $pagesize);
				$output->rows = $palestraparticipantes->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = $palestraparticipantes->TotalResults;
				$output->totalPages = $palestraparticipantes->TotalPages;
				$output->pageSize = $palestraparticipantes->PageSize;
				$output->currentPage = $palestraparticipantes->CurrentPage;
			}
			else
			{
				// return all results
				$palestraparticipantes = $this->Phreezer->Query('PalestraParticipanteReporter',$criteria);
				$output->rows = $palestraparticipantes->ToObjectArray(true, $this->SimpleObjectParams());
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
	 * API Method retrieves a single PalestraParticipante record and render as JSON
	 */
	public function Read()
	{
		try
		{
			$pk = $this->GetRouter()->GetUrlParam('id');
			$palestraparticipante = $this->Phreezer->Get('PalestraParticipante',$pk);
			$this->RenderJSON($palestraparticipante, $this->JSONPCallback(), true, $this->SimpleObjectParams());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method inserts a new PalestraParticipante record and render response as JSON
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

			$palestraparticipante = new PalestraParticipante($this->Phreezer);

			// TODO: any fields that should not be inserted by the user should be commented out

			// this is an auto-increment.  uncomment if updating is allowed
			// $palestraparticipante->Id = $this->SafeGetVal($json, 'id');

			$palestraparticipante->Presenca = $this->SafeGetVal($json, 'presenca');
			$palestraparticipante->IdParticipante = $this->SafeGetVal($json, 'idParticipante');
			$palestraparticipante->IdPalestra = $this->SafeGetVal($json, 'idPalestra');
			$palestraparticipante->IdCertificado = $this->SafeGetVal($json, 'idCertificado');

			$palestraparticipante->Validate();
			$errors = $palestraparticipante->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Verifique erros no preenchimento do formulário',$errors);
			}
			else
			{
				$palestraparticipante->Save(false, true); //$force_insert = false, $ignore_duplicate = false
				$this->RenderJSON($palestraparticipante, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method updates an existing PalestraParticipante record and render response as JSON
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
			$palestraparticipante = $this->Phreezer->Get('PalestraParticipante',$pk);

			// TODO: any fields that should not be updated by the user should be commented out

			// this is a primary key.  uncomment if updating is allowed
			// $palestraparticipante->Id = $this->SafeGetVal($json, 'id', $palestraparticipante->Id);

			$palestraparticipante->Presenca = $this->SafeGetVal($json, 'presenca', $palestraparticipante->Presenca);
			$palestraparticipante->IdParticipante = $this->SafeGetVal($json, 'idParticipante', $palestraparticipante->IdParticipante);
			$palestraparticipante->IdPalestra = $this->SafeGetVal($json, 'idPalestra', $palestraparticipante->IdPalestra);
			$palestraparticipante->IdCertificado = $this->SafeGetVal($json, 'idCertificado', $palestraparticipante->IdCertificado);

			$palestraparticipante->Validate();
			$errors = $palestraparticipante->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Verifique erros no preenchimento do formulário',$errors);
			}
			else
			{
				$palestraparticipante->Save(false, true); //$force_insert = false, $ignore_duplicate = false
				$this->RenderJSON($palestraparticipante, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}


		}
		catch (Exception $ex)
		{


			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method deletes an existing PalestraParticipante record and render response as JSON
	 */
	public function Delete()
	{
		try
		{
						
			// TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

			$pk = $this->GetRouter()->GetUrlParam('id');
			$palestraparticipante = $this->Phreezer->Get('PalestraParticipante',$pk);

			$palestraparticipante->Delete();

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
