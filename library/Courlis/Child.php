<?php


class Courlis_Child
{
	public static function get($id = 0)
	{
		$id = ($id) ? $id : User::getId();
		return MySQL::query("SELECT
				`id`,
				`name`,
				`firstname`,
				`birthday`
			FROM `child`
			WHERE `user_id` = '".$id."' AND `disabled` = 0");
	}

	public static function set($infos = array(), $id = 0)
	{
		$id = ($id) ? $id : User::getId();
		$infos['id_modifier'] = self::getRealId();
		$infos['mtime'] = new MySQL_Expr('NOW()');
		return MySQL::update('child', $infos, "`id_user` = '$id' AND `disabled` = 0");
	}

	public static function delete($id_child, $id = 0)
	{
		$id = ($id) ? $id : User::getId();
		$infos = array();
		$infos['id_modifier'] = User::getRealId();
		$infos['mtime'] = new MySQL_Expr('NOW()');
		$infos['disabled'] = '1';
		if (!(MySQL::update('child', $infos, "`id` = '$id_child' AND `user_id` = '$id'")))
			throw new Exception("Vous n'avez pas les permissions pour supprimer cet enfant.");
		return true;			
	}

	public static function create($infos = array(), $id = 0)
	{
		$id = ($id) ? $id : User::getId();
		$infos['user_id'] = $id;
		$infos['id_modifier'] = User::getRealId();
		$infos['mtime'] = new MySQL_Expr('NOW()');
		return MySQL::insert('child', array($infos));
	}
}


?>