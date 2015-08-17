<?php
/** @package    Example::App */

/** import supporting libraries */
require_once("verysimple/Authentication/IAuthenticatable.php");
require_once("util/password.php");

/** @package    Certificados FAROL::Controller */
/** import supporting libraries */
require_once("Controller/UsuarioController.php");

/**
 * The ExampleUser is a simple account object that demonstrates a simplistic way
 * to handle authentication.  Note that this uses a hard-coded username/password
 * combination (see inside the __construct method).
 * 
 * A better approach is to use one of your existing model classes and implement
 * IAuthenticatable inside that class.
 *
 * @package Example::App
 * @author ClassBuilder
 * @version 1.0
 */
class Autenticacao extends AppBaseController implements IAuthenticatable
{
	
	/**
	 * Initialize the array of users.  Note, this is only done this way because the 
	 * users are hard-coded for this example.  In your own code you would most likely
	 * do a single lookup inside the Login method
	 */
	// public function __construct()
	// {
		// if (!self::$USERS)
		// {
			// self::$USERS = Array(
				// "demo"=>password_hash("pass",PASSWORD_BCRYPT),
				// "admin"=>password_hash("pass",PASSWORD_BCRYPT)
			// );
		// }
	// }
	
	/**
	 * Override here for any controller-specific functionality
	 *
	 * @inheritdocs
	 */
	protected function Init()
	{
		parent::Init();

		// TODO: add controller-wide bootstrap code
		
		// TODO: if authentiation is required for this entire controller, for example:
		// $this->RequirePermission(ExampleUser::$PERMISSION_ADMIN,'Usuario.LoginForm');
		
		//$this->RequerPermissao(Usuario::$PERMISSION_USER,'Usuario.LoginForm');
	}
	
	/**
	 * @var Array hard-coded list user/passwords.  initialized on contruction
	 */
	static $USERS;
	
	static $PERMISSION_ADMIN = 1;
	static $PERMISSION_USER = 0;	
	
	public $Username = '';
	
	
	public function EstaLogado()
	{        		
		$criteria = new UsuarioCriteria();
        $criteria->Login_Equals = $this->GetCurrentUser()->Login;
		try {
			$user = $this->Phreezer->GetByCriteria("Usuario", $criteria);
			return true;
		} catch (NotFoundException $nfex) {
			return false;
		}
		//return print_r($u[0]->login). '   ->'. $this->GetCurrentUser()->Login.'<-';
		//if($this->Username == $u->Login) return true;
	
	
	
		// if ($this->Username == 'admin') return true;
		
		// if ($this->Username == 'demo' && $permission == self::$PERMISSION_USER) return true;
		
		return false;
	}
        
	public function RequerPermissao($permissao=0, $pagina=null){
		echo 'log'.$this->EstaLogado();
		if($this->EstaLogado()){
			echo 'LOGADO';
			echo $this->GetCurrentUser()->TipoUsuario;
			if($permissao == $this->GetCurrentUser()->TipoUsuario){ 
				echo 'UUUUUUUUSEUA:<BR> '.$this->Username.'<BR>';
				if($permissao == 0){
					if($pagina != null){
						//$this->Redirect($pagina, 'Login COMUM realizado com sucesso.');
					} else
						$this->UserPage();
				} else if($permissao == 1){
					if($pagina != null){
						//$this->Redirect($pagina, 'Login ADMINISTRADOR realizado com sucesso.');
					} else
						$this->AdminPage();
				} else {
					return false;
				}
				echo 'Permissão correta!';
			}
		}
		return false;
	}

