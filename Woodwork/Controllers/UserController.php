<?php 
use Woodwork\Core\Application;

class UserController extends \Woodwork\Core\Controller {

	public function login()
	{
		$post = $this->getRequest()->getPostParams();

		if (isset($post['login']))
		{
			$login = &$post['login'];
			if (!empty($login['email']) && !empty($login['password']))
			{

				$salt = Application::getConfig()->password_salt;
				$hashword = md5($salt . $login['password']);
				$user = User::get( $login['email'], 'email' );

				if ($user && $user->password == $hashword)
				{
					$this->getRequest()->setUser( $user );
				}

				$this->redirect( 'results' );
			}
		}
	}

	public function logout()
	{
		$this->getRequest()->setUser(null);
		$this->redirect();
	}

	public function register()
	{
		$post = $this->getRequest()->getPostParams();

		$errors = array();

		if ( isset($post['register']) )
		{
			$register = &$post['register'];
			if (empty($register['firstname']))
			{
				$errors[] = "First name is required";
			}
			if (empty($register['lastname']))
			{
				$errors[] = "Last name is required";
			}
			if (empty($register['email']))
			{
				$errors[] = "Email address is required";
			}
			if (empty($register['password']))
			{
				$errors[] = "Password is required";
			}
	
			$user = User::get( $register['email'], 'email' );
			if ($user)
			{
				$errors[] = "Email already exists";
			}

			if (empty($errors))
			{

				$salt = Woodwork\Application::getConfig()->password_salt;

				$user = new User( array(
					'first_name' => $user['firstname'], 
					'last_name' => $user['lastname'],
					'email' => $user['email'],
					'password' => md5($salt . $user['password'])
				));

				$result = $user->save();

				if ( $result )
				{
					$this->getRequest()->setUser($user);
					$this->redirect('results');
				}
				else
				{
					// noooooo!!!!
				}
			}
		}

		if (!empty($errors))
		{
			$this->setFlashMessage( implode('<br />', $errors) );
		}

		$this->redirect();
	}

}