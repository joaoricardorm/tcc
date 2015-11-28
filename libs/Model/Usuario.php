<?php
/** @package    Tcc::Model */

/** import supporting libraries */
require_once("DAO/UsuarioDAO.php");
require_once("UsuarioCriteria.php");

// #### ALL AUTHENTICATION-RELATED CHANGES ARE COMMENTED IN ALL-CAPS ####

// INCLUDE FILES FOR AUTHENTICATION
require_once("verysimple/Authentication/IAuthenticatable.php");

// BACKWARDS COMPATIBILITY FILE ADDS "password_hash" AND "password_verify" FUNCTIONS
require_once("util/password.php");

/**
 * NOTICE THAT THIS CLASS IMPLEMENTS THE "IAuthenticatable" INTERFACE
 * SO THAT IT CAN BE USED BY PHREEZE AS A "CURRENT USER"
 * 
 * The Usuario class extends UsuarioDAO which provides the access
 * to the datastore.
 *
 * @package Tcc::Model
 * @author ClassBuilder
 * @version 1.0
 */
class Usuario extends UsuarioDAO implements IAuthenticatable 
{	

	static $P_USUARIO = 0;
	static $P_ADMIN = 1;
	
	public $ConfirmarSenha;
	
	/**
	 * {@inheritdoc}
	 */
	public function IsAnonymous()
	{
		// ANY ACCOUNT THAT WAS LOADED FROM THE DB IS NOT CONSIDERED TO BE AN ANONYMOUS USER
		return $this->IsLoaded();
		//return $this->IsLoaded();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function IsAuthorized($permission)
	{
		// THIS COULD BE MADE MORE EFFICIENT BY CACHING THE ROLE VARIABLE
		// OR JUST HARD-CODING ROLE NAMES AND PERMISSIONS SO YOU DON'T
		// HAVE TO DO A DATABASE LOOKUP ON THE ROLE TABLE EVERY TIME
		
		// GET THE ROLE FOR THIS USER
		//$role = $this->GetRole();
		
		// IF THE PERMISSION BEING REQUESTED IS SOMETHING THAT THIS USER'S ROLE HAS, THEN THEY ARE AUTHORIZED
		if ($permission == self::$P_USUARIO && $this->TipoUsuario == 0) return true;
		if ($permission == self::$P_USUARIO && $this->TipoUsuario == 1) return true; //se for administrador
		if ($permission == self::$P_ADMIN && $this->TipoUsuario == 1) return true;
		
		// IF THERE WERE NO MATCHES THEN THAT MEANS THIS USER DOESNT' HAVE THE REQUESTED PERMISSION
		return false;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function Login($username,$password)
	{
		// IF THERE IS NO USERNAME THEN DON'T BOTHER CHECKING THE DATABASE
		if (!$username) return false;
		
		$result = false;
		
		//limpa o cache ao logar
		$this->ClearCache();
		
		$criteria = new UsuarioCriteria();
		
		$filtro = new CriteriaFilter('Login,Email', $username);
		$criteria->AddFilter($filtro);
		
		try {
			
			$user = $this->_phreezer->GetByCriteria("Usuario", $criteria);
			
			// WE NEED TO STRIP OFF THE "!!!" PREFIX THAT WAS ADDED IN "OnSave" BELOW:
			$hash = substr($user->Senha, 3);
			
			if (password_verify($password, $hash))
			{
				// THE USERNAME/PASSWORD COMBO IS CORRECT!
				
				// WHAT THIS IS DOING IS BASICALLY CLONING THE USER RESULT
				// FROM THE DATABASE INTO THE CURRENT RECORD.
				$this->LoadFromObject($user);
				
				$result = true;
			}
			else
			{
				// THE USERNAME WAS FOUND BUT THE PASSWORD DIDN'T MATCH
				$result = false;
			}
			
		}
		catch (NotFoundException $nfex) {
			
			// NO ACCOUNT WAS FOUND WITH THE GIVEN USERNAME
			$result = false;
		}
		
		return $result;
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

		//return parent::Validate();
		
		// validaco
		$this->ResetValidationErrors();
		$errors = $this->GetValidationErrors();

		// validacoes de preenchimento
		if (!$this->Nome) $this->AddValidationError('Nome','Nome é obrigatório');
		if (!$this->Email) $this->AddValidationError('Email','E-mail é obrigatório');
		if (!$this->Login) $this->AddValidationError('Login','Login é obrigatório');
		if (!$this->Senha) $this->AddValidationError('Senha','Senha é obrigatória');
		//validacao do login
		if ($this->Login && !preg_match('/^[A-Za-z][A-Za-z0-9]*(?:(_|\.)[A-Za-z0-9]+)*$/', $this->Login)) 
			$this->AddValidationError('Login','Usuário inválido. Só é permitidos caracteres alfanuméricos, e "." e "_" como separadores'); 
		//validacao do email
		if($this->Email && !filter_var($this->Email, FILTER_VALIDATE_EMAIL)) 
			$this->AddValidationError('Email','E-mail inválido'); 
		//validacao da senha
		if ($this->Senha && substr($this->Senha, 0,3) != '!!!'){
			if(strlen($this->Senha) < 3) $this->AddValidationError('Senha','Mínimo 3 caracteres');
			if(strlen($this->Senha) > 25) $this->AddValidationError('Senha','Máximo 25 caracteres');
			if (!$this->ConfirmarSenha) $this->AddValidationError('ConfirmarSenha','Confirmação da senha é obrigatória');
			if ($this->ConfirmarSenha && (string)$this->Senha != (string)$this->ConfirmarSenha) $this->AddValidationError('ConfirmarSenha','As senhas não correspondem');
		}
		
		//Valida se usuario ja existe pelo E-mail
		try {
			$criteria = new UsuarioCriteria();
			$criteria->Login_NotEquals = $this->Login;
			$criteria->Email_Equals = $this->Email;
			
			$user = $this->_phreezer->GetByCriteria("Usuario", $criteria);
			//mostra erro somente se o novo email pertencer a outro usuario e nao a si mesmo
			if($user->IdUsuario != $this->IdUsuario)			
				$this->AddValidationError('Email','O e-mail inserido já está sendo utilizado');
		} catch(Exception $ex) {
		}
		
		//Valida se usuario ja existe pelo nome de usuario
		try {
			$criteria = new UsuarioCriteria();
			$criteria->Login_Equals = $this->Login;
		
			$user = $this->_phreezer->GetByCriteria("Usuario", $criteria);	
			
			//mostra erro somente se o novo usuario pertencer a outro usuario e nao a si mesmo
			if($user->IdUsuario != $this->IdUsuario)
				$this->AddValidationError('Login','O usuário inserido já está sendo utilizado');
		} catch(Exception $ex) {
		}
		
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
		if (!$this->Validate()) throw new Exception('Unable to Save Usuario: ' .  implode(', ', $this->GetValidationErrors()));

		// WE NEVER WANT TO SAVE THE PASSWORD FIELD AS PLAIN TEXT.  SO, WE'LL DO A CHECK TO MAKE
		// SURE IT IS ENCRYPTED AND, IF NOT, THEN WE WILL ENCRYPT IT.  HOWEVER IT IS IMPORTANT
		// THAT WE DON'T DOUBLE-ENCRYPT THE PASSWORD SO WE NEED SOME WAY TO INDICATE WHETHER THE 
		// PASSWORD IS ALREADY HASHED OR NOT.  JUST AS AN EXAMPLE, WE'LL PREFIX IT WITH "!!!"
		// WHEN WE HASH THE PASSWORD.  WE'LL JUST IGNORE THAT PREFIX IN THE LOGIN FUNCTION.
		// FEEL FREE TO CHANGE THAT MECHANISM TO WHATEVER WORKS FOR YOUR OWN SYSTEM
		if (substr($this->Senha, 0,3) != '!!!')
		{
			// the password is in plain-text, so we need to hash it before saving
			$this->Senha = '!!!' . password_hash($this->Senha, PASSWORD_DEFAULT);
		}
		
		// OnSave must return true or eles Phreeze will cancel the save operation
		return true;
	}

}

?>