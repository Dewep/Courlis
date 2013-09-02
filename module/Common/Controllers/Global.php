<?php


class MySQL extends Dewep_MySQL { };
class MySQL_Expr extends Dewep_MySQL_Expr { };
class User extends Courlis_User { };


class Module_Common_Global extends Shape_Core
{
	public function init_global()
	{
		User::connectSession();
		$this->view->title = 'Les Courlis';
		$this->view->subtitle = 'Villers-Lès-Luxeuil';

		if (!Dewep_CSRF::check())
			$this->alert('Token invalide', 'Vous avez été inactif pendant trop longtemps. Pour des raisons de sécurité, votre requête a été rejetée.', 'error', false);
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

	public function inter_global()
	{
	}

	public function end_global()
	{
	}
}


?>