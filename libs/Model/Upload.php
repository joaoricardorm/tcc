<?php
/** @package    Tcc::Helper */
/**
 * Realiza o upload de arquivos para o servidor
 *
 * @package Tcc::Model
 * @author ClassBuilder
 * @version 1.0
 */
 
require_once("verysimple/Phreeze/Phreezable.php");
 
class Upload
{		
	public $campo = 'img';
	public $pasta;
	
	
	

	/**
	 * Add a validation error to the error array
	 * @param string property name
	 * @param string error message
	 */
	public function AddValidationError($prop,$msg)
	{
		$this->_val_errors[$prop] = $msg;
	}

	/**
	 * Returns true if this object has validation errors
	 * @return bool
	 */
	protected function HasValidationErrors()
	{
		return count($this->_val_errors) > 0;
	}

	/**
	* Returns the error array - containing an array of fields with invalid values.
	*
	* @access     public
	* @return     array
	*/
	public function GetValidationErrors()
	{
		return $this->_val_errors;
	}

	/**
	 * Clears all previous validation errors
	 */
	protected function ResetValidationErrors()
	{
		$this->_val_errors = Array();
		$this->_base_validation_complete = false;
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
		
		// EXAMPLE OF CUSTOM VALIDATION LOGIC
		$this->ResetValidationErrors();
		$errors = $this->GetValidationErrors();

		// THESE ARE CUSTOM VALIDATORS
		//if(1 == 1) $this->AddValidationError($this->campo,'Rafael travou o Nyck');
		
		if(!isset($_POST[$this->campo]))
			$this->AddValidationError($this->campo,'Nenhuma imagem foi selecionada');
		
		return !$this->HasValidationErrors();
	}		
	
	public function FileUploadCropit(){
		
		// the controller create/update methods validate before saving.  this will be a
		// redundant validation check, however it will ensure data integrity at the model
		// level based on validation rules.  comment this line out if this is not desired
		if (!$this->Validate()) throw new Exception('Não foi possível salvar a imagem: ' .  implode(', ', $this->GetValidationErrors()));
		
		if (!preg_match_all('/^data:image\/(.*);base64,(.*)$/m', $_POST[$this->campo], $match)) die ('Erro ao obter imagem!');
		$pasta = $this->pasta;
		$img_name = md5(uniqid(rand(), true)).'.'.$match[1][0];
		$img_source = base64_decode($match[2][0]);
		
		if (file_put_contents($pasta . $img_name, $img_source) === FALSE) die ('Erro ao salvar imagem!');
		
		require_once('Miniatura.php');		
		
		//small
		miniatura('' ,$img_name, $pasta, $pasta.'small/', 'esticar', 320, 'auto');
		//medium
		miniatura('' ,$img_name, $pasta, $pasta.'medium/', 'esticar', 720, 'auto');
		//large
		miniatura('' ,$img_name, $pasta, $pasta.'large/', 'esticar', 1000, 'auto');
		
		//require_once('../conn/conexao.php');

		// sql para inserir foto no banco
		//mysql_query("INSERT INTO fotos(imagem,tipo) VALUES('$img_name', '$tipodb')", $conn) or die("Erro na consulta.");

		//print 'Imagem salva com sucesso!';
		return $img_name;
	
	}

}

?>