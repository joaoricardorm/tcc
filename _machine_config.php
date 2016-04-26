<?php

/**
 * Sistema desenvolvido por João Ricardo Alves de Paula
 * apresentado como trabalho de conclusão de curso à FAROL - Faculdade de Rolim de Moura
 * em dezembro de 2015
 * contato através do e-mail joaoricardo.rm@gmail.com
*/




////NÃO MODIFIQUE AS CONFIGURAÇÕES ABAIXO, A NÃO SER QUE TENHA CERTEZA DO QUE ESTÁ FAZENDO////









/**
 * @package Certificados FAROL
 *
 * MACHINE-SPECIFIC CONFIGURATION SETTINGS
 *
 * The configuration settings in this file can be changed to suit the
 * machine on which the app is running (ex. loocal, staging or production).
 *
 * This file should not be added to version control, rather a template
 * file should be added instead and then copied for each install
 */

require_once 'verysimple/Phreeze/ConnectionSetting.php';
require_once("verysimple/HTTP/RequestUtil.php");

require_once('configuracao_servidor.php'); //CONFIGURAÇÕES REALIZADAS PELO ADMINISTRADOR DO SERVIDOR



//Configuracao local
/** database connection settings */
GlobalConfig::$CONNECTION_SETTING = new ConnectionSetting();
GlobalConfig::$CONNECTION_SETTING->ConnectionString = $servidor_banco_de_dados;
GlobalConfig::$CONNECTION_SETTING->DBName = $banco_de_dados;
GlobalConfig::$CONNECTION_SETTING->Username = $usuario;
GlobalConfig::$CONNECTION_SETTING->Password = $senha;
GlobalConfig::$CONNECTION_SETTING->Charset = $codificacao_dos_caracteres;
GlobalConfig::$CONNECTION_SETTING->Type = "MySQLi";
GlobalConfig::$CONNECTION_SETTING->Multibyte = true;

//GlobalConfig::$CONNECTION_SETTING->BootstrapSQL = "SET SQL_BIG_SELECTS=1";

/** the root url of the application with trailing slash, for example http://localhost/certificados farol/ */
GlobalConfig::$ROOT_URL = RequestUtil::GetServerRootUrl() . 'tcc/';

/** timezone */
date_default_timezone_set($fuso_horario);

/** functions for php 5.2 compatibility */
if (!function_exists('lcfirst')) {
	function lcfirst($string) {
		return substr_replace($string, strtolower(substr($string, 0, 1)), 0, 1);
	}
}

// if Multibyte support is specified then we need to check if multibyte functions are available
// if you receive this error then either install multibyte extensions or set Multibyte to false
if (GlobalConfig::$CONNECTION_SETTING->Multibyte && !function_exists('mb_strlen'))
	die('<html>Multibyte extensions are not installed but Multibyte is set to true in _machine_config.php</html>');

/** level 2 cache */
// require_once('verysimple/Util/MemCacheProxy.php');
// GlobalConfig::$LEVEL_2_CACHE = new MemCacheProxy(array('localhost'=>'11211'));
// GlobalConfig::$LEVEL_2_CACHE_TEMP_PATH = sys_get_temp_dir();
// GlobalConfig::$LEVEL_2_CACHE_TIMEOUT = 5; // default is 5 seconds which will not be highly noticable to the user

/** additional machine-specific settings */

?>