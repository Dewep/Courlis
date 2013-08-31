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
	}
}


?>