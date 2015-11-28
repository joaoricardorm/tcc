<?php
/** @package    Tcc::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");
require_once("verysimple/Phreeze/IDaoMap2.php");

/**
 * ModeloCertificadoMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the ModeloCertificadoDAO to the modelo_certificado datastore.
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
class ModeloCertificadoMap implements IDaoMap, IDaoMap2
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
			self::$FM["IdModeloCertificado"] = new FieldMap("IdModeloCertificado","modelo_certificado","id_modelo_certificado",true,FM_TYPE_INT,11,null,true);
			self::$FM["Nome"] = new FieldMap("Nome","modelo_certificado","nome",false,FM_TYPE_VARCHAR,70,null,false);
			self::$FM["TextoParticipante"] = new FieldMap("TextoParticipante","modelo_certificado","texto_participante",false,FM_TYPE_TEXT,null,null,false);
			self::$FM["TextoPalestrante"] = new FieldMap("TextoPalestrante","modelo_certificado","texto_palestrante",false,FM_TYPE_TEXT,null,null,false);
			self::$FM["ArquivoCss"] = new FieldMap("ArquivoCss","modelo_certificado","arquivo_css",false,FM_TYPE_VARCHAR,15,"padrao",false);
			self::$FM["Elementos"] = new FieldMap("Elementos","modelo_certificado","elementos",false,FM_TYPE_TEXT,null,null,false);
			self::$FM["ElementosPalestrante"] = new FieldMap("ElementosPalestrante","modelo_certificado","elementos_palestrante",false,FM_TYPE_TEXT,null,null,false);
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
			self::$KM["palestra_ibfk_2"] = new KeyMap("palestra_ibfk_2", "IdModeloCertificado", "Palestra", "IdModeloCertificado", KM_TYPE_ONETOMANY, KM_LOAD_LAZY);  // use KM_LOAD_EAGER with caution here (one-to-one relationships only)
		}
		return self::$KM;
	}

}

?>