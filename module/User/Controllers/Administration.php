<?php


class Module_User_Administration extends Module_User
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
		$this->view->title = 'Administration';
		$this->view->subtitle = 'Les Courlis';
	}
}


?>