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
		
		//$this->RequerPermissao(Usuario::$PERMISSION_USER,'SecureExample.LoginForm');
	}

	
	/**
	 * Displays a list view of Certificado objects
	 */
	public function EmitirCertificadosView()
	{
		
		
		// Requer permissão de acesso
		$this->RequirePermission(Usuario::$P_ADMIN,
				'SecureExample.LoginForm',
				'Autentique-se para acessar esta página',
				'Você não possui permissão para acessar essa página ou sua sessão expirou');
		
		
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
		
		
		
		// Requer permissão de acesso
		$this->RequirePermission(Usuario::$P_ADMIN,
				'SecureExample.LoginForm',
				'Autentique-se para acessar esta página',
				'Você não possui permissão para acessar essa página ou sua sessão expirou');
				
		
		
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
		
		
		// Requer permissão de acesso
		$this->RequirePermission(Usuario::$P_ADMIN,
				'SecureExample.LoginForm',
				'Autentique-se para acessar esta página',
				'Você não possui permissão para acessar essa página ou sua sessão expirou');
		
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


		// Requer permissão de acesso
		$this->RequirePermission(Usuario::$P_ADMIN,
				'SecureExample.LoginForm',
				'Autentique-se para acessar esta página',
				'Você não possui permissão para acessar essa página ou sua sessão expirou');
		
		
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

		// Requer permissão de acesso
		$this->RequirePermission(Usuario::$P_ADMIN,
				'SecureExample.LoginForm',
				'Autentique-se para acessar esta página',
				'Você não possui permissão para acessar essa página ou sua sessão expirou');	
		
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


		// Requer permissão de acesso
		$this->RequirePermission(Usuario::$P_ADMIN,
				'SecureExample.LoginForm',
				'Autentique-se para acessar esta página',
				'Você não possui permissão para acessar essa página ou sua sessão expirou');
				
		
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
	
	public function GeraCertificadoModelo(){
		
		
		// Requer permissão de acesso
		$this->RequirePermission(Usuario::$P_ADMIN,
				'SecureExample.LoginForm',
				'Autentique-se para acessar esta página',
				'Você não possui permissão para acessar essa página ou sua sessão expirou');
		
		
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
	
	
	
	
	
	
	//*****///****CHAMA FUNÇÃO DE GERAR CERTIFICADO DE CADA USUARIO DA PALESTRA******///*****///
	public function GeraCertificadoParticipante($idParticipante, $idPalestra,$replace=false,$ehPalestrante=false){				
		
		
		// Requer permissão de acesso
		$this->RequirePermission(Usuario::$P_ADMIN,
				'SecureExample.LoginForm',
				'Autentique-se para acessar esta página',
				'Você não possui permissão para acessar essa página ou sua sessão expirou');
				
		
		//Palestra
		$palestra = $this->Phreezer->Get('Palestra',$idPalestra);
		
		//Evento
		$evento = $this->Phreezer->Get('Evento',$palestra->IdEvento);
		

		//SE FOR PALESTRANTE OU SE FOR PARTICIPANTE
		if($ehPalestrante){
			
			//PalestraParticipante
			require_once('Model/PalestraPalestrante.php');
			$criteria = new PalestraPalestranteCriteria();
			$criteria->IdPalestra_Equals = $idPalestra; 
			$criteria->IdPalestrante_Equals = $idParticipante; 	
			
			$palestrante = $this->Phreezer->GetByCriteria('PalestraPalestranteReporter',$criteria);
			
			//Certificado
			$certificado = $this->Phreezer->Get('Certificado',$palestrante->IdCertificado);
			
		} else {
			
			//PalestraPalestrante
			require_once('Model/PalestraPalestrante.php');
			$criteria = new PalestraPalestranteCriteria();
			$criteria->IdPalestra_Equals = $idPalestra; 

			$palestrantes = $this->Phreezer->Query('PalestraPalestranteReporter',$criteria)->ToObjectArray(true,$this->SimpleObjectParams());
			
			//PalestraParticipante
			require_once('Model/PalestraParticipante.php');
			$criteria = new PalestraParticipanteCriteria();
			$criteria->IdPalestra_Equals = $idPalestra; 
			$criteria->IdParticipante_Equals = $idParticipante; 	
			
			$participante = $this->Phreezer->GetByCriteria('PalestraParticipanteReporter',$criteria);
			
			//Certificado
			$certificado = $this->Phreezer->Get('Certificado',$participante->IdCertificado);
			
		}		
		
		//Modelo Certificado
		$modeloCertificado = $this->Phreezer->Get('ModeloCertificado',$palestra->IdModeloCertificado);		
		
		$dadosModeloCertificado = $modeloCertificado->Elementos;
		
		if($ehPalestrante){
			$textoParticipante = json_decode($modeloCertificado->TextoPalestrante);
		} else {
			$textoParticipante = json_decode($modeloCertificado->TextoParticipante);
		}
		
		//print_r( $textoParticipante);
		
		//Carrega HTML
		// $obj = new DOMDocument();
		// $obj->loadHTML($dadosModeloCertificado);
		// $obj->preserveWhiteSpace = false;
		//echo $obj->saveHTML();
		
		
		//Orientacao
		$orientacao = preg_match("/A4portrait/", $dadosModeloCertificado, $matches);
		if($orientacao) $orientacao = 'portrait'; else $orientacao = 'landscape';
		
		
		//só pegar os 2 primeiros palestrantes se for retrato, ou 3 se for paisagem
		if($ehPalestrante == false){
			if($orientacao == 'portrait')
				$palestrantes = array_slice($palestrantes, 0, 2);
			else
				$palestrantes = array_slice($palestrantes, 0, 3);
		}
			
		
		//exibe assinatura do particcipante removendo o hide
		if($ehPalestrante == false)
			$dadosModeloCertificado = preg_replace('/hide-palestrante(\ hide)?/i', '', $dadosModeloCertificado);
		
		///REMOVER ISSO!!!
		//echo '<link rel="stylesheet" type="text/css" media="screen,print" href="'. GlobalConfig::$ROOT_URL. '/styles/certificados/padrao.css" />';
		
		$classePalestrante = '';
		$tableStyle = '';
		if($ehPalestrante){
			$classePalestrante = 'palestrante';
			$tableStyle = '';
		}
		
		if($orientacao === 'portrait') $tableStyle='style="margin-top:10mm!important;"';
		
		$htmlAssinaturas = '';
		//REMOVE ASSINATURAS ESTÁTICAS E COLOCA DINÂMICAS
		$htmlAssinaturas .=
		'<table class="assinaturas '.$classePalestrante.' dinamico justifyleft" '.$tableStyle.'>
			<tbody><tr>';
				
				if($ehPalestrante){
						
						//print_r($palestrante);
					
						$totalPalestrantes = 0; //vai somar +1 para dar 100%;
					
						$htmlAssinaturas .= '<td align="center" valign="bottom" style="width:'.(100/($totalPalestrantes+1)).'%!important; margin:0px 10mm; vertical-align:bottom;">';
						if($palestrante->ImagemAssinatura){
							$htmlAssinaturas .= '<img style="width:auto; height:22mm;" id="AssinaturaPalestrante" class="assinatura" src="'.GlobalConfig::$ROOT_URL.'images/uploads/logos/small/'.$palestrante->ImagemAssinatura.'">';
						}
						$htmlAssinaturas .= '</td>';
				
				
				} else {
					
					$totalPalestrantes = sizeof($palestrantes);
					
					foreach($palestrantes as $palestrante){					
						$htmlAssinaturas .= '<td align="center" valign="bottom" style="width:'.(100/($totalPalestrantes+1)).'%!important; margin:0px 10mm; vertical-align:bottom;">';
						if($palestrante->imagemAssinatura){
							$htmlAssinaturas .= '<img style="width:auto; height:22mm;" id="AssinaturaPalestrante" class="assinatura" src="'.GlobalConfig::$ROOT_URL.'images/uploads/logos/small/'.$palestrante->imagemAssinatura.'">';
						}
						$htmlAssinaturas .= '</td>';						
					}
					
				}
		
		$htmlAssinaturas .=
				'<td style="height:20mm; vertical-align:bottom;"></td>
			</tr>
			<tr>';
			
				if(!$ehPalestrante){
					foreach($palestrantes as $palestrante){	
						$htmlAssinaturas .= '<td style="width:'.(100/($totalPalestrantes+1)).'%!important;"><hr style="0 margin:20px!important;"></td>';
					}
				}
				
		$htmlAssinaturas .= '
				<td valign="bottom"><hr></td>
			</tr>
			<tr>';
		
		
		if($ehPalestrante){	
		
			//remove assinatura do participante
			$dadosModeloCertificado = preg_replace('/<td class="hide-palestrante">(.*?)<\\/td>/s', '', $dadosModeloCertificado);
		
			$htmlAssinaturas .= 
				'<td align="center" valign="top"><small><strong>'.$palestrante->NomePalestrante.'</strong><br>'.$palestrante->CargoPalestrante.'</small></td>';			
		} else {
			
			foreach($palestrantes as $palestrante){	
				$htmlAssinaturas .= 
					'<td align="center" valign="top"><small><strong>'.$palestrante->nomePalestrante.'</strong><br>'.$palestrante->cargoPalestrante.'</small></td>';
			}
			
			$htmlAssinaturas .=
			'<td align="center" valign="top" style="width:'.(100/($totalPalestrantes+1)).'%!important;"><small><strong>'.$participante->NomeParticipante.'</strong><br>Participante</small></td>';
		}
		
		$htmlAssinaturas .=
			'</tr>
		</tbody></table>';
		
		$dadosModeloCertificado = preg_replace('/<table class="assinaturas(.*?)"(.*?)>(.*?)<\\/table>/s', $htmlAssinaturas, $dadosModeloCertificado);
		
		
		//$dadosModeloCertificado = preg_replace('/\<[\/]?(table|tr|td)([^\>]*)\>/i', '', $dadosModeloCertificado);
		
		
		//preg_match_all('/(<table[^>]*>(?:.|\n)*(?=<\/table>))/',
		
		// echo '--------------------------------------';
		// print_r($dadosModeloCertificado);
		// echo '--------------------------------------';		
		
		
		
		
		//PEGA A COR DOS ELEMENTOS DINAMICOS
				$cor = preg_match('/id="(nomeParticipante|nomePalestrante|nomeAtividade|cargaHoraria)".*?style="color:\ (.*?)"/',$dadosModeloCertificado,$corArr);
				$corValue = $cor ? $corArr[2] : '';
				
				$styleCor = '';
				if($corValue != '')
					$styleCor = 'color: '.$corValue.';';
					
					//echo '<Br>~~~ ~~~~~~'.$corValue.'********<br>';
				
				if(preg_match('/id="(nomeParticipante|nomePalestrante)" class="(.*?)"/',$dadosModeloCertificado,$centralizarTextoArr));
				$centralizarTexto = $centralizarTextoArr ? $centralizarTextoArr[2] : '';
				
				$classCenter = '';
				if (strpos($centralizarTexto,'center-block') !== false)
					$classCenter = 'center-block';
				
				//echo '<Br>************'.$classCenter.'********<br>';
		
		
		
		
		//SUBSTITUI PELO TEXTO DO PARTICIPANTE
		$tagsFinal = '';		
		foreach($textoParticipante as $tag){
			$join = ' ';
					
			if(is_string($tag)){
				$tag = trim($tag);
				
				if(preg_match('/(,|\.|;|\?)(\ |^$)/i',$tag))
					$join = '';
			
			} else {
				
				if(preg_match('/(,|\.|;|\?)(\ |^$)/i',$tag->label))
					$join = '';
				
				$textCenter = '';
				if($tag->label == 'Nome do Participante' || $tag->label == 'Nome do Palestrante')
					$textCenter = $classCenter; 
				
				$tag = '<span class="dbItemCertificado '.$tag->class.' '.$textCenter.'" style="'.$styleCor.'">'.$tag->label.'</span>';
			
			}
			
			$tagsFinal .= $join.$tag;
		}		
		
		//print_r($tagsFinal);
		
		$novoTexto = $textoParticipante;
		
		//substitui texto dinamico
		$dadosModeloCertificado = preg_replace('/<div id="containerDinamico">(.+?)<\/div>/i', '<div id="containerDinamico">'.$tagsFinal.'</div>', $dadosModeloCertificado);	
		
		//substitui imagem do logotipo
		$dadosModeloCertificado = preg_replace('/(<img id="ImagemLogo" src=)(.+?)"/i', '$1"'.GlobalConfig::$ROOT_URL.'/images/uploads/logos/small/'.$this->Configuracao->ImagemLogo.'"', $dadosModeloCertificado);		
			
		$antigo = array(
			'Nome da Atividade',
			'Local da Atividade',
			'Data da Atividade',
			'Duração do Evento',
			'Carga Horária',
			'Registro nº 9081/15 folha 86 do livro nº 2',
			'validar-certificado/',
			'http://localhost:85/tcc/'
		);
		$novo = array(
			$palestra->Nome,
			$evento->Local,
			date('d/m/Y',strtotime($palestra->Data)),
			$evento->Duracao,
			date('H:i',strtotime($palestra->CargaHoraria)),
			'Registro nº '.$certificado->Codigo.'/'.date('y',strtotime($certificado->DataEmissao)).' folha '.$certificado->Folha.' do livro nº '.$certificado->Livro,
			'validar-certificado/'.$certificado->IdCertificado.'/',
			GlobalConfig::$ROOT_URL
		);
		
		
		if($ehPalestrante){
			
			array_push($antigo,
				'Nome do Palestrante'
			);
			array_push($novo,
				$palestrante->NomePalestrante
			);
			
		} else {
			
			array_push($antigo,
				'Nome do Participante'
			);
			array_push($novo,
				$participante->NomeParticipante
			);
			
		}
		
		
		$dadosModeloCertificado = str_replace($antigo,$novo,$dadosModeloCertificado);

		
		
		//echo '&&&begin&&&&&&&&&'.$htmlAssinaturas.'&&&&&end&&&&&&';

		
				
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
				
				
				//echo $html;
				
				if($ehPalestrante)
					$arquivo = 'palestrante'.$palestrante->IdPalestrante	.'.pdf';
				else
					$arquivo = 'palestra'.$participante->IdParticipante.'.pdf';
				
				$caminho = '/certificados-gerados/'.AppBaseController::ParseUrl($palestra->Nome).'-'.$palestra->IdPalestra.'/';
				
				if($replace or !file_exists(GlobalConfig::$APP_ROOT.$caminho.$arquivo)){
					AppBaseController::geraPDF($arquivo, GlobalConfig::$APP_ROOT.$caminho, $html,'a4',$orientacao);
				}

				//echo $html;
				
				//echo '<embed id="iwc" name="iwc" src="'.GlobalConfig::$ROOT_URL.$caminho.$arquivo.'" width="100%" height="300" wmode="transparent" type="application/pdf" style="display:block; margin:0 auto;">';
				
				//$this->DownloadCertificadoParticipante($palestra->IdPalestra, $participante->IdParticipante);
				
				
				
				
				//----->RENDER JSON !IMPORTANTE
				
			//	$this->RenderJSON(array('success'=>true));
				
	}
	
	
	
	
	
	
	//*****///****CHAMA FUNÇÃO DE GERAR CERTIFICADO DE CADA USUARIO DA PALESTRA******///*****///
	
	public function GeraCertificadosPalestraLote(){
		
		// Requer permissão de acesso
		$this->RequirePermission(Usuario::$P_ADMIN,
				'SecureExample.LoginForm',
				'Autentique-se para acessar esta página',
				'Você não possui permissão para acessar essa página ou sua sessão expirou');
		
		
		$idPalestra = $this->GetRouter()->GetUrlParam('idPalestra');
		
		if($this->GetRouter()->GetUrlParam('substituir') === 'true'){
			$substituir = true;
		} else {
			$substituir = false;
		}
		
		if($this->GetRouter()->GetUrlParam('palestrantes')){
			
			//PalestraParticipante
			require_once('Model/PalestraPalestrante.php');
			$criteria = new PalestraPalestranteCriteria();
			$criteria->IdPalestra_Equals = $idPalestra;

			$palestrantes = $this->Phreezer->Query('PalestraPalestranteReporter',$criteria)->ToObjectArray(true,$this->SimpleObjectParams());
			
			$pessoas = array();
			foreach($palestrantes as $palestrante){
				$pessoas[] = $palestrante->idPalestrante;
			}
			
			if($this->GetRouter()->GetUrlParam('idPalestrante'))
				$pessoas = array($this->GetRouter()->GetUrlParam('idPalestrante'));
			
			$ehPalestrante = true;
			
			print_r($pessoas);
			
		} else {
			$pessoas = json_decode($this->GetRouter()->GetUrlParam('participantes'));
			$ehPalestrante = false;
		}

		foreach($pessoas as $pessoa){
			$this->GeraCertificadoParticipante($pessoa, $idPalestra,$substituir,$ehPalestrante); //false=substitui os certificados existentes
		}
		
		
		foreach($pessoas as $pessoa){
			if($ehPalestrante){
					return 'Pegou '.$idPalestra.' palestrante '.$pessoa; 
					//$this->DownloadCertificadoPalestrante($idPalestra, $palestrante);
			} else {
					return 'Pegou '.$idPalestra.' participantes '.$pessoa; 
					//$this->DownloadCertificadoParticipante($idPalestra, $participante);
			}
		}
		
		
	}
	
	
	//*****///****FUNÇÃO PARA MESCLAR OS CERTIFICADOS DE CADA PARTICIPANTE E PALESTRANTE DA PALESTRA PARA IMPRESSAO******///*****///
	
	public function MesclarCertificadosPalestraLote(){
		
		// Requer permissão de acesso
		$this->RequirePermission(Usuario::$P_ADMIN,
				'SecureExample.LoginForm',
				'Autentique-se para acessar esta página',
				'Você não possui permissão para acessar essa página ou sua sessão expirou');
		
		$idPalestra = $this->GetRouter()->GetUrlParam('idPalestra');
		$participantes = $this->GetRouter()->GetUrlParam('participantes');
		
		//Palestra
		$palestra = $this->Phreezer->Get('Palestra',$idPalestra);
		
		if($this->GetRouter()->GetUrlParam('palestrantes')){
			
			//PalestraParticipante
			require_once('Model/PalestraPalestrante.php');
			$criteria = new PalestraPalestranteCriteria();
			$criteria->IdPalestra_Equals = $idPalestra;

			$palestrantes = $this->Phreezer->Query('PalestraPalestranteReporter',$criteria)->ToObjectArray(true,$this->SimpleObjectParams());
			
			$pessoas = array();
			foreach($palestrantes as $palestrante){
				$pessoas[] = $palestrante->idPalestrante;
			}
			
			if($this->GetRouter()->GetUrlParam('idPalestrante'))
				$pessoas = array($this->GetRouter()->GetUrlParam('idPalestrante'));
			
			$ehPalestrante = true;
			
			print_r($pessoas);
			
		} else {
			$pessoas = json_decode($this->GetRouter()->GetUrlParam('participantes'));
			$ehPalestrante = false;
		}

		
		
		$caminho = '/certificados-gerados/'.AppBaseController::ParseUrl($palestra->Nome).'-'.$palestra->IdPalestra.'/';
		
		
		include './vendor/PDFMerger/PDFMerger.php';

		$pdf = new PDFMerger;
		
		foreach($pessoas as $pessoa){
			$arquivo = GlobalConfig::$APP_ROOT.$caminho.'palestra'.$pessoa.'.pdf';
			$pdf->addPDF($arquivo);		
		}
		
		$fileMerged = $caminho.'impressao.pdf';
		
		$pdf->merge('file', GlobalConfig::$APP_ROOT.$fileMerged);
		
		if($pdf)
			echo GlobalConfig::$ROOT_URL.$fileMerged;
		
		
	}
	
	//*****///**** ADMIN ZIP- FUNÇÃO PARA ENVIAR OS CERTIFICADOS DE CADA PARTICIPANTE E PALESTRANTE DA PALESTRA PARA OS E-MAILS******///*****///
	
	public function InstanciaEmail(){
		require_once './vendor/PHPMailer/PHPMailerAutoload.php';

		$mail = new PHPMailer;
		
		//debug
		//$mail->SMTPDebug = 3;                               // Enable verbose debug output

		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'mail.avivalista.com.br;mail.lojapotencial.com.br';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'aviva';                 // SMTP username
		$mail->Password = 'ieabrm';                           // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;                                    // TCP port to connect to
		$mail->CharSet = 'UTF-8';
		$mail->setFrom('contato@avivalista.com.br', $this->Configuracao->NomeInstituicao);
		$mail->addReplyTo('joaoricardo.rm@gmail.com', $this->Configuracao->NomeInstituicao); //REPLY TO MEU PARA NÃO IR PARA O DA IGREJA
		//$mail->addBCC('joaoricardo.rm@live.com');
		
		$mail->isHTML(true); // Set email format to HTML	

		return $mail;
	}	
	
	public function EnviarEmailCertificadosPalestraAdmin(){
		
		
		$idPalestra = $this->GetRouter()->GetUrlParam('idPalestra');
		$participantes = $this->GetRouter()->GetUrlParam('participantes');
		
		$voltar = false;
		$voltar = $this->GetRouter()->GetUrlParam('voltar');
			
		//Palestra
		$palestra = $this->Phreezer->Get('Palestra',$idPalestra);
		
		//usuario logado
		$usuario = Controller::GetCurrentUser();
		
		if($this->GetRouter()->GetUrlParam('palestrantes')){
			$pessoas = json_decode($this->GetRouter()->GetUrlParam('palestrantes'));
			$ehPalestrante = true;			
		} else {
			$pessoas = json_decode($this->GetRouter()->GetUrlParam('participantes'));
			$ehPalestrante = false;
		}
		
		$caminho = '/certificados-gerados/'.AppBaseController::ParseUrl($palestra->Nome).'-'.$palestra->IdPalestra.'/';
		
		if($ehPalestrante)
			$tipo = 'palestrante';
		else
			$tipo = 'palestra';
		
		$palestraOuEvento = ' do evento ';
		if($palestra->ProprioEvento == 0)
			$palestraOuEvento = ' da atividade ';
		
		
		//INSTANCIA CONFIGURAÇÕES DO EMAIL
		$mail = $this->InstanciaEmail();
		
		//Adiciona e-mail do usuario logado como copia oculta
		$mail->addAddress($usuario->Email, $usuario->Nome);
		
		$result['success'] = false;
		
		//ZIP PARA CERTIFICADOS USUARIO DO SISTEMA
		$zip = $this->CompactarCertificados(false,$paramParticipantes=$pessoas); //false pra nao baixar
	
		$mail->addAttachment($zip['arquivo'], $zip['novo_nome']);
		
		$mail->Subject = 'Certificado(s)'.$palestraOuEvento;	


		//Corpo do e-mail
		$mail->Body  = '<h1><img width="200" style="max-height:200px;" alt="'.$this->Configuracao->NomeInstituicao.'" src="'.GlobalConfig::$ROOT_URL.'images/uploads/logos/small/'. $this->Configuracao->ImagemLogo .'"></h1>';
		$mail->Body .= '<p>Os certificados soliciadados de <b>'.$palestra->Nome.'</b> estão em anexo.</p>';
		
		//Corpo alternativo
		$mail->AltBody = 'Os certificados soliciadados de '.$palestra->Nome.' estão em anexo.';
	
		if(!$mail->send()) {
			// echo 'Message could not be sent.';
			// echo 'Mailer Error: ' . $mail->ErrorInfo;
			
			$result['success'] = false;
		} else {
			$result['success'] = true;
			$result['email'] = $usuario->Email;
			$mail->clearAttachments(); //remove o zip dos anexos
		}					
		
		if($voltar == true)
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		else
			$this->RenderJSON($result);
		
	}
	
	//*****///****FUNÇÃO PARA ENVIAR OS CERTIFICADOS DE CADA PARTICIPANTE E PALESTRANTE DA PALESTRA PARA OS E-MAILS******///*****///
	
	public function EnviarEmailCertificadosPalestra(){
		
		
		$idPalestra = $this->GetRouter()->GetUrlParam('idPalestra');
		$participantes = $this->GetRouter()->GetUrlParam('participantes');
		
		$voltar = false;
		$voltar = $this->GetRouter()->GetUrlParam('voltar');
			
		//Palestra
		$palestra = $this->Phreezer->Get('Palestra',$idPalestra);
		
		//usuario logado
		$usuario = Controller::GetCurrentUser();
		
		if($this->GetRouter()->GetUrlParam('palestrantes')){
			$pessoas = json_decode($this->GetRouter()->GetUrlParam('palestrantes'));
			$ehPalestrante = true;			
		} else {
			$pessoas = json_decode($this->GetRouter()->GetUrlParam('participantes'));
			$ehPalestrante = false;
		}
		
		$caminho = '/certificados-gerados/'.AppBaseController::ParseUrl($palestra->Nome).'-'.$palestra->IdPalestra.'/';
		
		if($ehPalestrante)
			$tipo = 'palestrante';
		else
			$tipo = 'palestra';
		
		$palestraOuEvento = ' do evento ';
		if($palestra->ProprioEvento == 0)
			$palestraOuEvento = ' da atividade ';
		
	
		//INSTANCIA CONFIGURAÇÕES DO EMAIL
		$mail = $this->InstanciaEmail();		
		
		//Adiciona e-mail do usuario logado como copia oculta quando alguem pedir o certificado no site
		//para caso adicionar cabecalho para admin do sistema que alguem pediu certificado
		$mail->Body = '';
		
		//ENCAMINHA PARA O MEU PARA PODER MOSTRAR LÁ NA HORA E TAL, REMOVER DEPOIS
		//$mail->addBCC($usuario->Email, $usuario->Nome);	
	
		$result['success'] = false;
		foreach($pessoas as $idPessoa){
			
			if($ehPalestrante){				
				$pessoa = $this->Phreezer->Get('Palestrante',$idPessoa);
				$nomePessoa = $pessoa->Nome;
				$emailPessoa = $pessoa->Email;
			} else {
				//PalestraParticipante
				require_once('Model/PalestraParticipante.php');
				$criteria = new PalestraParticipanteCriteria();
				$criteria->IdParticipante_Equals = $idPessoa;
				$criteria->IdPalestra_Equals = $palestra->IdPalestra;
				$criteria->TemCertificado = true;
				$criteria->Presenca_Equals = 1; //Só pode obter se tiver participado
				
				$pessoa = $this->Phreezer->GetByCriteria('PalestraParticipanteReporter',$criteria);
				$nomePessoa = $pessoa->NomeParticipante;
				$emailPessoa = $pessoa->EmailParticipante;
				
				if($emailPessoa == '' or $pessoa->Presenca == 0) continue; //pula se não tiver email
			}
			
			//Dados do e-mail		
			$mail->addAddress($emailPessoa, $nomePessoa); 
			
			$arquivo = GlobalConfig::$APP_ROOT.$caminho.$tipo.$idPessoa.'.pdf';
			$mail->addAttachment($arquivo, 'Certificado - '.$palestra->Nome.' - '.$nomePessoa.'.pdf');
			
			if($ehPalestrante)
				$participadoOuMinistrado = 'ministrado';
			else
				$participadoOuMinistrado = 'participado';
			
			$mail->Subject = 'Certificado'.$palestraOuEvento;	
			
			//Corpo do e-mail
			$mail->Body  .= '<h1><img width="200" style="max-height:200px;" alt="'.$this->Configuracao->NomeInstituicao.'" src="'.GlobalConfig::$ROOT_URL.'images/uploads/logos/small/'. $this->Configuracao->ImagemLogo .'"></h1>';
			$mail->Body .= '<p><b>'.$nomePessoa.'</b>, obrigado por ter '.$participadoOuMinistrado.$palestraOuEvento.'<b>'.$palestra->Nome.'</b> em <b>'.date('d/m/Y',strtotime($palestra->Data)).'</b>.</p>';
			$mail->Body .= '<p>O seu certificado está em anexo.</p>';
			
			//Corpo alternativo
			$mail->AltBody = $nomePessoa.', obrigado por ter '.$participadoOuMinistrado.$palestraOuEvento.' em '.date('d/m/Y',strtotime($palestra->Data)).'. O seu certificado está em anexo.</p>';
		
			if(!$mail->send()) {
				// echo 'Message could not be sent.';
				// echo 'Mailer Error: ' . $mail->ErrorInfo;
				
				$result['success'] = false;
			} else {
				$result['success'] = true;
				$result['email'] = $emailPessoa;
			}
		
		}//foreach
		
		if($voltar == true)
			header('Location: ' . $_SERVER['HTTP_REFERER'].'&s');
		else
			$this->RenderJSON($result);
		
	}
	
	public function CompactarCertificados($baixar=true,$paramParticipantes=false,$paramPalestrantes=false){
	
		$idPalestra = $this->GetRouter()->GetUrlParam('idPalestra');
		
		$ehPalestrante = false;
		if($this->GetRouter()->GetUrlParam('palestrantes'))
			$ehPalestrante = true;	
		
		if($ehPalestrante){
			$participantes = json_decode($this->GetRouter()->GetUrlParam('palestrantes'));
		} else {
			$participantes = json_decode($this->GetRouter()->GetUrlParam('participantes'));
		}
		
		if($paramParticipantes != false){
			$participantes = $paramParticipantes;
		} elseif($paramPalestrantes != false){
			$participantes = $paramPalestrantes;
		}
		
		//Palestra
		$palestra = $this->Phreezer->Get('Palestra',$idPalestra);
		
		$arquivos = array(); $novosNomes = array();
		
		$caminho = GlobalConfig::$APP_ROOT.'/certificados-gerados/'.AppBaseController::ParseUrl($palestra->Nome).'-'.$palestra->IdPalestra.'/';
		
		foreach($participantes as $idParticipante){	

			if($ehPalestrante){
				$participante = $this->Phreezer->Get('Palestrante',$idParticipante);
				$arquivos[] = $caminho.'palestrante'.$idParticipante.'.pdf'; 
				$novosNomes[] = AppBaseController::parseURL($participante->Nome).'.pdf';
			} else {
				$participante = $this->Phreezer->Get('Participante',$idParticipante);
				$arquivos[] = $caminho.'palestra'.$idParticipante.'.pdf'; 
				$novosNomes[] = AppBaseController::parseURL($participante->Nome).'.pdf';
			}
			
			//$arquivos[] = './certificados-gerados/workshop-tecnicas-avancadas-de-pog-128/palestra113.pdf';
		}
		
		//print_r( $arquivos );
		//print_r($nomeFinal);
	
		// $arquivo = 'arquivo-em-pdf-22.pdf';
		
		// $this->geraPDF($arquivo, $caminho, $html);
		
		$nomeAleatorio = 'certificados-'.mt_rand();
		
		$zip = $this->compactar($arquivos, $novosNomes, $caminho, 'certificados',true); //true=sobrescreve arquivo temporario
		
		$eventoOuPalestra = ($palestra->ProprioEvento) ? ' do evento ' : ' da atividade ';
		
		if($ehPalestrante)
			$novo_nome = 'PALESTRANTES - Certificados '.$eventoOuPalestra.$palestra->Nome.'.zip';
		else
			$novo_nome = 'Certificados '.$eventoOuPalestra.$palestra->Nome.'.zip';
		
		if($baixar)	
			AppBaseController::send_download($zip, $novo_nome);
		else {
			$result['arquivo'] = $zip;
			$result['novo_nome'] = $novo_nome;
			return $result;
		}
		
	}
	
	
	public function DownloadAta($paramIdPalestra=null)
	{
		
		// Requer permissão de acesso
		$this->RequirePermission(Usuario::$P_ADMIN,
				'SecureExample.LoginForm',
				'Autentique-se para acessar esta página',
				'Você não possui permissão para acessar essa página ou sua sessão expirou');
		
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
		
		
		// Requer permissão de acesso
		$this->RequirePermission(Usuario::$P_ADMIN,
				'SecureExample.LoginForm',
				'Autentique-se para acessar esta página',
				'Você não possui permissão para acessar essa página ou sua sessão expirou');
				
		
		$idPalestra = $this->GetRouter()->GetUrlParam('idPalestra');
		
		if($paramIdPalestra) $idPalestra = $paramIdPalestra;
		
		$palestra = $this->Phreezer->Get('Palestra',$idPalestra);
		
		$arquivo = 'palestra.pdf';
		$caminho = '/certificados-gerados/'.AppBaseController::ParseUrl($palestra->Nome).'-'.$palestra->IdPalestra.'/';
		
		AppBaseController::downloadArquivo(GlobalConfig::$APP_ROOT.$caminho.$arquivo, 'Ata - '.$palestra->Nome);
	}	
	
	public function DownloadCertificadoParticipante($paramIdPalestra=null,$paramIdParticipante=null)
	{
		$idPalestra = $this->GetRouter()->GetUrlParam('idPalestra');
		$idParticipante = $this->GetRouter()->GetUrlParam('idParticipante');
		
		if($paramIdPalestra) $idPalestra = $paramIdPalestra;
		if($paramIdParticipante) $idParticipante = $paramIdParticipante;
		
		$palestra = $this->Phreezer->Get('Palestra',$idPalestra);
		$participante = $this->Phreezer->Get('Participante',$idParticipante);
		
		$arquivo = 'palestra'.$idParticipante.'.pdf';
		$caminho = '/certificados-gerados/'.AppBaseController::ParseUrl($palestra->Nome).'-'.$palestra->IdPalestra.'/';
		
		AppBaseController::downloadArquivo(GlobalConfig::$APP_ROOT.$caminho.$arquivo, 'Certificado de '.$participante->Nome.' em '.$palestra->Nome);
	}

	public function DownloadCertificadoPalestrante($paramIdPalestra=null,$paramIdPalestrante=null)
	{
		$idPalestra = $this->GetRouter()->GetUrlParam('idPalestra');
		$idPalestrante = $this->GetRouter()->GetUrlParam('idPalestrante');
		
		if($paramIdPalestra) $idPalestra = $paramIdPalestra;
		if($paramIdPalestrante) $idPalestrante = $paramIdPalestrante;
		
		$palestra = $this->Phreezer->Get('Palestra',$idPalestra);
		$palestrante = $this->Phreezer->Get('Palestrante',$idPalestrante);
		
		$arquivo = 'palestrante'.$idPalestrante.'.pdf';
		$caminho = '/certificados-gerados/'.AppBaseController::ParseUrl($palestra->Nome).'-'.$palestra->IdPalestra.'/';
		
		AppBaseController::downloadArquivo(GlobalConfig::$APP_ROOT.$caminho.$arquivo, 'Certificado de '.$palestrante->Nome.' em '.$palestra->Nome);
	}	
	
	/**
	 * Pagina para obtenção dos certificados emitidos
	 */
	public function ObterCertificadosEmitidosView()
	{
		
		// Requer permissão de acesso
		$this->RequirePermission(Usuario::$P_ADMIN,
				'SecureExample.LoginForm',
				'Autentique-se para acessar esta página',
				'Você não possui permissão para acessar essa página ou sua sessão expirou');
		
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
	 * Pagina para validação de certificado POR ID
	 */
	public function ValidarCertificadoPorIdView()
	{
		$idCertificado = $this->GetRouter()->GetUrlParam('idCertificado');
		$this->Assign('GetIdCertificado',$idCertificado);
		
		try {
			
			if($idCertificado != ''){
				
				//Certificado
				require_once('Model/Certificado.php');					
				$certificado = $this->Phreezer->Get('Certificado',$idCertificado);
				$this->Assign('Certificado',$certificado);
			
					//SE FOR PARTICIPANTE
					try {
						
						//PalestraParticipante
						require_once('Model/PalestraParticipante.php');
						$criteria = new PalestraParticipanteCriteria();
						$criteria->IdCertificado_Equals = $certificado->IdCertificado;
						$criteria->TemCertificado = true;
						$criteria->Presenca_Equals = 1; //Só pode obter se tiver participado
						
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
						$this->Assign('EhPalestrante',false);
					
					} catch(Exception $ex){
					
						//SE FOR PARTICIPANTE
						
						//PalestraPalestrante
						require_once('Model/PalestraPalestrante.php');
						$criteria = new PalestraPalestranteCriteria();
						$criteria->IdCertificado_Equals = $certificado->IdCertificado;
						
						$palestrapalestrante = $this->Phreezer->GetByCriteria('PalestraPalestrante',$criteria);
						
						
						
						//Elementos
						$palestrante = $this->Phreezer->Get('Palestrante',$palestrapalestrante->IdPalestrante);
						$this->Assign('Participante',$palestrante); //participante para manter o padrao
						
						$palestra = $this->Phreezer->Get('Palestra',$palestrapalestrante->IdPalestra);
						$this->Assign('Palestra',$palestra);
						
						$evento = $this->Phreezer->Get('Evento',$palestra->IdEvento);
						$this->Assign('Evento',$evento);
						
						$this->Assign('CertificadoValido',true);
						$this->Assign('FaltaParametros',false);
						$this->Assign('EhPalestrante',true);
						
					}
					
					
				
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
	public function ValidarCertificadoView()
	{
		$livro = $this->GetRouter()->GetUrlParam('livro');
		$folha = $this->GetRouter()->GetUrlParam('folha');		
		$codigo = $this->GetRouter()->GetUrlParam('codigo');
		
		$achouBarra = preg_match('@(.*?)\/@',$codigo,$preg_codigoBanco);
		
		if($achouBarra){
			 //pois lá gera com registro/ano
			$codigoBanco = $preg_codigoBanco[1];
		} else {
			$codigoBanco = $codigo;
		}
		
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
					$criteria->TemCertificado = true;
					$criteria->Presenca_Equals = 1; //Só pode obter se tiver participado
					
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
		$cpf = trim($this->GetRouter()->GetUrlParam('cpf'));
		$this->Assign('Participante',null);
		$this->Assign('ArrPalestraParticipantes', null);
		$this->Assign('CPFValido',false);
		
			if($cpf){
			
					
					try {
					
					
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
					} catch(NotFoundException $ex){
						//Palestrante
						require_once('Model/Palestrante.php');
						$criteria = new PalestranteCriteria();
						$criteria->Cpf_Equals = $cpf;
						
						$palestrante = $this->Phreezer->GetByCriteria('Palestrante',$criteria);
						
						$this->Assign('Participante',$palestrante);
						
						
						//PalestraPalestrante
						require_once('Model/PalestraPalestrante.php');
						$criteria = new PalestraPalestranteCriteria();
						$criteria->CpfPalestrante_Equals = $cpf;

						$listaPalestraParticipante = $this->Phreezer->Query('PalestraPalestranteReporter',$criteria)->ToObjectArray(true,$this->SimpleObjectParams());
					}

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

					
					} catch(NotFoundException $ex){
						//throw new NotFoundException("Participante com CPF {$_GET['cpf']} não está cadastrado no sistema");
						$this->Assign('CPFValido',false);
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
		
		// Requer permissão de acesso
		$this->RequirePermission(Usuario::$P_ADMIN,
				'SecureExample.LoginForm',
				'Autentique-se para acessar esta página',
				'Você não possui permissão para acessar essa página ou sua sessão expirou');
		
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
		
		// Requer permissão de acesso
		$this->RequirePermission(Usuario::$P_ADMIN,
				'SecureExample.LoginForm',
				'Autentique-se para acessar esta página',
				'Você não possui permissão para acessar essa página ou sua sessão expirou');
		
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
		
		// Requer permissão de acesso
		$this->RequirePermission(Usuario::$P_ADMIN,
				'SecureExample.LoginForm',
				'Autentique-se para acessar esta página',
				'Você não possui permissão para acessar essa página ou sua sessão expirou');
		
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
		
		// Requer permissão de acesso
		$this->RequirePermission(Usuario::$P_ADMIN,
				'SecureExample.LoginForm',
				'Autentique-se para acessar esta página',
				'Você não possui permissão para acessar essa página ou sua sessão expirou');
		
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
