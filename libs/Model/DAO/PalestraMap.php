<?php
/** @package    Tcc::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");
require_once("verysimple/Phreeze/IDaoMap2.php");

/**
 * PalestraMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the PalestraDAO to the palestra datastore.
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
class PalestraMap implements IDaoMap, IDaoMap2
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
			self::$FM["IdPalestra"] = new FieldMap("IdPalestra","palestra","id_palestra",true,FM_TYPE_INT,11,null,true);
			self::$FM["Nome"] = new FieldMap("Nome","palestra","nome",false,FM_TYPE_VARCHAR,70,null,false);
			self::$FM["Data"] = new FieldMap("Data","palestra","data",false,FM_TYPE_DATE,null,null,false);
			self::$FM["CargaHoraria"] = new FieldMap("CargaHoraria","palestra","carga_horaria",false,FM_TYPE_TIME,null,null,false);
			self::$FM["ProprioEvento"] = new FieldMap("ProprioEvento","palestra","proprio_evento",false,FM_TYPE_TINYINT,1,"1",false);
			self::$FM["IdEvento"] = new FieldMap("IdEvento","palestra","id_evento",false,FM_TYPE_INT,11,null,false);
			self::$FM["IdModeloCertificado"] = new FieldMap("IdModeloCertificado","palestra","id_modelo_certificado",false,FM_TYPE_INT,11,null,false);
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
			self::$KM["palestra_palestrante_ibfk_2"] = new KeyMap("palestra_palestrante_ibfk_2", "IdPalestra", "PalestraPalestrante", "IdPalestra", KM_TYPE_ONETOMANY, KM_LOAD_LAZY);  // use KM_LOAD_EAGER with caution here (one-to-one relationships only)
			self::$KM["palestra_participante_ibfk_1"] = new KeyMap("palestra_participante_ibfk_1", "IdPalestra", "PalestraParticipante", "IdPalestra", KM_TYPE_ONETOMANY, KM_LOAD_LAZY);  // use KM_LOAD_EAGER with caution here (one-to-one relationships only)
			self::$KM["palestra_ibfk_2"] = new KeyMap("palestra_ibfk_2", "IdModeloCertificado", "ModeloCertificado", "IdModeloCertificado", KM_TYPE_MANYTOONE, KM_LOAD_LAZY); // you change to KM_LOAD_EAGER here or (preferrably) make the change in _config.php
			self::$KM["palestra_ibfk_1"] = new KeyMap("palestra_ibfk_1", "IdEvento", "Evento", "IdEvento", KM_TYPE_MANYTOONE, KM_LOAD_LAZY); // you change to KM_LOAD_EAGER here or (preferrably) make the change in _config.php
		}
		return self::$KM;
	}

}

?>