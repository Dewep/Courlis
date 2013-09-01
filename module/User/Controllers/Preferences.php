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
		if (isset($_POST['child_name']))
		{
			// On ajoute un enfant
		}
		if (isset($_POST['delete_child']))
		{
			// On supprime un enfant
		}
		if (isset($_POST['compte_name']))
		{
			// On modifie ces infos générales
		}
		$this->view->posts = array(
				'compte_name' => (isset($_REQUEST['compte_name']) ? $_REQUEST['compte_name'] : User::get()->name),
				'compte_firstname' => (isset($_REQUEST['compte_firstname']) ? $_REQUEST['compte_firstname'] : User::get()->firstname),
				'compte_mail' => (isset($_REQUEST['compte_mail']) ? $_REQUEST['compte_mail'] : User::get()->mail),
				'child_name' => (isset($_REQUEST['child_name']) ? $_REQUEST['child_name'] : ''),
				'child_firstname' => (isset($_REQUEST['child_firstname']) ? $_REQUEST['child_firstname'] : ''),
				'child_birthday' => (isset($_REQUEST['child_birthday ']) ? $_REQUEST['child_birthday '] : '')//,
				//'name' => Clae::get()
			);
	}
}


?>