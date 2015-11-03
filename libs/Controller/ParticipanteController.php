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
	 * API Method queries for Participante records and render as JSON
	 */
	public function Query()
	{
		try
		{
			$criteria = new ParticipanteCriteria();
			
			// FILTRA OS PALESTRANTES PELA PALESTRA SE EXISTIR TAL DADO NA URL
			$arquivoReporter = 'Participante';
			
			if(RequestUtil::Get('idPalestra')){
				$criteria->IdPalestra_Equals = RequestUtil::Get('idPalestra');
				$arquivoReporter .= 'Reporter';
			}
			
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

				$participantes = $this->Phreezer->Query($arquivoReporter,$criteria)->GetDataPage($page, $pagesize);
				$output->rows = $participantes->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = $participantes->TotalResults;
				$output->totalPages = $participantes->TotalPages;
				$output->pageSize = $participantes->PageSize;
				$output->currentPage = $participantes->CurrentPage;
			}
			else
			{
				// return all results
				$participantes = $this->Phreezer->Query($arquivoReporter,$criteria);
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
				$this->RenderErrorJSON('Verifique erros no preenchimento do formulário',$errors);
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
				$this->RenderErrorJSON('Verifique erros no preenchimento do formulário',$errors);
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

			//Verifica se existem certificados para o participante, senao existerem, permite a exclusao
			require_once("Model/PalestraParticipante.php");
			$criteria = new PalestraParticipanteCriteria();
	
			$criteria->IdParticipante_Equals = $pk;
			$criteria->IdCertificado_NotEquals = 0;
			
			try {
				$palestraParticipante = $this->Phreezer->GetByCriteria("PalestraParticipante", $criteria);	
				
				throw new Exception('Não é possível esse participante do sistema, pois ele já possui certificado por alguma palestra');	
			} catch(NotFoundException $nfex){
				$participante->Delete();
			}

			$output = new stdClass();

			$this->RenderJSON($output, $this->JSONPCallback());

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}
	
	
	
	
	
	
	
	
	/**
	 * API Method para inserir um array de participantes vindos da handsontable
	 */
	public function UpdateAll()
	{
		
		//$someJSON = $_GET['dados']; 
		
		require_once("Model/PalestraParticipante.php");
		
		try
		{
						
			$json = json_decode(RequestUtil::GetBody());
			
			//FILTRA OBJETOS DUPLICADOS NA TABELA DE PARTICIPANTES HANDSONTABLE
			$unique = array(); 
			foreach ($json->data as $object) { 
				if (isset($unique[$object->idParticipante])) { 
					continue; 
				} 
				$unique[$object->idParticipante] = $object; 
			}  

			//$json = json_decode($someJSON);

			if (!$json)
			{
				throw new Exception('The request body does not contain valid JSON');
			}

			$errors_p = array();
			
			$row = 0;
			foreach ($unique as $json){ //trocar $unique por $json->data se nao for usa-lo
				
				if($json->idParticipante == '' && $json->nome == '' && $json->email == '' && $json->nome == ''){
				  //se linha estiver em branco
				} else {
			
					$pk = $this->SafeGetVal($json, 'idParticipante', null);
						
					if($pk != ''){
						
						//SE TIVER ID É EDIÇÃOO SENÃO CRIA UM NOVO PARTICIPANTE
						try {
							$participante = $this->Phreezer->Get('Participante',$pk);								
						} catch (NotFoundException $ex){ 
							throw new Exception('Participante não encontrado');
						}
					
					} else {
						$participante = new Participante($this->Phreezer);
					}

					//se existir id, mas tudo estiver em branco o sistema exclui do banco
					if($json->idParticipante != '' && $json->nome == '' && $json->email == '' && $json->nome == ''){
					  $participante->Delete();
					} else {

					$participante->Nome = $this->SafeGetVal($json, 'nome', $participante->Nome);
					$participante->Email = $this->SafeGetVal($json, 'email', $participante->Email);
					$participante->Cpf = $this->SafeGetVal($json, 'cpf', $participante->Cpf);
					
					$participante->Validate();
					$errors = $participante->GetValidationErrors();
					
					//if(!$participante->Cpf) echo $participante->Nome .'--' . $participante->Cpf.'mmmm';
					
					if (count($errors) > 0){
						$errors_p[$participante->IdParticipante]['message'] = $participante->GetValidationErrors();								
						$errors_p[$participante->IdParticipante]['success'] = false;
						$errors_p[$participante->IdParticipante]['row'] = $row;
					} else {
						
							$participante->Save();
							
							//para fazer a associaçãoo na tabela palestra_participante
							$table2 = new PalestraParticipante($this->Phreezer);
							
							$table2->IdParticipante = $participante->IdParticipante;
							$table2->IdPalestra = RequestUtil::Get('idPalestra');
							$table2->Presenca = 0;
							$table2->IdCertificado = 0;
							$table2->Save(false, true);
							
							
							if($pk == ''){
								$dados = array( 'idParticipante' => $participante->IdParticipante, 'row' => $row);
								$sucesso['novo'][] = $dados;
							}
						
					}

					// if (count($errors) > 0)
					// {
						// $this->RenderErrorJSON('Verifique erros no preenchimento do formulario',$errors);
					// }
					// else
					// {
						// $participante->Save();
						// $this->RenderJSON($participante, $this->JSONPCallback(), true, $this->SimpleObjectParams());
					// }
					
					} //fim do cadastro se nao for exclusao
			
				} //fim do cadastro caso nada esteja em branco
				
				$row++;
			}
			
			if (count($errors_p) > 0)
			{
				$this->RenderErrorJSON('Verifique erros no preenchimento do formulário',$errors_p);
			} else {
				$sucesso['success'] = true;
				$sucesso['message'] = 'Participantes salvos com sucesso';
				$this->RenderJSON($sucesso);
			}
			
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}
	
}

?>
