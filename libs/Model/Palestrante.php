<?php
/** @package    Tcc::Model */

/** import supporting libraries */
require_once("DAO/PalestranteDAO.php");
require_once("PalestranteCriteria.php");

/**
 * The Palestrante class extends PalestranteDAO which provides the access
 * to the datastore.
 *
 * @package Tcc::Model
 * @author ClassBuilder
 * @version 1.0
 */
class Palestrante extends PalestranteDAO
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
		
		// CAMPOS OBRIGATÓRIOS
		if (!$this->Nome) $this->AddValidationError('Nome','Nome é obrigatório');
		if (!$this->Cpf) $this->AddValidationError('Cpf','CPF é obrigatório');
		
		//validacao do CPF e E-mail
		if($this->Cpf && !preg_match('/([0-9]{2}[\.]?[0-9]{3}[\.]?[0-9]{3}[\/]?[0-9]{4}[-]?[0-9]{2})|([0-9]{3}[\.]?[0-9]{3}[\.]?[0-9]{3}[-]?[0-9]{2})/i', $this->Cpf)) $this->AddValidationError('Cpf','CPF inválido');
		if($this->Email && !filter_var($this->Email, FILTER_VALIDATE_EMAIL)) $this->AddValidationError('Email','E-mail inválido'); 
		
		//Valida se palestrante ja existe pelo cpf
		try {
			$criteria = new PalestranteCriteria();
			$criteria->Cpf_Equals = $this->Cpf;
		
			$user = $this->_phreezer->GetByCriteria("Palestrante", $criteria);	
		} catch(Exception $ex) {
		}
		//mostra erro somente se o novo email pertencer a outro usuario e nao a si mesmo		
		if(isset($user->Cpf) && $user->IdPalestrante != $this->IdPalestrante)
			$this->AddValidationError('Cpf','Esse CPF pertence a outro palestrante cadastrado no sistema');
		
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
		if (!$this->Validate()) throw new Exception('Unable to Save Palestrante: ' .  implode(', ', $this->GetValidationErrors()));

		// OnSave must return true or eles Phreeze will cancel the save operation
		return true;
	}

}

?>