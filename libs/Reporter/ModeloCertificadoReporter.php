<?php
/** @package    Tcc::Reporter */

/** import supporting libraries */
require_once("verysimple/Phreeze/Reporter.php");

/**
 * This is an example Reporter based on the ModeloCertificado object.  The reporter object
 * allows you to run arbitrary queries that return data which may or may not fith within
 * the data access API.  This can include aggregate data or subsets of data.
 *
 * Note that Reporters are read-only and cannot be used for saving data.
 *
 * @package Tcc::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class ModeloCertificadoReporter extends Reporter
{

	public $IdPalestra;
	
	public $IdModeloCertificado;
	public $Nome;
	public $TextoParticipante;
	public $TextoPalestrante;
	public $ArquivoCss;
	public $Elementos;

	/*
	* GetCustomQuery returns a fully formed SQL statement.  The result columns
	* must match with the properties of this reporter object.
	*
	* @see Reporter::GetCustomQuery
	* @param Criteria $criteria
	* @return string SQL statement
	*/
	static function GetCustomQuery($criteria)
	{
		$sql = "select ";
		
	if ($criteria->IdPalestra_Equals){	
		$sql.= " `palestra`.`id_palestra` as IdPalestra, ";
	}
	
	$sql.=" `modelo_certificado`.`id_modelo_certificado` as IdModeloCertificado
			,`modelo_certificado`.`nome` as Nome
			,`modelo_certificado`.`texto_participante` as TextoParticipante
			,`modelo_certificado`.`texto_palestrante` as TextoPalestrante
			,`modelo_certificado`.`arquivo_css` as ArquivoCss
			,`modelo_certificado`.`elementos` as Elementos
		from `modelo_certificado`";
		
		if ($criteria->IdPalestra_Equals){
		
			$sql .= " inner join palestra on `palestra`.`id_modelo_certificado` = `modelo_certificado`.`id_modelo_certificado` ";
			
			$sql .= " where `palestra`.`id_palestra` = '" . $criteria->Escape($criteria->IdPalestra_Equals) . "' ";
		
		}

		// the criteria can be used or you can write your own custom logic.
		// be sure to escape any user input with $criteria->Escape()
		//$sql .= $criteria->GetWhere();
		$sql .= $criteria->GetOrder();

		return $sql;
	}
	
	/*
	* GetCustomCountQuery returns a fully formed SQL statement that will count
	* the results.  This query must return the correct number of results that
	* GetCustomQuery would, given the same criteria
	*
	* @see Reporter::GetCustomCountQuery
	* @param Criteria $criteria
	* @return string SQL statement
	*/
	static function GetCustomCountQuery($criteria)
	{
		$sql = "select count(1) as counter from `modelo_certificado`";

		// the criteria can be used or you can write your own custom logic.
		// be sure to escape any user input with $criteria->Escape()
		//$sql .= $criteria->GetWhere();

		return $sql;
	}
}

?>