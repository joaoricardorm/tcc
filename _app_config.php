<?php
/**
 * @package Certificados FAROL
 *
 * APPLICATION-WIDE CONFIGURATION SETTINGS
 *
 * This file contains application-wide configuration settings.  The settings
 * here will be the same regardless of the machine on which the app is running.
 *
 * This configuration should be added to version control.
 *
 * No settings should be added to this file that would need to be changed
 * on a per-machine basic (ie local, staging or production).  Any
 * machine-specific settings should be added to _machine_config.php
 */

/**
 * APPLICATION ROOT DIRECTORY
 * If the application doesn't detect this correctly then it can be set explicitly
 */
if (!GlobalConfig::$APP_ROOT) GlobalConfig::$APP_ROOT = realpath("./");

/**
 * check is needed to ensure asp_tags is not enabled
 */
if (ini_get('asp_tags')) 
	die('<h3>Server Configuration Problem: asp_tags is enabled, but is not compatible with Savant.</h3>'
	. '<p>You can disable asp_tags in .htaccess, php.ini or generate your app with another template engine such as Smarty.</p>');

/**
 * INCLUDE PATH
 * Adjust the include path as necessary so PHP can locate required libraries
 */
set_include_path(
		GlobalConfig::$APP_ROOT . '/libs/' . PATH_SEPARATOR .
		GlobalConfig::$APP_ROOT . '/../phreeze/libs' . PATH_SEPARATOR .
		GlobalConfig::$APP_ROOT . '/vendor/phreeze/phreeze/libs/' . PATH_SEPARATOR .
		get_include_path()
);

/**
 * COMPOSER AUTOLOADER
 * Uncomment if Composer is being used to manage dependencies
 */
// $loader = require 'vendor/autoload.php';
// $loader->setUseIncludePath(true);

/**
 * SESSION CLASSES
 * Any classes that will be stored in the session can be added here
 * and will be pre-loaded on every page
 */
require_once "Model/Usuario.php";

/**
 * RENDER ENGINE
 * You can use any template system that implements
 * IRenderEngine for the view layer.  Phreeze provides pre-built
 * implementations for Smarty, Savant and plain PHP.
 */
require_once 'verysimple/Phreeze/SavantRenderEngine.php';
GlobalConfig::$TEMPLATE_ENGINE = 'SavantRenderEngine';
GlobalConfig::$TEMPLATE_PATH = GlobalConfig::$APP_ROOT . '/templates/';

/**
 * ROUTE MAP
 * The route map connects URLs to Controller+Method and additionally maps the
 * wildcards to a named parameter so that they are accessible inside the
 * Controller without having to parse the URL for parameters such as IDs
 */
