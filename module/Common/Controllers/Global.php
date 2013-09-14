<?php


class MySQL extends Dewep_MySQL { };
class MySQL_Expr extends Dewep_MySQL_Expr { };
class User extends Courlis_User { };


class Module_Common_Global extends Shape_Core
{
	public function init_global()
	{
		User::connectSession();
		if (!isset($_GET['format']))
		{
			$this->view->title = 'Les Courlis';
			$this->view->subtitle = 'Villers-Lès-Luxeuil';
		}

		if (!Dewep_CSRF::check())
			$this->alert('Token invalide', 'Vous avez été inactif pendant trop longtemps. Pour des raisons de sécurité, votre requête a été rejetée.', 'error', false);
		return true;
	}

	public function alert($title, $content, $type = 'error', $force = true)
	{
		$alert = new stdClass();
		$alert->title = $title;
		$alert->content = $content;
		$alert->type = $type;
		$alert->force = $force;
		$this->view->alert = $alert;
		return ($type == 'success' ? true : false);
	}

	public function alertOverride($title = null, $content = null, $type = null, $force = null)
	{
		if (!$this->view->alert)
			return false;
		if ($title !== null)
			$this->view->alert->title = $title;
		if ($content !== null)
			$this->view->alert->content = $content;
		if ($type !== null)
			$this->view->alert->type = $type;
		if ($force !== null)
			$this->view->alert->force = $force;
		return true;
	}

	public function alertAppend($title = null, $content = null, $type = null, $force = null)
	{
		if (!$this->view->alert)
			return false;
		if ($title !== null)
			$this->view->alert->title .= $title;
		if ($content !== null)
			$this->view->alert->content .= $content;
		if ($type !== null)
			$this->view->alert->type .= $type;
		if ($force !== null)
			$this->view->alert->force .= $force;
		return true;
	}

	public function inter_global()
	{
		if (isset($_GET['format']) && $_GET['format'] == 'json')
		{
			$this->setView('json');
			if (isset($this->view->alert) && $this->view->alert->type == 'error')
			{
				$this->view->error = $this->view->alert->title;
				$this->view->details = $this->view->alert->content;
			}
			else if (isset($this->view->alert))
			{
				$this->view->success = $this->view->alert->title;
				$this->view->details = $this->view->alert->content;
			}
			if (isset($this->view->error))
			{
				header('500 Internal Server Error', true, 500);
				if (!isset($this->view->details))
				{
					$this->view->details = $this->view->error;
					$this->view->error = 'Erreur';
				}
				foreach ($this->view as $key => $value) {
					if (!in_array($key, array('error', 'details')))
						unset($this->view->$key);
				}
			}
			if (isset($this->view->success))
			{
				if (!isset($this->view->details))
				{
					$this->view->details = $this->view->success;
					$this->view->success = 'Succès';
				}
			}
		}
	}

	public function end_global()
	{
	}
}


?>