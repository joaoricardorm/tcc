<?php
/** @package    Tcc::Model */

/** import supporting libraries */
require_once("DAO/ConfiguracaoDAO.php");
require_once("ConfiguracaoCriteria.php");

/**
 * The Configuracao class extends ConfiguracaoDAO which provides the access
 * to the datastore.
 *
 * @package Tcc::Model
 * @author ClassBuilder
 * @version 1.0
 */
class Configuracao extends ConfiguracaoDAO
{

	/**
	 * Override default validation
	 * @see Phreezable::Validate()
	 */
	public function Validate()
	{
		// example of custom validation
		// $this->ResetValidationErrors();
		// $errors = $this->GetValidationErrors();
		// if ($error == true) $this->AddValidationError('FieldName', 'Error Information');
		// return !$this->HasValidationErrors();
		
		// EXAMPLE OF CUSTOM VALIDATION LOGIC
		$this->ResetValidationErrors();
		$errors = $this->GetValidationErrors();

		// THESE ARE CUSTOM VALIDATORS
		if (!$this->NomeInstituicao) $this->AddValidationError('NomeInstituicao','Nome é obrigatório');
		
		return !$this->HasValidationErrors();
	}

	/**
	 * @see Phreezable::OnSave()
	 */
	public function OnSave($insert)
	{
		// the controller create/update methods validate before saving.  this will be a
		// redundant validation check, however it will ensure data integrity at the model
		// level based on validation rules.  comment this line out if this is not desired
		if (!$this->Validate()) throw new Exception('Unable to Save Configuracao: ' .  implode(', ', $this->GetValidationErrors()));

		// OnSave must return true or eles Phreeze will cancel the save operation
		return true;
	}

}

?>