    /**
     * Validate the given username/password.  If successful then $this becomes the
     * authenticated user and is returned
     *
     * @param string $username
     * @param string $password
     * @return Account or NULL
     */
    public function Login($username, $password) {
        $result = null;

        if ($username == "" || $password == "")
            return null;

        // to begin, query for all users with a matching username.  we cannot check at this point
        // if the password matches or not because it is crypted with a salt
        $criteria = new UsuarioCriteria();
        $criteria->Login_Equals = $username;
	
        $accounts = $this->Phreezer->Query("Usuario", $criteria)->ToObjectArray();

        // If we have any objects in this array then we know a correct username was enterd,
        // however we do not know if the password is correct until we verify the hash
        foreach ($accounts as $possibleAccount) {

            //codifica senha temporariamente
            $senha = password_hash($possibleAccount->Senha, PASSWORD_BCRYPT);

            if (password_verify($password, $senha)) {
                echo 'VERIFICA SENHA';
                
                // this is a successful login, the username and password matches.
                // what we are doing here is copying all of the properties from $possibleAccount
                // into $this.  we can't do $this = $possibleAccount because PHP would throw
                // exception. so instead we just clone all of the properties from $possibleAccount
                // into $this using a Phreezable method 'LoadFromObject'
                //$this->LoadFromObject($possibleAccount);

               $this->SetCurrentUser($possibleAccount);
			   $this->Username = 'Joao';
				
				
				$this->RequerPermissao($possibleAccount->TipoUsuario);
			   
			   //$this->RequerPermissao($possibleAccount->TipoUsuario);

                // on success this method will return a reference to itself
                $result = $this;
				
                break;
            } else {
                return false;
            }
        }

        // this will either be a reference to itself (which will evaluate as true)
        // or it will be null (which will evaluate as false)
        return $result;
    }
	
	/**
     * Process the login, create the user session and then redirect to 
     * the appropriate page
     */
    public function Autenticar()
	{
		//$autenticacao = new Autenticacao($this->Phreezer);
        if (!Autenticacao::Login(RequestUtil::Get('username'), RequestUtil::Get('password'))) {
          //Se o login falhar
		  $this->Redirect('Usuario.LoginForm', 'Usuário ou senha incorretos.');
        }
    }
	
	
	
	
	
	

        
        /**
	 * This login method uses hard-coded username/passwords.  This is ok for simple apps
	 * but for a more robust application this would do a database lookup instead.
	 * The Username is used as a mechanism to determine whether the user is logged in or
	 * not
	 * 
	 * @see IAuthenticatable
	 * @param string $username
	 * @param string $password
	 */
	// public function Login($username,$password)
	// {
		
		// funcao padrao da classe
		
		// foreach (self::$USERS as $un=>$pw)
		// {
			// if ($username == $un && password_verify($password,$pw))
			// {
				// $this->Username = $username;
				// break;
			// }
		// }
		
		// return $this->Username != '';
	// }
 
	
	
	
	
	
	
	
	
	
	

	/**
	 * Returns true if the user is anonymous (not logged in)
	 * @see IAuthenticatable
	 */
	public function IsAnonymous()
	{
		return $this->Username == '';
	}
	
	/**
	 * This is a hard-coded way of checking permission.  A better approach would be to look up
	 * this information in the database or base it on the account type
	 * 
	 * @see IAuthenticatable
	 * @param int $permission
	 */
	public function IsAuthorized($permission)
	{
		if ($this->Username == 'admin') return true;
		
		if ($this->Username == 'demo' && $permission == self::$PERMISSION_USER) return true;
		
		return false;
	}
	
	/**
	 * This login method uses hard-coded username/passwords.  This is ok for simple apps
	 * but for a more robust application this would do a database lookup instead.
	 * The Username is used as a mechanism to determine whether the user is logged in or
	 * not
	 * 
	 * @see IAuthenticatable
	 * @param string $username
	 * @param string $password
	 */
	// public function Login($username,$password)
	// {
		// foreach (self::$USERS as $un=>$pw)
		// {
			// if ($username == $un && password_verify($password,$pw))
			// {
				// $this->Username = $username;
				// break;
			// }
		// }
		
		// return $this->Username != '';
	// }
	
}

?>