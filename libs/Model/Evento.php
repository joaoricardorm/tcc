<?php
/** @package    Tcc::Model */

/** import supporting libraries */
require_once("DAO/EventoDAO.php");
require_once("EventoCriteria.php");

/**
 * The Evento class extends EventoDAO which provides the access
 * to the datastore.
 *
 * @package Tcc::Model
 * @author ClassBuilder
 * @version 1.0
 */
class Evento extends EventoDAO
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

		if(!$this->Data) $this->AddValidationError('Data','TEM DATA');
		
		// THESE ARE CUSTOM VALIDATORS
		
		//Reformata data para o banco
		//$this->Data = implode("-",array_reverse(explode("/",$this->Data)));
		
		if (!$this->Nome) $this->AddValidationError('Nome','Nome do evento é obrigatório');
		if (!$this->Data) $this->AddValidationError('Data','Data do evento é obrigatória');
		
		return !$this->HasValidationErrors();

		return parent::Validate();
	}

	/**
	 * @see Phreezable::OnSave()
	 */
	public function OnSave($insert)
	{
		// the controller create/update methods validate before saving.  this will be a
		// redundant validation check, however it will ensure data integrity at the model
		// level based on validation rules.  comment this line out if this is not desired
		if (!$this->Validate()) throw new Exception('Unable to Save Evento: ' .  implode(', ', $this->GetValidationErrors()));
		
		//converte a data para o formato americano antes de salvar no banco
		//$this->Data = date('Y-m-d',strtotime($this->Data));
		
		// OnSave must return true or eles Phreeze will cancel the save operation
		return true;
	}

}

?>