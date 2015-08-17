<?php
/** @package    Certificados FAROL::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/Participante.php");

/**
 * ParticipanteController is the controller class for the Participante object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package Certificados FAROL::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class ParticipanteController extends AppBaseController
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
	 * Displays a list view of Participante objects
	 */
	public function ListView()
	{
		$this->Render();
	}

	/**
	 * API Method queries for Participante records and render as JSON
	 */
	public function Query()
	{
		try
		{
			$criteria = new ParticipanteCriteria();
			
			// TODO: this will limit results based on all properties included in the filter list 
			$filter = RequestUtil::Get('filter');
			if ($filter) $criteria->AddFilter(
				new CriteriaFilter('IdParticipante,Nome,Email,Cpf'
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

				$participantes = $this->Phreezer->Query('Participante',$criteria)->GetDataPage($page, $pagesize);
				$output->rows = $participantes->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = $participantes->TotalResults;
				$output->totalPages = $participantes->TotalPages;
				$output->pageSize = $participantes->PageSize;
				$output->currentPage = $participantes->CurrentPage;
			}
			else
			{
				// return all results
				$participantes = $this->Phreezer->Query('Participante',$criteria);
				$output->rows = $participantes->ToObjectArray(true, $this->SimpleObjectParams());
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
	 * API Method retrieves a single Participante record and render as JSON
	 */
	public function Read()
	{
		try
		{
			$pk = $this->GetRouter()->GetUrlParam('idParticipante');
			$participante = $this->Phreezer->Get('Participante',$pk);
			$this->RenderJSON($participante, $this->JSONPCallback(), true, $this->SimpleObjectParams());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method inserts a new Participante record and render response as JSON
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

			$participante = new Participante($this->Phreezer);

			// TODO: any fields that should not be inserted by the user should be commented out

			// this is an auto-increment.  uncomment if updating is allowed
			// $participante->IdParticipante = $this->SafeGetVal($json, 'idParticipante');

			$participante->Nome = $this->SafeGetVal($json, 'nome');
			$participante->Email = $this->SafeGetVal($json, 'email');
			$participante->Cpf = $this->SafeGetVal($json, 'cpf');

			$participante->Validate();
			$errors = $participante->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$participante->Save();
				$this->RenderJSON($participante, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method updates an existing Participante record and render response as JSON
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

			$pk = $this->GetRouter()->GetUrlParam('idParticipante');
			$participante = $this->Phreezer->Get('Participante',$pk);

			// TODO: any fields that should not be updated by the user should be commented out

			// this is a primary key.  uncomment if updating is allowed
			// $participante->IdParticipante = $this->SafeGetVal($json, 'idParticipante', $participante->IdParticipante);

			$participante->Nome = $this->SafeGetVal($json, 'nome', $participante->Nome);
			$participante->Email = $this->SafeGetVal($json, 'email', $participante->Email);
			$participante->Cpf = $this->SafeGetVal($json, 'cpf', $participante->Cpf);

			$participante->Validate();
			$errors = $participante->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$participante->Save();
				$this->RenderJSON($participante, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}


		}
		catch (Exception $ex)
		{


			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method deletes an existing Participante record and render response as JSON
	 */
	public function Delete()
	{
		try
		{
						
			// TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

			$pk = $this->GetRouter()->GetUrlParam('idParticipante');
			$participante = $this->Phreezer->Get('Participante',$pk);

			$participante->Delete();

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
