<?php


class Dewep_User
{
	public static function getId($set = null)
	{
		static $id = 0;
		if ($set)
			$id = $set;
		return $id;
	}

	public static function getLogin($set = null)
	{
		static $login = '';
		if ($set)
			$login = $set;
		return $login;
	}

	public static function connect($login, $password)
	{
		$result = MySQL::get()->query("SELECT `user`.`id`, `user`.`login`
			FROM `user`
			WHERE
				`user`.`login` = '".MySQL::escape($login)."'
				AND `user`.`password` = '".MySQL::escape(sha1(Shape::getConf('security', 'salt') . $password))."'
				AND `user`.disabled = 0
				AND `user`.`confirm` = 1
			LIMIT 1;");
		$result = $result->fetch();
		if (!$result || !$result->id || !$result->login)
			throw new Exception("Identifiants incorrects.");
		User::getId($result->id);
		User::getLogin($result->login);
		return $result->id;
	}

	public static function get($login = "")
	{
		static $cache = array();
		$login = ($login) ? $login : User::getLogin();

		if (isset($cache[$login]))
			return $cache[$login];

		$result = MySQL::get()->query("SELECT
				`user`.`name`,
				`user`.`firstname`,
				`user`.`mail`,
				`user`.`type`,
				`user`.`confirm`
			FROM `user`
			WHERE
				`user`.`login` = '".MySQL::escape($login)."'
				AND `user`.`disabled` = 0
			LIMIT 1;");
		$result = $result->fetch();
		if (!$result || !$result->name)
			throw new Exception("Impossible de trouver ce compte.");

		$rights = MySQL::get()->query("SELECT
				`right`.`name`,
				" . (($result->type == 'root') ? "1" : "(CASE WHEN `user_right`.`id_user` IS NOT NULL THEN 1 ELSE 0 END)") . " AS 'value'
			FROM `right`
				LEFT JOIN `user_right` ON `right`.`id` = `user_right`.`id_right` AND `user_right`.`id_user` = '".MySQL::escape(User::getId())."'
			WHERE
				`user_right`.`id_user` IS NOT NULL
				OR '".MySQL::escape($result->type)."' = 'root';");
		$result->rights = new stdClass();
		foreach ($rights as $right)
		{
			$result->rights->{$right->name} = ($right->value == '1') ? true : false;
		}
		$cache[$login] = $result;

		return $cache[$login];
	}
}


?>