<?php
/** @package    Tcc::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");
require_once("verysimple/Phreeze/IDaoMap2.php");

/**
 * PalestraParticipanteMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the PalestraParticipanteDAO to the palestra_participante datastore.
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
class PalestraParticipanteMap implements IDaoMap, IDaoMap2
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
			self::$FM["Id"] = new FieldMap("Id","palestra_participante","id",true,FM_TYPE_INT,11,null,true);
			self::$FM["Presenca"] = new FieldMap("Presenca","palestra_participante","presenca",false,FM_TYPE_TINYINT,1,null,false);
			self::$FM["IdParticipante"] = new FieldMap("IdParticipante","palestra_participante","id_participante",false,FM_TYPE_INT,11,null,false);
			self::$FM["IdPalestra"] = new FieldMap("IdPalestra","palestra_participante","id_palestra",false,FM_TYPE_INT,11,null,false);
			self::$FM["IdCertificado"] = new FieldMap("IdCertificado","palestra_participante","id_certificado",false,FM_TYPE_INT,11,null,false);
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
			self::$KM["palestra_participante_ibfk_1"] = new KeyMap("palestra_participante_ibfk_1", "IdPalestra", "Palestra", "IdPalestra", KM_TYPE_MANYTOONE, KM_LOAD_LAZY); // you change to KM_LOAD_EAGER here or (preferrably) make the change in _config.php
			self::$KM["palestra_participante_ibfk_2"] = new KeyMap("palestra_participante_ibfk_2", "IdParticipante", "Participante", "IdParticipante", KM_TYPE_MANYTOONE, KM_LOAD_LAZY); // you change to KM_LOAD_EAGER here or (preferrably) make the change in _config.php
			self::$KM["palestra_participante_ibfk_3"] = new KeyMap("palestra_participante_ibfk_3", "IdCertificado", "Certificado", "IdCertificado", KM_TYPE_MANYTOONE, KM_LOAD_LAZY); // you change to KM_LOAD_EAGER here or (preferrably) make the change in _config.php
		}
		return self::$KM;
	}

}

?>