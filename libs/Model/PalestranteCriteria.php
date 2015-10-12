<?php
/** @package    Tcc::Model */

/** import supporting libraries */
require_once("DAO/PalestranteCriteriaDAO.php");

/**
 * The PalestranteCriteria class extends PalestranteDAOCriteria and is used
 * to query the database for objects and collections
 * 
 * @inheritdocs
 * @package Tcc::Model
 * @author ClassBuilder
 * @version 1.0
 */
class PalestranteCriteria extends PalestranteCriteriaDAO
{
	
	public $IdPalestra_Equals;
	public $OrdemLouca;
	public $OrderByNomePalestrante;
	public $OuterJoinPalestras;
	
	/**
	 * This is overridden so that we can instruct Phreezer what database field
	 * is referred to by the property "BookId"
	 * @see Criteria::GetFieldFromProp()
	 */
	public function GetFieldFromProp($propname)
	{
		if ($propname == 'IdPalestra') return 'id_palestra';
		if ($propname == 'OrderByNomePalestrante') return 'nome';
		if ($propname == 'OuterJoinPalestras') return 'nome';
		
		switch($propname){
			case 'IdPalestra':
				return 'id_palestra';
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