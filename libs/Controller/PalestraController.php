<?php
/** @package    Certificados FAROL::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/Palestra.php");

/**
 * PalestraController is the controller class for the Palestra object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package Certificados FAROL::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class PalestraController extends AppBaseController
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
	 * Displays a list view of Palestra objects
	 */
	public function ListView()
	{
		$this->Render();
	}

	/**
	 * API Method queries for Palestra records and render as JSON
	 */
	public function Query()
	{
		try
		{
			$criteria = new PalestraCriteria();
			
			// TODO: this will limit results based on all properties included in the filter list 
			$filter = RequestUtil::Get('filter');
			if ($filter) $criteria->AddFilter(
				new CriteriaFilter('IdPalestra,Nome,Data,CargaHoraria,ProprioEvento,IdEvento,IdModeloCertificado'
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

				$palestras = $this->Phreezer->Query('Palestra',$criteria)->GetDataPage($page, $pagesize);
				$output->rows = $palestras->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = $palestras->TotalResults;
				$output->totalPages = $palestras->TotalPages;
				$output->pageSize = $palestras->PageSize;
				$output->currentPage = $palestras->CurrentPage;
			}
			else
			{
				// return all results
				$palestras = $this->Phreezer->Query('Palestra',$criteria);
				$output->rows = $palestras->ToObjectArray(true, $this->SimpleObjectParams());
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
	 * API Method retrieves a single Palestra record and render as JSON
	 */
	public function Read()
	{
		try
		{
			$pk = $this->GetRouter()->GetUrlParam('idPalestra');
			$palestra = $this->Phreezer->Get('Palestra',$pk);
			$this->RenderJSON($palestra, $this->JSONPCallback(), true, $this->SimpleObjectParams());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method inserts a new Palestra record and render response as JSON
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

			$palestra = new Palestra($this->Phreezer);

			// TODO: any fields that should not be inserted by the user should be commented out

			// this is an auto-increment.  uncomment if updating is allowed
			// $palestra->IdPalestra = $this->SafeGetVal($json, 'idPalestra');

			$palestra->Nome = $this->SafeGetVal($json, 'nome');
			$palestra->Data = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'data')));
			$palestra->CargaHoraria = date('H:i:s',strtotime('1970-01-01 ' . $this->SafeGetVal($json, 'cargaHoraria')));
			$palestra->ProprioEvento = $this->SafeGetVal($json, 'proprioEvento');
			$palestra->IdEvento = $this->SafeGetVal($json, 'idEvento');
			$palestra->IdModeloCertificado = $this->SafeGetVal($json, 'idModeloCertificado');

			$palestra->Validate();
			$errors = $palestra->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$palestra->Save();
				$this->RenderJSON($palestra, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method updates an existing Palestra record and render response as JSON
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

			$pk = $this->GetRouter()->GetUrlParam('idPalestra');
			$palestra = $this->Phreezer->Get('Palestra',$pk);

			// TODO: any fields that should not be updated by the user should be commented out

			// this is a primary key.  uncomment if updating is allowed
			// $palestra->IdPalestra = $this->SafeGetVal($json, 'idPalestra', $palestra->IdPalestra);

			$palestra->Nome = $this->SafeGetVal($json, 'nome', $palestra->Nome);
			$palestra->Data = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'data', $palestra->Data)));
			$palestra->CargaHoraria = date('Y-m-d H:i:s',strtotime('1970-01-01 ' . $this->SafeGetVal($json, 'cargaHoraria', $palestra->CargaHoraria)));
			$palestra->ProprioEvento = $this->SafeGetVal($json, 'proprioEvento', $palestra->ProprioEvento);
			$palestra->IdEvento = $this->SafeGetVal($json, 'idEvento', $palestra->IdEvento);
			$palestra->IdModeloCertificado = $this->SafeGetVal($json, 'idModeloCertificado', $palestra->IdModeloCertificado);

			$palestra->Validate();
			$errors = $palestra->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$palestra->Save();
				$this->RenderJSON($palestra, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}


		}
		catch (Exception $ex)
		{


			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method deletes an existing Palestra record and render response as JSON
	 */
	public function Delete()
	{
		try
		{
						
			// TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

			$pk = $this->GetRouter()->GetUrlParam('idPalestra');
			$palestra = $this->Phreezer->Get('Palestra',$pk);

			$palestra->Delete();

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
