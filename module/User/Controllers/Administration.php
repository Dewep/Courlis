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

		if (isset($_POST['confirm']) && isset($_POST['user_id']))
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
				$this->alert('Logas', $e->getMessage(), 'success', false);
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
}


?>