<?php


class Module_Default_Default extends Module_Default
{
	public function init_controller()
	{
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
		$this->view->title = 'Les Courlis';
		$this->view->subtitle = 'Villers-Lès-Luxeuil';
	}
}


?>