<?php


class Module_Ecole_Blog extends Module_Ecole
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
		$this->view->title = 'Blog';
		$this->view->subtitle = 'École Les Courlis';
	}
}


?>