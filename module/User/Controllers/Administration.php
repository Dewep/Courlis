<?php


class Module_User_Administration extends Module_User
{
	public function init_controller()
	{
		if (!User::getId() || (User::get()->type != 'admin' && User::get()->type != 'root'))
		{
			$this->alert('Accès restreint', 'Vous n\'avez pas les permissions pour accéder à cette page.');
			return false;
		}
		return true;
	}

	public function inter_controller()
	{
	}

	public function end_controller()
	{
	}

	public function indexAction()
	{
		$this->view->title = 'Administration';
		$this->view->subtitle = 'Les Courlis';

		if (isset($_POST['delete_user']) && isset($_POST['user_id']))
		{
			try
			{
				if ((User::get($_POST['user_id'])->type == 'parent' && !User::get()->rights->manage_parent)
					|| (User::get($_POST['user_id'])->type == 'admin' && !User::get()->rights->manage_admin)
					|| (User::get($_POST['user_id'])->type == 'root'))
					throw new Exception("Vous n'avez pas les permissions nécessaires pour supprimer cet utilisateur.");
				User::disable($_POST['user_id']);
				$this->alert('Suppression', 'Le compte a bien été supprimé.', 'success', false);
			}
			catch (Exception $e)
			{
				$this->alert('Suppression', $e->getMessage(), 'error', false);
			}
		}
		else if (isset($_POST['confirm']) && isset($_POST['user_id']))
		{
			User::setConfirm(1, $_POST['user_id']);
			$this->alert('Confirmer', 'Le compte a été confirmé.', 'success', false);
		}
		else if (isset($_POST['logas']) && isset($_POST['user_id']))
		{
			try
			{
				User::logas($_POST['user_id']);
				header('Location: ' . $this->baseUrl(), 301);
				echo 'Move to ' . $this->baseUrl();
				exit(301);
			}
			catch (Exception $e)
			{
				$this->alert('Logas', $e->getMessage(), 'error', false);
			}
		}

		$users = User::getAll();
		$this->view->users = array(
				'confirm' => array(),
				'parents' => array(),
				'admin' => array()
			);
		foreach ($users as $user) {
			if ($user->confirm == 0)
				$this->view->users['confirm'][] = $user;
			if ($user->type == 'parent')
				$this->view->users['parents'][] = $user;
			if ($user->type == 'admin')
				$this->view->users['admin'][] = $user;
		}
	}

	public function getUserAction()
	{
		if (!isset($_POST['id']))
		{
			$this->view->error = 'ID incorrect.';
			return ;
		}

		try
		{
			$user = User::get(intval($_POST['id']));
			foreach ($user as $key => $value) {
				$this->view->$key = $value;
			}
			$this->view->rights_definitions = new stdClass();
			foreach (Courlis_Right::definitions() as $value) {
				$this->view->rights_definitions->{$value->name} = $value->help;
			}
		}
		catch (Exception $e)
		{
			$this->view->error = $e->getMessage();
		}
	}

	public function setUserAction()
	{
		if (!isset($_POST['id']))
		{
			$this->view->error = 'ID incorrect.';
			return false;
		}

		$id = intval($_POST['id']);
		$data = array();
		if (isset($_POST['name']) && $_POST['name'] && User::get($id)->name != $_POST['name'])
			$data['name'] = $_POST['name'];
		if (isset($_POST['firstname']) && $_POST['firstname'] && User::get($id)->firstname != $_POST['firstname'])
			$data['firstname'] = $_POST['firstname'];
		if (isset($_POST['mail']) && $_POST['mail'] && User::get($id)->mail != $_POST['mail'])
			$data['mail'] = $_POST['mail'];

		try
		{
			if (isset($data['mail']) && !filter_var($data['mail'], FILTER_VALIDATE_EMAIL))
				throw new Exception('L\'adresse E-Mail est invalide.');
			if (isset($data['mail']) && !User::canUseMail($data['mail'], $id))
				throw new Exception('L\'adresse E-Mail est déjà utilisé par un autre compte.');
			if (isset($_POST['rights']) && is_array($_POST['rights']))
				Courlis_Right::set($_POST['rights'], $id);
			if (count($data))
				User::set($data, $id);
			$this->alert('Éditer l\'utilisateur', 'Informations enregistrées.', 'success');
		}
		catch (Exception $e)
		{
			$this->alert('Éditer l\'utilisateur', $e->getMessage());
		}
	}
}


?>