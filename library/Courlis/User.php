<?php


class Courlis_User extends Dewep_User
{
	public static function disable($id = 0)
	{
		MySQL::exec("UPDATE `user`
			SET `disabled` = 1,
				`id_modifier` = '".MySQL::escape(self::$id_real)."',
				`mtime` = NOW()
			WHERE
				`id` = '".MySQL::escape(($id ? $id : self::$id))."';");
	}

	public static function setConfirm($etat = 0, $id = 0)
	{
		MySQL::exec("UPDATE `user`
			SET `confirm` = ".intval($etat).",
				`id_modifier` = '".MySQL::escape(self::$id_real)."',
				`mtime` = NOW()
			WHERE
				`id` = '".MySQL::escape(($id ? $id : self::$id))."'
				AND `type` = 'parent';");
	}

	public static function set($infos = array(), $id = 0)
	{
		$id = MySQL::escape(($id) ? $id : self::$id);
		$infos['id_modifier'] = self::getRealId();
		$infos['mtime'] = new MySQL_Expr('NOW()');
		if (isset($infos['password']))
			$infos['password'] = User::getHash($infos['password']);
		return MySQL::update('user', $infos, "`id` = '$id'");
	}

	public static function canUseMail($mail, $id = 0)
	{
		$id = MySQL::escape(($id) ? $id : self::$id);
		$res = MySQL::query("SELECT COUNT(`id`) AS 'count' FROM `user` WHERE `id` != '$id' AND `mail` = '".MySQL::escape($mail)."' AND `disabled` = 0;");
		if (!$res || (!($res = $res->fetch())))
			return false;
		return ($res->count ? false : true);
	}

	public static function getAll($type = false, $confirm = false)
	{
		$res = MySQL::query("SELECT
				`user`.`id`,
				`user`.`login`,
				`user`.`name`,
				`user`.`firstname`,
				`user`.`mail`,
				`user`.`type`,
				`user`.`confirm`
			FROM `user`
			WHERE
				`user`.`disabled` = 0
				" . ($type ? ("AND `user`.`type` = '" . MySQL::escape($type) . "'") : '') . "
				" . ($confirm ? 'AND `user`.`confirm` = 1' : '') . "
				;");
		return $res->fetchAll();
	}
}


?>