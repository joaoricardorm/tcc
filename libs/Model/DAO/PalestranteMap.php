<?php
/** @package    Tcc::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");
require_once("verysimple/Phreeze/IDaoMap2.php");

/**
 * PalestranteMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the PalestranteDAO to the palestrante datastore.
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
class PalestranteMap implements IDaoMap, IDaoMap2
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
			self::$FM["IdPalestrante"] = new FieldMap("IdPalestrante","palestrante","id_palestrante",true,FM_TYPE_INT,11,null,true);
			self::$FM["Nome"] = new FieldMap("Nome","palestrante","nome",false,FM_TYPE_VARCHAR,50,null,false);
			self::$FM["Email"] = new FieldMap("Email","palestrante","email",false,FM_TYPE_VARCHAR,50,null,false);
			self::$FM["Cpf"] = new FieldMap("Cpf","palestrante","cpf",false,FM_TYPE_VARCHAR,15,null,false);
			self::$FM["Cargo"] = new FieldMap("Cargo","palestrante","cargo",false,FM_TYPE_VARCHAR,50,null,false);
			self::$FM["ImagemAssinatura"] = new FieldMap("ImagemAssinatura","palestrante","imagem_assinatura",false,FM_TYPE_VARCHAR,50,null,false);
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
			self::$KM["palestra_palestrante_ibfk_1"] = new KeyMap("palestra_palestrante_ibfk_1", "IdPalestrante", "PalestraPalestrante", "IdPalestrante", KM_TYPE_ONETOMANY, KM_LOAD_LAZY);  // use KM_LOAD_EAGER with caution here (one-to-one relationships only)
		}
		return self::$KM;
	}

}

?>