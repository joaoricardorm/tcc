<?php
/** @package    Tcc::Model */

/** import supporting libraries */
require_once("DAO/PalestraPalestranteCriteriaDAO.php");

/**
 * The PalestraPalestranteCriteria class extends PalestraPalestranteDAOCriteria and is used
 * to query the database for objects and collections
 * 
 * @inheritdocs
 * @package Tcc::Model
 * @author ClassBuilder
 * @version 1.0
 */
class PalestraPalestranteCriteria extends PalestraPalestranteCriteriaDAO
{
	
	
	
	public $IdPalestra_Equals;
	public $IdPalestrante_Equals;
	public $CpfPalestrante_Equals;
	public $OuterJoinPalestras;
	public $InnerJoinCertificado;
	public $TemCertificado;
	public $NaoTemCertificado;
	public $OrderByNomePalestrante;
	public $Limite;
	
	/**
	 * This is overridden so that we can instruct Phreezer what database field
	 * is referred to by the property "BookId"
	 * @see Criteria::GetFieldFromProp()
	 */
	public function GetFieldFromProp($propname)
	{		
		switch($propname){
			case 'IdPalestra':
				return 'id_palestra';
			case 'IdPalestrante':
				return 'id_palestrante';
			case 'CpfPalestrante':
				return 'cpf';
			case 'OuterJoinPalestras':
				return 'id_palestra';
			case 'InnerJoinCertificado':
				return 'id_certificado';
			case 'TemCertificado':
				return 'id_certificado';
			case 'NaoTemCertificado':
				return 'id_certificado';				

			case 'NomePalestrante':
				return 'NomePalestrante';
				
			default:
				return parent::GetFieldFromProp($propname);
		}
		
		//throw new Exception("Unknown Property '$propname' specified.");
	
	}
	
	
	/**
	 * GetFieldFromProp returns the DB column for a given class property
	 * 
	 * If any fields that are not part of the table need to be supported
	 * by this Criteria class, they can be added inside the switch statement
	 * in this method
	 * 
	 * @see Criteria::GetFieldFromProp()
	 */
	/*
	public function GetFieldFromProp($propname)
	{
		switch($propname)
		{
			 case 'CustomProp1':
			 	return 'my_db_column_1';
			 case 'CustomProp2':
			 	return 'my_db_column_2';
			default:
				return parent::GetFieldFromProp($propname);
		}
	}
	*/
	
	/**
	 * For custom query logic, you may override OnPrepare and set the $this->_where to whatever
	 * sql code is necessary.  If you choose to manually set _where then Phreeze will not touch
	 * your where clause at all and so any of the standard property names will be ignored
	 *
	 * @see Criteria::OnPrepare()
	 */
	/*
	function OnPrepare()
	{
		if ($this->MyCustomField == "special value")
		{
			// _where must begin with "where"
			$this->_where = "where db_field ....";
		}
	}
	*/

}
?>