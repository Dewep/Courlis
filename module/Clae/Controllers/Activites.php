<?php


class Module_Clae_Activites extends Module_Ecole
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
		$this->view->title = 'Les prochaines activités';
		$this->view->subtitle = 'Claé Les Courlis';
	}
}


?>