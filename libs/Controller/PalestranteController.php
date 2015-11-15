<?php
/** @package    Certificados FAROL::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/Palestrante.php");

/**
 * PalestranteController is the controller class for the Palestrante object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package Certificados FAROL::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class PalestranteController extends AppBaseController
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
	 * Displays a list view of Palestrante objects
	 */
	public function ListView()
	{				 
	
		//Dados da palestra
		$this->Assign('Palestra',null);
		
		$pk = $this->GetRouter()->GetUrlParam('idPalestra');
		
		if($pk){
			try {
				$palestra = $this->Phreezer->Get('Palestra',$pk);
				$this->Assign('Palestra',$palestra);
				
				$pkEvento = $palestra->IdEvento;
				
				$evento = $this->Phreezer->Get('Evento',$pkEvento);
				$this->Assign('Evento',$evento);
				
			} catch(NotFoundException $ex){
				throw new NotFoundException("A atividade #$pk não existe");
			}
		
		}
	
		$this->Render();
	}

	/**
	 * API Method queries for Palestrante records and render as JSON
	 */
	public function Query()
	{		
		try
		{
			$criteria = new PalestranteCriteria();			
			
			// FILTRA OS PALESTRANTES PELA PALESTRA SE EXISTIR TAL DADO NA URL
			$arquivoReporter = 'Palestrante';
			if(RequestUtil::Get('palestra')){
				$criteria->IdPalestra_Equals = RequestUtil::Get('palestra');
				$arquivoReporter .= 'Reporter';
			}
			
			if(RequestUtil::Get('outerJoinPalestras')){
				$criteria->OuterJoinPalestras = RequestUtil::Get('outerJoinPalestras');
				$arquivoReporter .= 'Reporter';
			}
			
			if(RequestUtil::Get('ordemLouca')){
				$criteria->OrdemLouca = RequestUtil::Get('ordemLouca');
				$arquivoReporter .= 'Reporter';
			}
			
			// TODO: this will limit results based on all properties included in the filter list 
			$filter = RequestUtil::Get('filter');
			if ($filter) $criteria->AddFilter(
				new CriteriaFilter('IdPalestrante,Nome,Email,Cpf,Cargo,ImagemAssinatura'
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
				
						// book with id of 3 has 3 authors assigned, this will run one query
				 //$palestra = $this->Phreezer->Get('Palestra',85);
				
				// LOOK AT THE SOURCE OF Book->GetAuthorsUsingReporter TO SEE HOW A CUSTOM REPORTER IS USED
				// $palestrantes = $palestra->GetPalestrantesUsingReporter();
				
				$palestrantes = $this->Phreezer->Query('PalestranteReporter',$criteria)->GetDataPage($page, $pagesize);
				$output->rows = $palestrantes->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = $palestrantes->TotalResults;
				$output->totalPages = $palestrantes->TotalPages;
				$output->pageSize = $palestrantes->PageSize;
				$output->currentPage = $palestrantes->CurrentPage;
			}
			else
			{
				// return all results
				$palestrantes = $this->Phreezer->Query('PalestranteReporter',$criteria);
				$output->rows = $palestrantes->ToObjectArray(true, $this->SimpleObjectParams());
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
	 * API Method retrieves a single Palestrante record and render as JSON
	 */
	public function Read()
	{
		try
		{
			$pk = $this->GetRouter()->GetUrlParam('idPalestrante');
			$palestrante = $this->Phreezer->Get('Palestrante',$pk);
			$this->RenderJSON($palestrante, $this->JSONPCallback(), true, $this->SimpleObjectParams());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method inserts a new Palestrante record and render response as JSON
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

			$palestrante = new Palestrante($this->Phreezer);

			// TODO: any fields that should not be inserted by the user should be commented out

			// this is an auto-increment.  uncomment if updating is allowed
			// $palestrante->IdPalestrante = $this->SafeGetVal($json, 'idPalestrante');

			$palestrante->Nome = $this->SafeGetVal($json, 'nome');
			$palestrante->Email = $this->SafeGetVal($json, 'email');
			$palestrante->Cpf = $this->SafeGetVal($json, 'cpf');
			$palestrante->Cargo = $this->SafeGetVal($json, 'cargo');
			$palestrante->ImagemAssinatura = $this->SafeGetVal($json, 'imagemAssinatura');

			$palestrante->Validate();
			$errors = $palestrante->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Verifique erros no preenchimento do formulário',$errors);
			}
			else
			{
				$palestrante->Save();
				$this->RenderJSON($palestrante, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method updates an existing Palestrante record and render response as JSON
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

			$pk = $this->GetRouter()->GetUrlParam('idPalestrante');
			$palestrante = $this->Phreezer->Get('Palestrante',$pk);

			// TODO: any fields that should not be updated by the user should be commented out

			// this is a primary key.  uncomment if updating is allowed
			// $palestrante->IdPalestrante = $this->SafeGetVal($json, 'idPalestrante', $palestrante->IdPalestrante);

			$palestrante->Nome = $this->SafeGetVal($json, 'nome', $palestrante->Nome);
			$palestrante->Email = $this->SafeGetVal($json, 'email', $palestrante->Email);
			$palestrante->Cpf = $this->SafeGetVal($json, 'cpf', $palestrante->Cpf);
			$palestrante->Cargo = $this->SafeGetVal($json, 'cargo', $palestrante->Cargo);
			$palestrante->ImagemAssinatura = $this->SafeGetVal($json, 'imagemAssinatura', $palestrante->ImagemAssinatura);

			$palestrante->Validate();
			$errors = $palestrante->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Verifique erros no preenchimento do formulário',$errors);
			}
			else
			{
				$palestrante->Save();
				$this->RenderJSON($palestrante, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}


		}
		catch (Exception $ex)
		{


			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method deletes an existing Palestrante record and render response as JSON
	 */
	public function Delete()
	{
		try
		{
						
			// TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

			$pk = $this->GetRouter()->GetUrlParam('idPalestrante');
			$palestrante = $this->Phreezer->Get('Palestrante',$pk);
			
			//Verifica se existem certificados para o palestrante, senao existerem, permite a exclusao
			require_once("Model/PalestraPalestrante.php");
			$criteria = new PalestraPalestranteCriteria();
	
			$criteria->IdPalestrante_Equals = $pk;
			$criteria->IdCertificado_NotEquals = 1;
	
			try {
				$palestraPalestrante = $this->Phreezer->Query("PalestraPalestrante", $criteria)->ToObjectArray(true,$this->SimpleObjectParams());	;	
				
				throw new Exception('Não é possível esse palestrante do do sistema, pois ele já possui certificado por alguma palestra');
			} catch(NotFoundException $nfex){
				$palestrante->Delete();
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
