<?php
/** @package    Certificados FAROL::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/ModeloCertificado.php");

/**
 * ModeloCertificadoController is the controller class for the ModeloCertificado object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package Certificados FAROL::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class ModeloCertificadoController extends AppBaseController
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
	 * Displays a list view of ModeloCertificado objects
	 */
	public function ListView()
	{
		$this->Render();
	}

	/**
	 * API Method queries for ModeloCertificado records and render as JSON
	 */
	public function Query()
	{
		try
		{
			$criteria = new ModeloCertificadoCriteria();
			
			// TODO: this will limit results based on all properties included in the filter list 
			$filter = RequestUtil::Get('filter');
			if ($filter) $criteria->AddFilter(
				new CriteriaFilter('IdModeloCertificado,Nome,TextoParticipante,TextoPalestrante,ArquivoCss,Elementos'
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

				$modelocertificados = $this->Phreezer->Query('ModeloCertificado',$criteria)->GetDataPage($page, $pagesize);
				$output->rows = $modelocertificados->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = $modelocertificados->TotalResults;
				$output->totalPages = $modelocertificados->TotalPages;
				$output->pageSize = $modelocertificados->PageSize;
				$output->currentPage = $modelocertificados->CurrentPage;
			}
			else
			{
				// return all results
				$modelocertificados = $this->Phreezer->Query('ModeloCertificado',$criteria);
				$output->rows = $modelocertificados->ToObjectArray(true, $this->SimpleObjectParams());
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
	 * API Method retrieves a single ModeloCertificado record and render as JSON
	 */
	public function Read()
	{
		try
		{
			$pk = $this->GetRouter()->GetUrlParam('idModeloCertificado');
			$modelocertificado = $this->Phreezer->Get('ModeloCertificado',$pk);
			$this->RenderJSON($modelocertificado, $this->JSONPCallback(), true, $this->SimpleObjectParams());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method inserts a new ModeloCertificado record and render response as JSON
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

			$modelocertificado = new ModeloCertificado($this->Phreezer);

			// TODO: any fields that should not be inserted by the user should be commented out

			// this is an auto-increment.  uncomment if updating is allowed
			// $modelocertificado->IdModeloCertificado = $this->SafeGetVal($json, 'idModeloCertificado');

			$modelocertificado->Nome = $this->SafeGetVal($json, 'nome');
			$modelocertificado->TextoParticipante = $this->SafeGetVal($json, 'textoParticipante');
			$modelocertificado->TextoPalestrante = $this->SafeGetVal($json, 'textoPalestrante');
			$modelocertificado->ArquivoCss = $this->SafeGetVal($json, 'arquivoCss');
			$modelocertificado->Elementos = $this->SafeGetVal($json, 'elementos');

			$modelocertificado->Validate();
			$errors = $modelocertificado->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Verifique erros no preenchimento do formulário',$errors);
			}
			else
			{
				$modelocertificado->Save();
				$this->RenderJSON($modelocertificado, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method updates an existing ModeloCertificado record and render response as JSON
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

			$pk = $this->GetRouter()->GetUrlParam('idModeloCertificado');
			$modelocertificado = $this->Phreezer->Get('ModeloCertificado',$pk);

			// TODO: any fields that should not be updated by the user should be commented out

			// this is a primary key.  uncomment if updating is allowed
			// $modelocertificado->IdModeloCertificado = $this->SafeGetVal($json, 'idModeloCertificado', $modelocertificado->IdModeloCertificado);

			$modelocertificado->Nome = $this->SafeGetVal($json, 'nome', $modelocertificado->Nome);
			$modelocertificado->TextoParticipante = $this->SafeGetVal($json, 'textoParticipante', $modelocertificado->TextoParticipante);
			$modelocertificado->TextoPalestrante = $this->SafeGetVal($json, 'textoPalestrante', $modelocertificado->TextoPalestrante);
			$modelocertificado->ArquivoCss = $this->SafeGetVal($json, 'arquivoCss', $modelocertificado->ArquivoCss);
			$modelocertificado->Elementos = $this->SafeGetVal($json, 'elementos', $modelocertificado->Elementos);

			$modelocertificado->Validate();
			$errors = $modelocertificado->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Verifique erros no preenchimento do formulário',$errors);
			}
			else
			{
				$modelocertificado->Save();
				$this->RenderJSON($modelocertificado, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}


		}
		catch (Exception $ex)
		{


			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method deletes an existing ModeloCertificado record and render response as JSON
	 */
	public function Delete()
	{
		try
		{
						
			// TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

			$pk = $this->GetRouter()->GetUrlParam('idModeloCertificado');
			$modelocertificado = $this->Phreezer->Get('ModeloCertificado',$pk);
			
			if($modelocertificado->IdModeloCertificado == 1){
				throw new Exception('O modelo padrÃ£o nÃ£o pode ser excluido.');
			} else {	
				$modelocertificado->Delete();
			}
			
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