GlobalConfig::$ROUTE_MAP = array(

	// default controller when no route specified
	'GET:' => array('route' => 'Default.Home'),
	
	// upload de arquivos
	'POST:Upload' => array('route' => 'Upload.UploadImagem'),
		
	// example authentication routes
	'GET:loginform' => array('route' => 'SecureExample.LoginForm'),
	'GET:loginform/(:any)' => array('route' => 'SecureExample.LoginForm', 'params' => array('pagina' => 1)),
	'POST:login' => array('route' => 'SecureExample.Login'),
	
	'POST:login/(:any)' => array('route' => 'SecureExample.Login', 'params' => array('pagina' => 'ABC')),
	
	'GET:secureuser' => array('route' => 'SecureExample.UserPage'),
	'GET:secureadmin' => array('route' => 'SecureExample.AdminPage'),
	'GET:logout' => array('route' => 'SecureExample.Logout'),
		
	// Certificado
	'GET:certificados' => array('route' => 'Certificado.ListView'),
	'GET:certificado/(:num)' => array('route' => 'Certificado.SingleView', 'params' => array('idCertificado' => 1)),
	'GET:api/certificados' => array('route' => 'Certificado.Query'),
	'POST:api/certificado' => array('route' => 'Certificado.Create'),
	'GET:api/certificado/(:num)' => array('route' => 'Certificado.Read', 'params' => array('idCertificado' => 2)),
	'PUT:api/certificado/(:num)' => array('route' => 'Certificado.Update', 'params' => array('idCertificado' => 2)),
	'DELETE:api/certificado/(:num)' => array('route' => 'Certificado.Delete', 'params' => array('idCertificado' => 2)),
		
	// Configuracao
	'GET:configuracoes' => array('route' => 'Configuracao.ListView'),
	'GET:configuracao/(:num)' => array('route' => 'Configuracao.SingleView', 'params' => array('idConfiguracao' => 1)),
	//single view da configuracao sem precisar do id
	'GET:configuracao' => array('route' => 'Configuracao.SingleView'),
	'GET:api/configuracoes' => array('route' => 'Configuracao.Query'),
	'POST:api/configuracao' => array('route' => 'Configuracao.Create'),
	'GET:api/configuracao/(:num)' => array('route' => 'Configuracao.Read', 'params' => array('idConfiguracao' => 2)),
	'PUT:api/configuracao/(:num)' => array('route' => 'Configuracao.Update', 'params' => array('idConfiguracao' => 2)),
	'DELETE:api/configuracao/(:num)' => array('route' => 'Configuracao.Delete', 'params' => array('idConfiguracao' => 2)),
		
	// ModeloCertificado
	'GET:modelocertificados' => array('route' => 'ModeloCertificado.ListView'),
	'GET:modelocertificado/(:num)' => array('route' => 'ModeloCertificado.SingleView', 'params' => array('idModeloCertificado' => 1)),
	'GET:api/modelocertificados' => array('route' => 'ModeloCertificado.Query'),
	'POST:api/modelocertificado' => array('route' => 'ModeloCertificado.Create'),
	'GET:api/modelocertificado/(:num)' => array('route' => 'ModeloCertificado.Read', 'params' => array('idModeloCertificado' => 2)),
	'PUT:api/modelocertificado/(:num)' => array('route' => 'ModeloCertificado.Update', 'params' => array('idModeloCertificado' => 2)),
	'DELETE:api/modelocertificado/(:num)' => array('route' => 'ModeloCertificado.Delete', 'params' => array('idModeloCertificado' => 2)),
		
	// Palestra
	// buscar atividades de um evento
	'GET:evento/(:num)/atividades' => array('route' => 'Palestra.ListView', 'params' => array('idEvento' => 1)),
	'GET:evento/(:num)/atividades/excluir' => array('route' => 'Palestra.ListView', 'params' => array('idEvento' => 1)),
	'GET:evento/(:num)/atividades/(:num)/(:any)' => array('route' => 'Palestra.ListView', 'params' => array('idEvento' => 1, 'idPalestra' => 2)),
	//para excluir atividade
	'GET:evento/(:num)/atividades/(:num)/(:any)/excluir' => array('route' => 'Palestra.ListView', 'params' => array('idEvento' => 1, 'idPalestra' => 2)),
	'GET:palestras/(:num)' => array('route' => 'Palestra.ListView', 'params' => array('idEvento' => 1)),
	'GET:palestras' => array('route' => 'Palestra.ListView'),
	'GET:palestra/(:num)' => array('route' => 'Palestra.SingleView', 'params' => array('idPalestra' => 1)),
	//api para buscar atividades de um evento
	'GET:api/evento/(:num)/atividades' => array('route' => 'Palestra.Query', 'params' => array('idEvento' => 2)),
	'GET:api/palestras' => array('route' => 'Palestra.Query'),
	'POST:api/palestra' => array('route' => 'Palestra.Create'),
	'GET:api/palestra/(:num)' => array('route' => 'Palestra.Read', 'params' => array('idPalestra' => 2)),
	'PUT:api/palestra/(:num)' => array('route' => 'Palestra.Update', 'params' => array('idPalestra' => 2)),
	'DELETE:api/palestra/(:num)' => array('route' => 'Palestra.Delete', 'params' => array('idPalestra' => 2)),
	
	// Evento
	'GET:evento/(:num)/(:any)' => array('route' => 'Evento.ListView', 'params' => array('idEvento' => 1)),
	'GET:eventos' => array('route' => 'Evento.ListView'),
	'GET:evento/(:num)' => array('route' => 'Evento.SingleView', 'params' => array('idEvento' => 1)),
	'GET:api/eventos' => array('route' => 'Evento.Query'),
	'POST:api/evento' => array('route' => 'Evento.Create'),
	'GET:api/evento/(:num)' => array('route' => 'Evento.Read', 'params' => array('idEvento' => 2)),
	'PUT:api/evento/(:num)' => array('route' => 'Evento.Update', 'params' => array('idEvento' => 2)),
	'DELETE:api/evento/(:num)' => array('route' => 'Evento.Delete', 'params' => array('idEvento' => 2)),
		
	// PalestraPalestrante
	'GET:palestrapalestrantes' => array('route' => 'PalestraPalestrante.ListView'),
	'GET:palestrapalestrante/(:num)' => array('route' => 'PalestraPalestrante.SingleView', 'params' => array('id' => 1)),
	'GET:api/palestrapalestrantes' => array('route' => 'PalestraPalestrante.Query'),
	'POST:api/palestrapalestrante' => array('route' => 'PalestraPalestrante.Create'),
	'GET:api/palestrapalestrante/(:num)' => array('route' => 'PalestraPalestrante.Read', 'params' => array('id' => 2)),
	'PUT:api/palestrapalestrante/(:num)' => array('route' => 'PalestraPalestrante.Update', 'params' => array('id' => 2)),
	'DELETE:api/palestrapalestrante/(:num)' => array('route' => 'PalestraPalestrante.Delete', 'params' => array('id' => 2)),
		
	// PalestraParticipante
	'GET:palestraparticipantes' => array('route' => 'PalestraParticipante.ListView'),
	'GET:palestraparticipante/(:num)' => array('route' => 'PalestraParticipante.SingleView', 'params' => array('id' => 1)),
	'GET:api/palestraparticipantes' => array('route' => 'PalestraParticipante.Query'),
	'POST:api/palestraparticipante' => array('route' => 'PalestraParticipante.Create'),
	'GET:api/palestraparticipante/(:num)' => array('route' => 'PalestraParticipante.Read', 'params' => array('id' => 2)),
	'PUT:api/palestraparticipante/(:num)' => array('route' => 'PalestraParticipante.Update', 'params' => array('id' => 2)),
	'DELETE:api/palestraparticipante/(:num)' => array('route' => 'PalestraParticipante.Delete', 'params' => array('id' => 2)),
		
	// Palestrante
	'GET:atividade/(:num)/(:any)/palestrantes' => array('route' => 'Palestrante.ListView', 'params' => array('idPalestra' => 1)),
	'GET:atividade/(:num)/(:any)/palestrantes/novo' => array('route' => 'Palestrante.ListView', 'params' => array('idPalestra' => 1)),
	'GET:atividade/(:num)/(:any)/palestrantes/(:num)/(:any)' => array('route' => 'Palestrante.ListView', 'params' => array('idPalestra' => 1, 'idPalestrante' => 4)),
	'GET:palestrantes' => array('route' => 'Palestrante.ListView'),
	'GET:palestrante/(:num)' => array('route' => 'Palestrante.SingleView', 'params' => array('idPalestrante' => 1)),
	'GET:api/palestrantes' => array('route' => 'Palestrante.Query'),
	'POST:api/palestrante' => array('route' => 'Palestrante.Create'),
	'GET:api/palestrante/(:num)' => array('route' => 'Palestrante.Read', 'params' => array('idPalestrante' => 2)),
	'PUT:api/palestrante/(:num)' => array('route' => 'Palestrante.Update', 'params' => array('idPalestrante' => 2)),
	'DELETE:api/palestrante/(:num)' => array('route' => 'Palestrante.Delete', 'params' => array('idPalestrante' => 2)),
		
	// Participante
	'GET:participantes' => array('route' => 'Participante.ListView'),
	'GET:participante/(:num)' => array('route' => 'Participante.SingleView', 'params' => array('idParticipante' => 1)),
	'GET:api/participantes' => array('route' => 'Participante.Query'),
	'POST:api/participante' => array('route' => 'Participante.Create'),
	'GET:api/participante/(:num)' => array('route' => 'Participante.Read', 'params' => array('idParticipante' => 2)),
	'PUT:api/participante/(:num)' => array('route' => 'Participante.Update', 'params' => array('idParticipante' => 2)),
	'DELETE:api/participante/(:num)' => array('route' => 'Participante.Delete', 'params' => array('idParticipante' => 2)),
		
	// Usuario
	'GET:usuarios' => array('route' => 'Usuario.ListView'),
	'GET:usuario/(:num)' => array('route' => 'Usuario.SingleView', 'params' => array('idUsuario' => 1)),
	'GET:api/usuarios' => array('route' => 'Usuario.Query'),
	'POST:api/usuario' => array('route' => 'Usuario.Create'),
	'GET:api/usuario/(:num)' => array('route' => 'Usuario.Read', 'params' => array('idUsuario' => 2)),
	'PUT:api/usuario/(:num)' => array('route' => 'Usuario.Update', 'params' => array('idUsuario' => 2)),
	'DELETE:api/usuario/(:num)' => array('route' => 'Usuario.Delete', 'params' => array('idUsuario' => 2)),
	
	// catch any broken API urls
	'GET:api/(:any)' => array('route' => 'Default.ErrorApi404'),
	'PUT:api/(:any)' => array('route' => 'Default.ErrorApi404'),
	'POST:api/(:any)' => array('route' => 'Default.ErrorApi404'),
	'DELETE:api/(:any)' => array('route' => 'Default.ErrorApi404')
);

