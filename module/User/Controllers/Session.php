<?php

/*
> connexion : Se connecter
> deconnexion : Se déconnecter
> inscription : S'inscrire
> reset_mdp : Mot de passe oublié
*/

class Module_User_Session extends Module_User
{
	public function init_controller()
	{
	}

	public function inter_controller()
	{
	}

	public function end_controller()
	{
	}

	public function connexionAction()
	{
		$this->view->title = 'Se connecter';
		$this->view->subtitle = 'Les Courlis';

		if (User::getId())
			return $this->alert('Connexion impossible', 'Vous êtes déjà connecté.');
		if (isset($_POST['login_name']) && isset($_POST['login_pass']))
		{
			try {
				User::connect($_POST['login_name'], $_POST['login_pass']);
				if (!User::get()->confirm)
					return $this->alert('Connexion effectuée', 'Identifiants corrects. Vous êtes maintenant connecté. Cependant, votre compte doit être validé par un administrateur avant de pouvoir faire des actions avec.', 'warning');
				return $this->alert('Connexion effectuée', 'Identifiants corrects. Vous êtes maintenant connecté.', 'success');
			} catch (Exception $e) {
				$this->view->erreur_connexion = $e->getMessage();
			}
		}
	}

	public function deconnexionAction()
	{
		$this->view->title = 'Se déconnecter';
		$this->view->subtitle = 'Les Courlis';

		if (!User::getId())
			return $this->alert('Déconnexion impossible', 'Vous n\'êtes pas connecté.');
		User::logout();
		return $this->alert('Déconnexion effectuée', 'Vous êtes maintenant déconnecté.', 'success');
	}

	public function inscriptionAction()
	{
		$this->view->title = 'S\'inscrire';
		$this->view->subtitle = 'Les Courlis';
	}

	public function reset_mdpAction()
	{
		$this->view->title = 'Réinitialiser son mot de passe';
		$this->view->subtitle = 'Les Courlis';
	}
}


?>