<?php


class Module_Default_MentionsLegales extends Module_Default
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
		$this->view->title = 'Mentions Légales';
		$this->view->subtitle = 'Les Courlis';
	}
}


?>