/**
 * FETCHING STRATEGY
 * You may uncomment any of the lines below to specify always eager fetching.
 * Alternatively, you can copy/paste to a specific page for one-time eager fetching
 * If you paste into a controller method, replace $G_PHREEZER with $this->Phreezer
 */
// $GlobalConfig->GetInstance()->GetPhreezer()->SetLoadType("Certificado","certificado_ibfk_1",KM_LOAD_EAGER); // KM_LOAD_INNER | KM_LOAD_EAGER | KM_LOAD_LAZY
// $GlobalConfig->GetInstance()->GetPhreezer()->SetLoadType("Palestra","palestra_ibfk_2",KM_LOAD_EAGER); // KM_LOAD_INNER | KM_LOAD_EAGER | KM_LOAD_LAZY
// $GlobalConfig->GetInstance()->GetPhreezer()->SetLoadType("Palestra","palestra_ibfk_1",KM_LOAD_EAGER); // KM_LOAD_INNER | KM_LOAD_EAGER | KM_LOAD_LAZY
// $GlobalConfig->GetInstance()->GetPhreezer()->SetLoadType("PalestraPalestrante","palestra_palestrante_ibfk_1",KM_LOAD_EAGER); // KM_LOAD_INNER | KM_LOAD_EAGER | KM_LOAD_LAZY
// $GlobalConfig->GetInstance()->GetPhreezer()->SetLoadType("PalestraPalestrante","palestra_palestrante_ibfk_2",KM_LOAD_EAGER); // KM_LOAD_INNER | KM_LOAD_EAGER | KM_LOAD_LAZY
// $GlobalConfig->GetInstance()->GetPhreezer()->SetLoadType("PalestraPalestrante","palestra_palestrante_ibfk_3",KM_LOAD_EAGER); // KM_LOAD_INNER | KM_LOAD_EAGER | KM_LOAD_LAZY
// $GlobalConfig->GetInstance()->GetPhreezer()->SetLoadType("PalestraParticipante","palestra_participante_ibfk_1",KM_LOAD_EAGER); // KM_LOAD_INNER | KM_LOAD_EAGER | KM_LOAD_LAZY
// $GlobalConfig->GetInstance()->GetPhreezer()->SetLoadType("PalestraParticipante","palestra_participante_ibfk_2",KM_LOAD_EAGER); // KM_LOAD_INNER | KM_LOAD_EAGER | KM_LOAD_LAZY
// $GlobalConfig->GetInstance()->GetPhreezer()->SetLoadType("PalestraParticipante","palestra_participante_ibfk_3",KM_LOAD_EAGER); // KM_LOAD_INNER | KM_LOAD_EAGER | KM_LOAD_LAZY
?>