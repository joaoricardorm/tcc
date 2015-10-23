<?php
/** @package    Tcc::Model */

/** import supporting libraries */
require_once("DAO/ParticipanteDAO.php");
require_once("ParticipanteCriteria.php");

/**
 * The Participante class extends ParticipanteDAO which provides the access
 * to the datastore.
 *
 * @package Tcc::Model
 * @author ClassBuilder
 * @version 1.0
 */
class Participante extends ParticipanteDAO
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
		if (!$this->Nome) $this->AddValidationError('Nome','Nome é obrigatório');
		if (!$this->Cpf) $this->AddValidationError('Cpf','CPF é obrigatório');
		
		//validacao do CPF e E-mail
		if($this->Cpf && !preg_match('/([0-9]{2}[\.]?[0-9]{3}[\.]?[0-9]{3}[\/]?[0-9]{4}[-]?[0-9]{2})|([0-9]{3}[\.]?[0-9]{3}[\.]?[0-9]{3}[-]?[0-9]{2})/i', $this->Cpf)) $this->AddValidationError('Cpf','CPF inválido');
		if($this->Email && !filter_var($this->Email, FILTER_VALIDATE_EMAIL)) $this->AddValidationError('Email','E-mail inválido'); 
		
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
		if (!$this->Validate()) throw new Exception('Unable to Save Participante: ' .  implode(', ', $this->GetValidationErrors()));

		// OnSave must return true or eles Phreeze will cancel the save operation
		return true;
	}

}

?>