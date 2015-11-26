<?php
/** @package    Tcc::Reporter */

/** import supporting libraries */
require_once("verysimple/Phreeze/Reporter.php");

/**
 * This is an example Reporter based on the PalestraParticipante object.  The reporter object
 * allows you to run arbitrary queries that return data which may or may not fith within
 * the data access API.  This can include aggregate data or subsets of data.
 *
 * Note that Reporters are read-only and cannot be used for saving data.
 *
 * @package Tcc::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class PalestraParticipanteReporter extends Reporter
{

	public $Id;
	public $Presenca;
	public $IdParticipante;
	public $IdPalestra;
	public $IdCertificado;
	
	public $NomePalestrante;
	public $CpfPalestrante;	

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
		$sql = "select
			`palestra_participante`.`id` as Id
			,`palestra_participante`.`presenca` as Presenca
			,`palestra_participante`.`id_participante` as IdParticipante
			,`participante`.`nome` as NomeParticipante
			,`participante`.`cpf` as CpfParticipante
			,`palestra_participante`.`id_palestra` as IdPalestra
			,`palestra_participante`.`id_certificado` as IdCertificado ";
	
	if($criteria->InnerJoinCertificado){	
		$sql .=",`certificado`.`data_emissao` as DataEmissao
				,`certificado`.`livro` as Livro
				,`certificado`.`folha` as Folha
				,`certificado`.`codigo` as Codigo ";
	}
		
		$sql .=	" from `participante` ";
		
		if ($criteria->IdPalestra_Equals){
		
			$sql .= " inner join palestra_participante on `palestra_participante`.`id_participante` = `participante`.`id_participante` ";
			
			if($criteria->InnerJoinCertificado)
				$sql .= " inner join certificado on `palestra_participante`.`id_certificado` = `certificado`.`id_certificado` ";
			
			$sql .= " where `palestra_participante`.`id_palestra` = '" . $criteria->Escape($criteria->IdPalestra_Equals) . "'";
			
			if($criteria->IdParticipante_Equals)
				$sql .= " AND `participante`.`id_participante` = '" . $criteria->Escape($criteria->IdParticipante_Equals) . "' ";

		
		} else {
			
			$sql .= "inner join palestra_participante on `palestra_participante`.`id_participante` = `participante`.`id_participante`";
		
			if($criteria->IdParticipante_Equals)
				$sql .= " WHERE `participante`.`id_participante` = '" . $criteria->Escape($criteria->IdParticipante_Equals) . "' ";
			else if($criteria->CpfParticipante_Equals){
				$sql .= " WHERE `participante`.`cpf` = '" . $criteria->Escape($criteria->CpfParticipante_Equals) . "' ";		
				if($criteria->Presenca_Equals){
					$sql .= " AND `palestra_participante`.`presenca` = '" . $criteria->Escape($criteria->Presenca_Equals) . "' ";	
				}
			}				
		
		}

		if($criteria->TemCertificado)
			$sql .= " AND `palestra_participante`.`id_certificado` > 0 ";
		else if ($criteria->NaoTemCertificado)
			$sql .= " AND `palestra_participante`.`id_certificado` = 0 ";
		
		//Agrupa os participantes para não duplicar
		if ($criteria->IdPalestra_Equals)
			$sql .= " group by `participante`.`id_participante` ";
		
		//$sql .= $criteria->GetWhere();
		//$sql .= $criteria->GetOrder();

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
		$sql = "select count(1) as counter from `palestra_participante`";

		// the criteria can be used or you can write your own custom logic.
		// be sure to escape any user input with $criteria->Escape()
		$sql .= $criteria->GetWhere();

		return $sql;
	}
}

?>