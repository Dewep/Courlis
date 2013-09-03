<?php

/*
> index : Gérer son compte
*/

class Module_User_Preferences extends Module_User
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

	public function indexAction()
	{
		$this->view->title = 'Gérer son compte';
		$this->view->subtitle = 'Les Courlis';

		if (!User::getId())
			return $this->alert('Accès restreint', 'Vous ne pouvez pas accéder à cette page, vous n\'êtes pas connecté.');
		foreach ($_POST as $key => $value) {
			$_POST[$key] = trim($_POST[$key]);
		}
		$mustReconfirm = false;
		if (isset($_POST['child_name']) && isset($_POST['child_firstname']) && isset($_POST['child_birthday']))
		{
			try
			{
				if (!$_POST['child_name'] || !$_POST['child_firstname'] || !$_POST['child_birthday'])
					throw new Exception("Merci de compléter tous les champs pour ajouter un enfant.");
				if (!preg_match('#([0-9]{2})/([0-9]{2})/([0-9]{4})#', $_POST['child_birthday'], $matches) || count($matches) != 4)
					throw new Exception("Format de la date d'anniversaire incorrect. Merci de respecter le format suivant : 23/11/1998.");
				$birthday = $matches[3] . '-' . $matches[2] . '-' . $matches[1];
				Courlis_Child::create(array(
						'name' => $_POST['child_name'],
						'firstname' => $_POST['child_firstname'],
						'birthday' => $birthday
					));
				$this->alert('Vos enfants', 'Votre enfant a bien ete modifie.', 'success', false);
				$mustReconfirm = true;
			}
			catch (Exception $e)
			{
				$this->alert('Impossible d\'ajouter cet enfant', $e->getMessage(), 'error', false);
			}
		}
		if (isset($_POST['delete_child']) && isset($_POST['child']))
		{
			try
			{
				Courlis_Child::delete($_POST['child']);
				$this->alert('Vos enfants', 'Votre enfant a bien ete supprime.', 'success', false);
				$mustReconfirm = true;
			}
			catch (Exception $e)
			{
				$this->alert('Impossible de supprimer cet enfant', $e->getMessage(), 'error', false);
			}
		}
		if (isset($_POST['compte_name']))
		{
			$data = array();
			if (isset($_POST['compte_name']) && $_POST['compte_name'] && User::get()->name != $_POST['compte_name'])
				$data['name'] = $_POST['compte_name'];
			if (isset($_POST['compte_firstname']) && $_POST['compte_firstname'] && User::get()->firstname != $_POST['compte_firstname'])
				$data['firstname'] = $_POST['compte_firstname'];
			if (isset($_POST['compte_mail']) && $_POST['compte_mail'] && User::get()->mail != $_POST['compte_mail'])
				$data['mail'] = $_POST['compte_mail'];
			if (isset($_POST['compte_pass']) && $_POST['compte_pass'])
				$data['password'] = $_POST['compte_pass'];

			if (isset($data['mail']) && !filter_var($data['mail'], FILTER_VALIDATE_EMAIL))
				$this->alert('Votre compte', 'Votre adresse E-Mail est invalide.', 'error', false);
			else if (isset($data['password']) && (strlen($data['password']) < 8 || !preg_match('#[0-9]#', $data['password']) || !preg_match('#[a-z]#i', $data['password'])))
				$this->alert('Votre compte', 'Votre mot de passe doit faire au moins 8 caractères, et contenir au minimum une lettre et un chiffre.', 'error', false);
			else if (isset($data['mail']) && !User::canUseMail($data['mail']))
				$this->alert('Votre compte', 'Cet E-Mail est déjà utilisé par un autre compte.', 'error', false);
			else
			{
				if (count($data))
					User::set($data);
				if (isset($data['name']) || isset($data['firstname']))
					$mustReconfirm = true;
				$this->alert('Votre compte', 'Informations enregistrées.', 'success', false);
			}
		}
		if ($mustReconfirm && !User::isLogas())
		{
			User::setConfirm(0);
			$this->alertOverride(null, null, 'warning');
			$this->alertAppend(null, '<br>Votre compte doit cependant être reconfirmé par un administrateur avant de pouvoir effectuer des actions avec.');
		}
		$this->view->posts = array(
				'compte_name' => (isset($_REQUEST['compte_name']) ? $_REQUEST['compte_name'] : User::get()->name),
				'compte_firstname' => (isset($_REQUEST['compte_firstname']) ? $_REQUEST['compte_firstname'] : User::get()->firstname),
				'compte_mail' => (isset($_REQUEST['compte_mail']) ? $_REQUEST['compte_mail'] : User::get()->mail),
				'child_name' => (isset($_REQUEST['child_name']) ? $_REQUEST['child_name'] : ''),
				'child_firstname' => (isset($_REQUEST['child_firstname']) ? $_REQUEST['child_firstname'] : ''),
				'child_birthday' => (isset($_REQUEST['child_birthday']) ? $_REQUEST['child_birthday'] : ''),
				'children' => Courlis_Child::get()
			);
	}
}


?>