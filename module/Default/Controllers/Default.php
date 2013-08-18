<?php


class Module_Default_Default extends Module_Default
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
		$this->view->test = 'toto';
		$this->view->salut = 'coucou';
		$this->view->list = array('1', '2' => 5);
		Shape::getConf('bdd', 'password');
		Dewep_MySQL::connect();
	}
}


?>