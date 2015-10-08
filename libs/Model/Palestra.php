<?php
/** @package    Tcc::Model */

/** import supporting libraries */
require_once("DAO/PalestraDAO.php");
require_once("PalestraCriteria.php");

/**
 * The Palestra class extends PalestraDAO which provides the access
 * to the datastore.
 *
 * @package Tcc::Model
 * @author ClassBuilder
 * @version 1.0
 */
class Palestra extends PalestraDAO
{


	/**
	 * PARA OBTER OS PALESTRANTES COM INNER JOIN
	 */
	public function GetPalestrantesUsingReporter()
	{
		require_once 'Model/PalestraPalestranteCriteriaTeste.php';
		
		$criteria = new PalestraPalestranteCriteria();
		$criteria->IdPalestra_Equals = $this->IdPalestra;
		
		// using ToObjectArray will tell phreeze that you want all of the records so no need to do a count query
		return $this->_phreezer->Query('PalestraPalestranteReporter',$criteria)->ToObjectArray();
	}
	
	
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

		if (!$this->Nome) $this->AddValidationError('Nome','Nome da atividade é obrigatório');
		if (!$this->Data) $this->AddValidationError('Data','Data da atividade é obrigatória');
		if (!$this->IdModeloCertificado) $this->AddValidationError('IdModeloCertificado','Modelo do certificado é obrigatório');
		//caso nao tenha definido carga horária ao editar palestra. Só vale se houver IdPalestra, pois no caso o proprio evento ele deixa zerado
		if ($this->CargaHoraria && preg_match('/10:11:12/',$this->CargaHoraria)) $this->AddValidationError('CargaHoraria','A carga horária é obrigatória');
		
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
		if (!$this->Validate()) throw new Exception('Não foi possível salvar a atividade: ' .  implode(', ', $this->GetValidationErrors()));

		// OnSave must return true or eles Phreeze will cancel the save operation
		return true;
	}

}

?>