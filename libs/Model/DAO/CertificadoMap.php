<?php
/** @package    Tcc::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");
require_once("verysimple/Phreeze/IDaoMap2.php");

/**
 * CertificadoMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the CertificadoDAO to the certificado datastore.
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
class CertificadoMap implements IDaoMap, IDaoMap2
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
			self::$FM["IdCertificado"] = new FieldMap("IdCertificado","certificado","id_certificado",true,FM_TYPE_INT,11,null,true);
			self::$FM["DataEmissao"] = new FieldMap("DataEmissao","certificado","data_emissao",false,FM_TYPE_TIMESTAMP,null,"CURRENT_TIMESTAMP",false);
			self::$FM["Livro"] = new FieldMap("Livro","certificado","livro",false,FM_TYPE_INT,11,null,false);
			self::$FM["Folha"] = new FieldMap("Folha","certificado","folha",false,FM_TYPE_INT,11,null,false);
			self::$FM["Codigo"] = new FieldMap("Codigo","certificado","codigo",false,FM_TYPE_INT,11,null,false);
			self::$FM["IdUsuario"] = new FieldMap("IdUsuario","certificado","id_usuario",false,FM_TYPE_INT,11,null,false);
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
			self::$KM["palestra_palestrante_ibfk_3"] = new KeyMap("palestra_palestrante_ibfk_3", "IdCertificado", "PalestraPalestrante", "IdCertificado", KM_TYPE_ONETOMANY, KM_LOAD_LAZY);  // use KM_LOAD_EAGER with caution here (one-to-one relationships only)
			self::$KM["palestra_participante_ibfk_3"] = new KeyMap("palestra_participante_ibfk_3", "IdCertificado", "PalestraParticipante", "IdCertificado", KM_TYPE_ONETOMANY, KM_LOAD_LAZY);  // use KM_LOAD_EAGER with caution here (one-to-one relationships only)
			self::$KM["certificado_ibfk_1"] = new KeyMap("certificado_ibfk_1", "IdUsuario", "Usuario", "IdUsuario", KM_TYPE_MANYTOONE, KM_LOAD_LAZY); // you change to KM_LOAD_EAGER here or (preferrably) make the change in _config.php
		}
		return self::$KM;
	}

}

?>