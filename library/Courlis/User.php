<?php


class Courlis_User extends Dewep_User
{
	public static function setConfirm($etat = 0, $id = 0)
	{
		MySQL::exec("UPDATE `user`
			SET `confirm` = ".intval($etat).",
				`id_modifier` = '".MySQL::escape(self::$id_real)."',
				`mtime` = NOW()
			WHERE `id` = '".($id ? $id : self::$id)."'");
	}

	public static function set($infos = array(), $id = 0)
	{
		$id = ($id) ? $id : self::$id;
		$infos['id_modifier'] = self::getRealId();
		$infos['mtime'] = new MySQL_Expr('NOW()');
		return MySQL::update('user', $infos, "`id` = '$id'");
	}

	public static function canUseMail($mail, $id = 0)
	{
		$id = ($id) ? $id : self::$id;
		$res = MySQL::query("SELECT COUNT(`id`) AS 'count' FROM `user` WHERE `id` != '$id' AND `mail` = '".MySQL::escape($mail)."';");
		if (!$res || (!($res = $res->fetch())))
			return false;
		return ($res->count ? false : true);
	}
}


?>