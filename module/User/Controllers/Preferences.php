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
		if (isset($_POST['child_name']) && isset($_POST['child_firstname']) && isset($_POST['child_birthday']))
		{
			try
			{
				Courlis_Child::create(array(
						'name' => $_POST['child_name'],
						'firstname' => $_POST['child_firstname'],
						'birthday' => $_POST['child_birthday']
					));
				$this->alert('Vos enfants', 'Votre enfant a bien ete modifie.', 'success', false);
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
			if (count($data))
				User::set($data);
			$this->alert('Votre compte', 'Informations enregistrées.', 'success', false);
		}
		$this->view->posts = array(
				'compte_name' => (isset($_REQUEST['compte_name']) ? $_REQUEST['compte_name'] : User::get()->name),
				'compte_firstname' => (isset($_REQUEST['compte_firstname']) ? $_REQUEST['compte_firstname'] : User::get()->firstname),
				'compte_mail' => (isset($_REQUEST['compte_mail']) ? $_REQUEST['compte_mail'] : User::get()->mail),
				'child_name' => (isset($_REQUEST['child_name']) ? $_REQUEST['child_name'] : ''),
				'child_firstname' => (isset($_REQUEST['child_firstname']) ? $_REQUEST['child_firstname'] : ''),
				'child_birthday' => (isset($_REQUEST['child_birthday ']) ? $_REQUEST['child_birthday '] : ''),
				'children' => Courlis_Child::get()
			);
	}
}


?>