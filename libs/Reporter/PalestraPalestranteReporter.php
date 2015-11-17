<?php
/** @package    Tcc::Reporter */

/** import supporting libraries */
require_once("verysimple/Phreeze/Reporter.php");

/**
 * This is an example Reporter based on the PalestraPalestrante object.  The reporter object
 * allows you to run arbitrary queries that return data which may or may not fith within
 * the data access API.  This can include aggregate data or subsets of data.
 *
 * Note that Reporters are read-only and cannot be used for saving data.
 *
 * @package Tcc::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class PalestraPalestranteReporter extends Reporter
{

	// the properties in this class must match the columns returned by GetCustomQuery().
	// 'CustomFieldExample' is an example that is not part of the `palestra_palestrante` table
	//public $CustomFieldExample;

	public $Id;
	public $IdPalestrante;	
	public $IdPalestra;
	public $IdCertificado;
	
	public $NomePalestrante;
	public $CpfPalestrante;	
	
	public $DataEmissao;
	public $Livro;
	public $Folha;	
	public $Codigo;

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
		/* SQL ANTIGA DO SELECT */
		// $sql = "select
			// 'custom value here...' as CustomFieldExample
			// ,`palestra_palestrante`.`id` as Id
			// ,`palestra_palestrante`.`id_palestrante` as IdPalestrante
			// ,`palestra_palestrante`.`id_palestra` as IdPalestra
			// ,`palestra_palestrante`.`id_certificado` as IdCertificado
		// from `palestra_palestrante`";

		// the criteria can be used or you can write your own custom logic.
		// be sure to escape any user input with $criteria->Escape()
		
		
		
		
		//if (!$criteria->IdPalestra_Equals && !$criteria->IdPalestrante_Equals) throw new Exception('IdPalestra_Equals ou IdPalestrante_Equals é obrigatório!');
		
	$sql = "select
			`palestra_palestrante`.`id` as Id  
			,`palestrante`.`id_palestrante` as IdPalestrante
			,`palestrante`.`nome` as NomePalestrante
			,`palestrante`.`cpf` as CpfPalestrante
			, `palestra_palestrante`.`id_palestra` as IdPalestra
			,`palestra_palestrante`.`id_certificado` as IdCertificado ";
	
if($criteria->InnerJoinCertificado){	
    $sql .=",`certificado`.`data_emissao` as DataEmissao
			,`certificado`.`livro` as Livro
			,`certificado`.`folha` as Folha
			,`certificado`.`codigo` as Codigo ";
}
	
	$sql .=	" from `palestrante` ";
		
		if ($criteria->IdPalestra_Equals){
		
			$sql .= "inner join palestra_palestrante on `palestra_palestrante`.`id_palestrante` = `palestrante`.`id_palestrante`";
			
			
			if($criteria->InnerJoinCertificado)
				$sql .= " inner join certificado on `palestra_palestrante`.`id_certificado` = `certificado`.`id_certificado` ";
			
			
			$sql .= "where `palestra_palestrante`.`id_palestra` = '" . $criteria->Escape($criteria->IdPalestra_Equals) . "'";
			
			if($criteria->IdPalestrante_Equals)
				$sql .= " AND `palestrante`.`id_palestrante` = '" . $criteria->Escape($criteria->IdPalestrante_Equals) . "' ";

		
		} else {
			
			$sql .= "inner join palestra_palestrante on `palestra_palestrante`.`id_palestrante` = `palestrante`.`id_palestrante`";
		
			if($criteria->IdPalestrante_Equals)
				$sql .= " WHERE `palestrante`.`id_palestrante` = '" . $criteria->Escape($criteria->IdPalestrante_Equals) . "' ";			
		
		}
		
		if($criteria->TemCertificado)
			$sql .= " AND `palestra_palestrante`.`id_certificado` > 0 ";
		else if ($criteria->NaoTemCertificado)
			$sql .= " AND `palestra_palestrante`.`id_certificado` = 0 ";
		
		//Agrupa os palestrantes para não duplicar
		if ($criteria->IdPalestra_Equals)
			$sql .= " group by `palestrante`.`id_palestrante` ";
		
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
		$sql = "select count(1) as counter from `palestra_palestrante`";

		// the criteria can be used or you can write your own custom logic.
		// be sure to escape any user input with $criteria->Escape()
		$sql .= $criteria->GetWhere();

		return $sql;
	}
}

?>