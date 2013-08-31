<?php


class Module_Default_Aide extends Module_Default
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
		$this->view->title = 'Pages d\'aide';
		$this->view->subtitle = 'Les Courlis';
	}
}


?>