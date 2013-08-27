<?php


class MySQL extends Dewep_MySQL { };
class User extends Dewep_User { };


class Module_Common_Global extends Shape_Core
{
	public function init_global()
	{
		//User::connectSession();
		User::connect('root', 'root');
		User::get();
	}

	public function inter_global()
	{
	}

	public function end_global()
	{
	}
}


?>