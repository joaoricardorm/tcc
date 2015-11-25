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
				'SecureExample.LoginForm',
				'Autentique-se para acessar esta página',
				'Você não possui permissão para acessar essa página ou sua sessão expirou');
		
		//$this->RequerPermissao(Usuario::$PERMISSION_USER,'SecureExample.LoginForm');
	}

	
	/**
	 * Displays a list view of Certificado objects
	 */
	public function EmitirCertificadosView()
	{
		//$usuario = Controller::GetCurrentUser();
		//$this->Assign('usuario',$usuario);		
		
		//Dados do evento
		$this->Assign('Palestra',null);
		$this->Assign('Evento',null);
		$this->Assign('navegacao', 'emitir-certificados');
		
		$pk = $this->GetRouter()->GetUrlParam('idPalestra');
		
		if(isset($_GET['idPalestra']))
			$pk = (int)$_GET['idPalestra'];
		
		if($pk){
		
			try {
				$palestra = $this->Phreezer->Get('Palestra',$pk);
				$this->Assign('Palestra',$palestra);
				
				$evento = $this->Phreezer->Get('Evento',$palestra->IdEvento);
				$this->Assign('Evento',$evento);
							
			// require_once('verysimple/Phreeze/ObserveToBrowser.php');
			
			// $ob = new ObserveToBrowser();
			// $this->Phreezer->DataAdapter->AttachObserver($ob);
				
				//RESGATA O ÚLTIMO CERTIFICADO PARA PREENCHER NA PARTE EMITIR CERTIFICADO
				$criteria = new CertificadoCriteria();
				$criteria->SetOrder('Codigo', '1'); //PARA PEGAR PELO ULTIMO CODIGO E NÃO ID
				$criteria->SetLimit(1);
				
				$ultimoElemento = $this->Phreezer->GetByCriteria('CertificadoReporter',$criteria);
				
				if($ultimoElemento->Folha == 0) $ultimoElemento->Livro = 1;
				$ultimoElemento->Folha += 1; 
				$ultimoElemento->Codigo += 1; 
		
				$this->Assign('UltimoElemento',$ultimoElemento);
					
			} catch(NotFoundException $ex){
				throw new NotFoundException("A atividade #$pk não existe".$ex);
			}
		
		} else {
				require_once('Model/Evento.php');
				$criteria = new EventoCriteria();
				$listaEventos = $this->Phreezer->Query('Evento',$criteria)->ToObjectArray(true,$this->SimpleObjectParams());

				$this->Assign('ListaEventos',$listaEventos);
				
				/*$output->rows = $certificados->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = $certificados->TotalResults;
				$output->totalPages = $certificados->TotalPages;
				$output->pageSize = $certificados->PageSize;
				$output->currentPage = $certificados->CurrentPage;*/
		}
		
		$this->Render('EmitirCertificadosView.tpl');
	}
	
	public function EmitirModeloCertificadosView(){
		//Dados do evento
		$this->Assign('Palestra',null);
		$this->Assign('Evento',null);
		$this->Assign('ModeloCertificado',null);
		
		$this->Assign('navegacao', 'emitir-certificados');
		
		$pk = $this->GetRouter()->GetUrlParam('idPalestra');
		
		$certificadoPalestrante = $this->GetRouter()->GetUrlParam('palestrante');
		
		if(isset($_GET['idPalestra']))
			$pk = (int)$_GET['idPalestra'];
		
		if($pk){
		
			try {
				$palestra = $this->Phreezer->Get('Palestra',$pk);
				$this->Assign('Palestra',$palestra);
				
				$evento = $this->Phreezer->Get('Evento',$palestra->IdEvento);
				$this->Assign('Evento',$evento);
				
				$modeloCertificado = $this->Phreezer->Get('ModeloCertificado',$palestra->IdModeloCertificado);
				
				//RETIRA CAMPO PARA ASSINATURA DO PARTICIPANTE SE FOR CERTIFICADO DE PALESTRANTE
				if($certificadoPalestrante){
					$regex = '/(class=\"hide-palestrante)/';
					$modeloCertificado->Elementos = preg_replace($regex, '$1 hide', $modeloCertificado->Elementos);
				}
				
				// echo '<pre>';
				// print_r($modeloCertificado);
				// echo '</pre>';
				
				$this->Assign('ModeloCertificado',$modeloCertificado);
				
			} catch(NotFoundException $ex){
				throw new NotFoundException("A atividade #$pk não existe".$ex);
			}
		}
		
		$this->Render('EmitirModeloCertificadosView.tpl');
	}
	
	
	
	
	
	
	
	public function EmitirModeloCertificadosViewSimples(){
		//Dados do evento
		$this->Assign('Palestra',null);
		$this->Assign('Evento',null);
		$this->Assign('ModeloCertificado',null);
		
		$this->Assign('navegacao', 'emitir-certificados');
		
		$pk = $this->GetRouter()->GetUrlParam('idPalestra');
		
		$certificadoPalestrante = $this->GetRouter()->GetUrlParam('palestrante');
		
		if(isset($_GET['idPalestra']))
			$pk = (int)$_GET['idPalestra'];
		
		if($pk){
		
			try {
				$palestra = $this->Phreezer->Get('Palestra',$pk);
				$this->Assign('Palestra',$palestra);
				
				$evento = $this->Phreezer->Get('Evento',$palestra->IdEvento);
				$this->Assign('Evento',$evento);
				
				$modeloCertificado = $this->Phreezer->Get('ModeloCertificado',$palestra->IdModeloCertificado);
				
				//RETIRA CAMPO PARA ASSINATURA DO PARTICIPANTE SE FOR CERTIFICADO DE PALESTRANTE
				if($certificadoPalestrante){
					$regex = '/(class=\"hide-palestrante)/';
					$modeloCertificado->Elementos = preg_replace($regex, '$1 hide', $modeloCertificado->Elementos);
				}
				
				// echo '<pre>';
				// print_r($modeloCertificado);
				// echo '</pre>';
				
				$this->Assign('ModeloCertificado',$modeloCertificado);
				
			} catch(NotFoundException $ex){
				throw new NotFoundException("A atividade #$pk não existe".$ex);
			}
		}
		
		$this->Render('EmitirModeloCertificadosView.tpl');
	}
	
	
	
	
	
	
	
	
	
	public function GerarCertificadoPalestrante(){		
		
		$idPalestra = $this->GetRouter()->GetUrlParam('idPalestra');
		$idPalestrante = $this->GetRouter()->GetUrlParam('idPalestrante');
		
		$livro = $this->GetRouter()->GetUrlParam('livro');
		$folha = $this->GetRouter()->GetUrlParam('folha');		
		$codigo = $this->GetRouter()->GetUrlParam('codigo');
	
		// VERIFICA SE PALESTRANTE JÁ POSSUI CERTIFICADO
		try {
			require_once('Model/PalestraPalestrante.php');
			$criteria = new PalestraPalestranteCriteria();
			$criteria->IdPalestra_Equals = $idPalestra;
			$criteria->IdPalestrante_Equals = $idPalestrante;
			$palestrapalestrante = $this->Phreezer->GetByCriteria('PalestraPalestrante',$criteria);
			
			//SE AINDA NÃO TIVER ELE CRIA O CERTIFICADO
			if($palestrapalestrante->IdCertificado == 0){				
				$certificado = new Certificado($this->Phreezer);
				$certificado->DataEmissao = date('Y-m-d H:i:s');
				$certificado->Livro = $livro;
				$certificado->Folha = $folha;
				$certificado->Codigo = $codigo;
				$certificado->IdUsuario = $this->GetCurrentUser()->IdUsuario;
				
				
				$certificado->Validate();
				$errors = $certificado->GetValidationErrors();

				if (count($errors) > 0)
				{
					$this->RenderErrorJSON('Verifique erros no preenchimento do formulário',$errors);
				}
				else
				{
					$certificado->Save();
					
					//para fazer a associação do certificado na tabela palestra_palestrante
					$palestrapalestrante->IdCertificado = $certificado->IdCertificado;
					$palestrapalestrante->Save();
					
					$this->RenderJSON('Criou o certificado '.$certificado->IdCertificado.' e associou com palestrante '.$palestrapalestrante->IdPalestrante);

				}
		
				
			} else {
			   //JÁ TEM CEFTIFICADO
			}
				
		} catch(NotFoundException $ex){
			throw new NotFoundException("Erro ao buscar associação do palestrante com a palestra".$ex);
		}						
							
	}
	
	public function GerarCertificadoParticipante(){		
		
		$idPalestra = $this->GetRouter()->GetUrlParam('idPalestra');
		$idParticipante = $this->GetRouter()->GetUrlParam('idParticipante');
		
		$livro = $this->GetRouter()->GetUrlParam('livro');
		$folha = $this->GetRouter()->GetUrlParam('folha');		
		$codigo = $this->GetRouter()->GetUrlParam('codigo');
	
		// VERIFICA SE PARTICIPANTE JÁ POSSUI CERTIFICADO
		try {
			require_once('Model/PalestraParticipante.php');
			$criteria = new PalestraParticipanteCriteria();
			$criteria->IdPalestra_Equals = $idPalestra;
			$criteria->IdParticipante_Equals = $idParticipante;
			$palestraparticipante = $this->Phreezer->GetByCriteria('PalestraParticipante',$criteria);
			
			//SE AINDA NÃO TIVER ELE CRIA O CERTIFICADO
			if($palestraparticipante->IdCertificado == 0){				
				$certificado = new Certificado($this->Phreezer);
				$certificado->DataEmissao = date('Y-m-d H:i:s');
				$certificado->Livro = $livro;
				$certificado->Folha = $folha;
				$certificado->Codigo = $codigo;
				$certificado->IdUsuario = $this->GetCurrentUser()->IdUsuario;
				
				
				$certificado->Validate();
				$errors = $certificado->GetValidationErrors();

				if (count($errors) > 0)
				{
					$this->RenderErrorJSON('Verifique erros no preenchimento do formulário',$errors);
				}
				else
				{
					$certificado->Save();
					
					//para fazer a associação do certificado na tabela palestra_participante
					$palestraparticipante->IdCertificado = $certificado->IdCertificado;
					$palestraparticipante->Save();
					
					$this->RenderJSON('Criou o certificado '.$certificado->IdCertificado.' e associou com participante '.$palestraparticipante->IdParticipante);

				}
		
				
			} else {
			   //JÁ TEM CEFTIFICADO
			}
				
		} catch(NotFoundException $ex){
			throw new NotFoundException("Erro ao buscar associação do participante com a palestra".$ex);
		}						
							
	}
	
	public function GerarAta(){		
		
		$idPalestra = $this->GetRouter()->GetUrlParam('idPalestra');
	
		// VERIFICA SE PALESTRA EXISTE
		try {
			require_once('Model/PalestraPalestrante.php');
				
			require_once('Model/Palestra.php');
			
			$criteria = new PalestraCriteria();
			$criteria->IdPalestra_Equals = $idPalestra;

			$palestra = $this->Phreezer->GetByCriteria('PalestraReporter',$criteria);
			
			//OBTEM TODOS CERTIFICADOS DA PALESTRA
			try {
				require_once('Model/PalestraPalestrante.php');
				$criteria = new PalestraPalestranteCriteria();
				$criteria->IdPalestra_Equals = $idPalestra;
				$criteria->IdCertificado_GreaterThan = 1; //só quem já tem certificado
				$criteria->InnerJoinCertificado = true; //só quem já tem certificado
				$palestrantesCertificados = $this->Phreezer->Query('PalestraPalestranteReporter',$criteria)->ToObjectArray(true,$this->SimpleObjectParams());
				
				require_once('Model/PalestraParticipante.php');
				$criteria = new PalestraParticipanteCriteria();
				$criteria->IdPalestra_Equals = $idPalestra;
				$criteria->IdCertificado_GreaterThan = 1; //só quem já tem certificado
				$criteria->InnerJoinCertificado = true; //só quem já tem certificado
				$participantesCertificados = $this->Phreezer->Query('PalestraParticipanteReporter',$criteria)->ToObjectArray(true,$this->SimpleObjectParams());
				
				//JUNTA PALESTRANTES E PARTICIPANTES
				$registros = array_merge($palestrantesCertificados, $participantesCertificados);
				
				$html = '
				<html>
				<head>
				<meta http-equiv="content-type" content="text/html;charset=utf-8" />
				<meta charset="utf8"/>
				<style>
				
				@page, html { margin: 25mm; }
				.page-break { page-break-after: always; }
				.page-break:last-child { page-break-after: initial; }
				
				body { font-family:Arial, helvetica, sans-serif; }
				
				#tblAta { border-collapse:collapse; max-width:100%; }
				#tblAta th, #tblAta td {	
					vertical-align:top; 
					font-size:8.5px; 
					padding:2px; 
					border:1px #000 solid; 
					border-right-width:0; 
				}
				#tblAta th:last-child, #tblAta td:last-child { border-right-width:1px; }

				</style>
				</head>

				<body>
				
				<!--EXIBE O NÚMERO DA PÁGINA NO TOPO-->
				<script type="text/php">				  
				  if ( isset($pdf) ) { 
					
					$folha = $PAGE_NUM-1+'. $registros[0]->folha .';
					
					$font = Font_Metrics::get_font("sans-serif", "bold");
					$size = 15;
					$color = array(0.5,0.5,0.5);
					$y = 17;
					$x = $pdf->get_width() - 60 - Font_Metrics::get_text_width("1/1", $font, $size);
					//$pdf->page_text($x, $y, "{PAGE_NUM}", $font, $size, $color);
					$pdf->page_text($x, $y, "$folha", $font, $size, $color);
				  } 
				</script>
				
				<table class="page-break" id="tblAta" width="100%" border="0" cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th>Participante</th>
							<th style="padding:2px 5px;">Assinatura</th>
							<th>Título da atividade</th>
							<th>Evento</th>
							<th>Data</th>
							<th>CH</th>
							<th colspan="2">Nº de registro</th>
							<th>Folha</th>
							<th>Livro</th>
						</tr>
					</thead>
					<tbody>
				';
				
				$i=1;
				foreach($registros as $palestranteCertificado){
					
					$nomePalestra = null;
					if($palestra->ProprioEvento != 1) 
						$nomePalestra = $palestra->Nome;
					
					$html .= '
					<tr>
						<td>'.@$palestranteCertificado->nomePalestrante . @$palestranteCertificado->nomeParticipante.'</td>
						<td></td>
						<td>'.$nomePalestra.'</td>
						<td>'.$palestra->NomeEvento.'</td>
						<td>'.date('d/m/Y',strtotime($palestra->Data)).'</td>
						<td>'.date('H:i',strtotime($palestra->CargaHoraria)).'</td>
						<td>'.$palestranteCertificado->codigo.'</td>
						<td>/'.date('y',strtotime($palestra->Data)).'</td>
						<td>'.$palestranteCertificado->folha.'</td>
						<td>'.$palestranteCertificado->livro.'</td>
					</tr>';
					
					if($i % 35 == 0){
						$html .= '
						</tbody>
						</table>
						
						<table id="tblAta" width="100%" border="0" cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<th>Palestrante</th>
								<th style="padding:2px 20px;">Assinatura</th>
								<th>Título da atividade</th>
								<th>Evento</th>
								<th>Data</th>
								<th>CH</th>
								<th colspan="2">Nº de registro</th>
								<th>Folha</th>
								<th>Livro</th>
							</tr>
						</thead>
						<tbody>';
					}
				$i++;
				}
				
				$html .= '
				</tbody>
				</table>
				</body>
				</html>';
				
				$arquivo = 'ata.pdf';
				$caminho = '/certificados-gerados/'.AppBaseController::ParseUrl($palestra->Nome).'-'.$palestra->IdPalestra.'/';
				
				AppBaseController::geraPDF($arquivo, GlobalConfig::$APP_ROOT.$caminho, $html,'a4','portrait');
				
				//AppBaseController::downloadArquivo(GlobalConfig::$APP_ROOT.$caminho.$arquivo, 'Ata - '.$palestra->Nome);
				
				//echo $html;
				
				// echo '<pre>';
				// print_r($registros);
				// echo '</pre>';
				
				//echo '<embed id="iwc" name="iwc" src="'.GlobalConfig::$ROOT_URL.$caminho.$arquivo.'" width="885" height="628" wmode="transparent" type="application/pdf" style="display:block; margin:0 auto;">';
				
				$json['success'] = true;
				$this->RenderJSON($json);
				
			} catch(NotFoundException $ex){
				throw new NotFoundException("Erro ao emitir ata: a atividade não possui nenhum certificado. ".$ex);
			}
				
		} catch(NotFoundException $ex){
			throw new NotFoundException("Erro ao buscar palestra".$ex);
		}						
							
	}
	
	public function GeraCertificadosPalestra(){
		$idPalestra = $this->GetRouter()->GetUrlParam('idPalestra');
		$orientacao = $this->GetRouter()->GetUrlParam('orientacao');
		$htmlPreviewCertificado = $this->GetRouter()->GetUrlParam('html');
		
		
		$json = json_decode(RequestUtil::GetBody());
		
		//$json = $_GET['data'];

		if (!$json)
		{
			throw new Exception('The request body does not contain valid JSON');
		}

		$dadosModeloCertificado = $this->SafeGetVal($json, 'data');		
		
		
		// VERIFICA SE PALESTRA EXISTE
		try {
			require_once('Model/PalestraPalestrante.php');
				
			require_once('Model/Palestra.php');
			
			$criteria = new PalestraCriteria();
			$criteria->IdPalestra_Equals = $idPalestra;

			$palestra = $this->Phreezer->GetByCriteria('PalestraReporter',$criteria);					
		} catch(NotFoundException $ex){
			throw new NotFoundException("Erro ao buscar palestra".$ex);
		}		
		
		$nomeArquivoModeloCertificado = 'padrao.css';
		
		$bgOrientacao = null;
		if($orientacao === 'portrait')
			$bgOrientacao = '-portrait'; 
		
		$html = '
				<html>
				<head>
				<meta http-equiv="content-type" content="text/html;charset=utf-8" />
				<meta charset="utf8"/>
				
				<style>
				
				html { margin:0; }
				
				.page-break { page-break-after: always; }
				.page-break:last-child { page-break-after: initial; }
			
				.fixed-pdf { 
					position:fixed!important;
				}
				
				/* Fundo em modo retrato */
				.containerCertificado {
					background:url('.GlobalConfig::$ROOT_URL.'styles/certificados/images/moldura-padrao'.$bgOrientacao.'.png); 
				}
			
				</style>
				
				<link rel="stylesheet" type="text/css" media="screen,print" href="'. GlobalConfig::$ROOT_URL. '/styles/certificados/'.$nomeArquivoModeloCertificado.'" />
				
				</head>

				<body class="pdf">	
					<div class="containerCertificado">					
				    '.$dadosModeloCertificado.'							
					</div>
				</body>
				</html>';
				
				$arquivo = 'palestra.pdf';
				$caminho = '/certificados-gerados/'.AppBaseController::ParseUrl($palestra->Nome).'-'.$palestra->IdPalestra.'/';
				
				AppBaseController::geraPDF($arquivo, GlobalConfig::$APP_ROOT.$caminho, $html,'a4',$orientacao);

				echo '<embed id="iwc" name="iwc" src="'.GlobalConfig::$ROOT_URL.$caminho.$arquivo.'" width="100%" height="300" wmode="transparent" type="application/pdf" style="display:block; margin:0 auto;">';
				
				//$this->DownloadAta(223);
				
				//$this->RenderJSON($html);
	}
	
	public function DownloadAta($paramIdPalestra=null)
	{
		$idPalestra = $this->GetRouter()->GetUrlParam('idPalestra');
		
		if($paramIdPalestra) $idPalestra = $paramIdPalestra;
		
		$palestra = $this->Phreezer->Get('Palestra',$idPalestra);
		
		$arquivo = 'ata.pdf';
		$caminho = '/certificados-gerados/'.AppBaseController::ParseUrl($palestra->Nome).'-'.$palestra->IdPalestra.'/';
		
		AppBaseController::downloadArquivo(GlobalConfig::$APP_ROOT.$caminho.$arquivo, 'Ata - '.$palestra->Nome);
	}
	
	/**
	 * Displays a list view of Certificado objects
	 */
	public function DownloadCertificadoModelo($paramIdPalestra=null)
	{
		$idPalestra = $this->GetRouter()->GetUrlParam('idPalestra');
		
		if($paramIdPalestra) $idPalestra = $paramIdPalestra;
		
		$palestra = $this->Phreezer->Get('Palestra',$idPalestra);
		
		$arquivo = 'palestra.pdf';
		$caminho = '/certificados-gerados/'.AppBaseController::ParseUrl($palestra->Nome).'-'.$palestra->IdPalestra.'/';
		
		AppBaseController::downloadArquivo(GlobalConfig::$APP_ROOT.$caminho.$arquivo, 'Ata - '.$palestra->Nome);
	}	
	
	/**
	 * Pagina para obtenção dos certificados emitidos
	 */
	public function ObterCertificadosEmitidosView()
	{
		$pk = $this->GetRouter()->GetUrlParam('idPalestra');
		
		try {
				$palestra = $this->Phreezer->Get('Palestra',$pk);
				$this->Assign('Palestra',$palestra);
				
				$evento = $this->Phreezer->Get('Evento',$palestra->IdEvento);
				$this->Assign('Evento',$evento);				
			} catch(NotFoundException $ex){
				throw new NotFoundException("A atividade #$pk não existe".$ex);
			}
			
		//$usuario = Controller::GetCurrentUser();
		//$this->Assign('usuario',$usuario);
		$this->Render('ObterCertificadosEmitidosView');
	}
	
	/**
	 * Pagina para validação de certificado
	 */
	public function ValidarCertificadoView()
	{
		$livro = $this->GetRouter()->GetUrlParam('livro');
		$folha = $this->GetRouter()->GetUrlParam('folha');		
		$codigo = $this->GetRouter()->GetUrlParam('codigo');
		
		$codigoBanco = preg_replace('@([0-9])\/[0-9]@','$1',$codigo); //pois lá gera com registro/ano
		
		echo $codigoBanco;
		
		try {
			
			if($livro && $folha && $codigo){
			
					//Certificado
					require_once('Model/Certificado.php');
					$criteria = new CertificadoCriteria();
					$criteria->Livro_Equals = $livro;
					$criteria->Folha_Equals = $folha;
					$criteria->Codigo_Equals = $codigoBanco; //pois lá gera com registro/ano
					
					$certificado = $this->Phreezer->GetByCriteria('Certificado',$criteria);
					$this->Assign('Certificado',$certificado);
					
					//PalestraParticipante
					require_once('Model/PalestraParticipante.php');
					$criteria = new PalestraParticipanteCriteria();
					$criteria->IdCertificado_Equals = $certificado->IdCertificado;
					
					$palestraparticipante = $this->Phreezer->GetByCriteria('PalestraParticipante',$criteria);
					
					
					
					//Elementos
					$participante = $this->Phreezer->Get('Participante',$palestraparticipante->IdParticipante);
					$this->Assign('Participante',$participante);
					
					$palestra = $this->Phreezer->Get('Palestra',$palestraparticipante->IdPalestra);
					$this->Assign('Palestra',$palestra);
					
					$evento = $this->Phreezer->Get('Evento',$palestra->IdEvento);
					$this->Assign('Evento',$evento);
					
					$this->Assign('CertificadoValido',true);
					$this->Assign('FaltaParametros',false);
				
				} else {
					$this->Assign('FaltaParametros',true);
					$this->Assign('CertificadoValido',false);
				}
							
			} catch(NotFoundException $ex){
				//throw new NotFoundException("A atividade #$ex não existe".$ex);
				$this->Assign('CertificadoValido',false);
			}
			
			$this->Render('ValidarCertificadoView');
	}
	
	
	/**
	 * Pagina para validação de certificado
	 */
	public function ObterCertificadoView()
	{
		$cpf = $this->GetRouter()->GetUrlParam('cpf');
		$this->Assign('Participante',null);
		$this->Assign('ArrPalestraParticipantes', null);
			
			if($cpf){
			
					
					try {
					
					//Participante
					require_once('Model/Participante.php');
					$criteria = new ParticipanteCriteria();
					$criteria->Cpf_Equals = $cpf;
					
					$participante = $this->Phreezer->GetByCriteria('Participante',$criteria);
					
					$this->Assign('Participante',$participante);
					
					
					//PalestraParticipante
					require_once('Model/PalestraParticipante.php');
					$criteria = new PalestraParticipanteCriteria();
					$criteria->CpfParticipante_Equals = $cpf;
					$criteria->TemCertificado = true;
					$criteria->Presenca_Equals = 1; //Só pode obter se tiver participado

					$listaPalestraParticipante = $this->Phreezer->Query('PalestraParticipanteReporter',$criteria)->ToObjectArray(true,$this->SimpleObjectParams());

					$this->Assign('ListaCertificados', $listaPalestraParticipante);
					
					//print_r($listaCertificados);
					
					//ESTRANHAMENTE O $CERTIFICADO NÃO SÃO MAISCULOS NO INICIO
					
					$arrPalestraParticipantes = array();
					
					$i=0;
					foreach($listaPalestraParticipante as $palestraParticipante){
						$arrPalestraParticipantes[$i]['PalestraParticipante'] = $palestraParticipante;
						
						$arrPalestraParticipantes[$i]['Certificado'] = $this->Phreezer->Get('Certificado',$palestraParticipante->idCertificado);
						$arrPalestraParticipantes[$i]['Palestra'] = $this->Phreezer->Get('Palestra',$palestraParticipante->idPalestra);
					$i++;
					}
					
				
					$this->Assign('ArrPalestraParticipantes', $arrPalestraParticipantes);

					
					/*
					$certificado = $this->Phreezer->Get('Certificado',$listaCertificados[0]->IdCertificado);
					$this->Assign('Certificado',$certificado);
					
					$certificado = $this->Phreezer->GetByCriteria('Certificado',$criteria);
					$this->Assign('Certificado',$certificado);
					*/
					
					} catch(NotFoundException $ex){
						throw new NotFoundException("Participante com CPF #$ex não existe".$ex);
						$this->Assign('CertificadoValido',false);
					}
					
					
					
					
					
					
					//Elementos
					//$participante = $this->Phreezer->Get('Participante',$listaCertificados[1]->IdParticipante);
					//
					
					
					
					
					$this->Assign('CertificadoValido',true);
					$this->Assign('FaltaParametros',false);
				
				} else {
					$this->Assign('FaltaParametros',true);
					$this->Assign('CertificadoValido',false);
				}
							
			
			
			$this->Render('ObterCertificadoView');
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
			
			$criteria->IdCertificado_GreaterThan = 1; // para não lista o certificado "sem certificado"
			
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

			if($certificado->IdCertificado == 1){
				throw new Exception('O certificado não pode ser excluido. Erro x42CTF01');
			} else {	
				$certificado->Delete();
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
