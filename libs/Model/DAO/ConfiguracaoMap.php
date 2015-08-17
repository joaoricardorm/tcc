<?php
/** @package    Tcc::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");
require_once("verysimple/Phreeze/IDaoMap2.php");

/**
 * ConfiguracaoMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the ConfiguracaoDAO to the configuracao datastore.
 *
 * WARNING: THIS IS AN AUTO-GENERATED FILE
 *
 * This file should generally not be edited by hand except in special circumstances.
 * You can override the default fetching strategies for KeyMaps in _config.php.
 * Leaving this file alone will allow easy re-generation of all DAOs in the event of schema changes
 *
 * @package Tcc::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class ConfiguracaoMap implements IDaoMap, IDaoMap2
{

	private static $KM;
	private static $FM;
	
	/**
	 * {@inheritdoc}
	 */
	public static function AddMap($property,FieldMap $map)
	{
		self::GetFieldMaps();
		self::$FM[$property] = $map;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public static function SetFetchingStrategy($property,$loadType)
	{
		self::GetKeyMaps();
		self::$KM[$property]->LoadType = $loadType;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function GetFieldMaps()
	{
		if (self::$FM == null)
		{
			self::$FM = Array();
			self::$FM["IdConfiguracao"] = new FieldMap("IdConfiguracao","configuracao","id_configuracao",true,FM_TYPE_INT,11,null,true);
			self::$FM["NomeInstituicao"] = new FieldMap("NomeInstituicao","configuracao","nome_instituicao",false,FM_TYPE_VARCHAR,60,null,false);
			self::$FM["ImagemLogo"] = new FieldMap("ImagemLogo","configuracao","imagem_logo",false,FM_TYPE_VARCHAR,50,null,false);
			self::$FM["Cnpj"] = new FieldMap("Cnpj","configuracao","cnpj",false,FM_TYPE_VARCHAR,20,null,false);
			self::$FM["Telefone"] = new FieldMap("Telefone","configuracao","telefone",false,FM_TYPE_VARCHAR,15,null,false);
		}
		return self::$FM;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function GetKeyMaps()
	{
		if (self::$KM == null)
		{
			self::$KM = Array();
		}
		return self::$KM;
	}

}

?>