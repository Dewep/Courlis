<?php


class Dewep_User
{
	static $id = 0;
	static $id_real = 0;
	static $users = array();

	public static function getId()
	{
		return self::$id;
	}

	public static function getRealId()
	{
		return self::$id_real;
	}

	public static function getLogin($id = null)
	{
		$id = ($id) ? $id : self::$id;
		if (!isset(self::$users[$id]))
			self::get($id);
		return (self::$users[$id]->login);
	}

	public static function getString($id = null)
	{
		$id = ($id) ? $id : self::$id;
		if (!isset(self::$users[$id]))
			self::get($id);
		return (self::$users[$id]->string);
	}

	public static function logas($id)
	{
		$real_id = self::$id_real ? self::$id_real : self::$id;
		if (!isset(self::$users[$real_id]))
			self::get($real_id);
		if (!self::$users[$real_id]->rights->logas)
			throw new Exception("Vous n'avez pas les permissions pour logas.");
		self::$id_real = $real_id;
		self::$id = $id;
		$_SESSION['User']['Logas'] = $id;
	}

	public static function connectSession()
	{
		if (!isset($_SESSION['User']))
			return false;
		if (isset($_SESSION['User']['Id']))
		{
			$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
			if (!MySQL::exec("UPDATE `user`
					SET `last_ip` = ".(($ip) ? ("'".MySQL::escape($ip)."'") : 'NULL').",
						`atime` = NOW(),
						`pages_views` = (`pages_views` + 1)
					WHERE `id` = '".MySQL::escape($_SESSION['User']['Id'])."'"))
				throw new Exception("Impossible de trouver ce compte.");
			self::$id = $_SESSION['User']['Id'];
			if (isset($_SESSION['User']['Logas']))
			{
				self::$id_real = self::$id;
				self::$id = $_SESSION['User']['Logas'];
			}
			return true;
		}
		return false;
	}

	public static function logout()
	{
		unset($_SESSION['User']);
		self::$id = 0;
		self::$id_real = 0;
		return true;
	}

	public static function connect($identifiant, $password)
	{
		// @WARNING : Mot de passe en dur autorisé seulement pour les tests !
		$type = filter_var($identifiant, FILTER_VALIDATE_EMAIL) ? 'mail' : 'login';
		$result = MySQL::query("SELECT `user`.`id`, `user`.`login`, `user`.`disabled`, `user`.`confirm`
			FROM `user`
			WHERE
				`user`.`$type` = '".MySQL::escape($identifiant)."'
				AND (`user`.`password` = '".MySQL::escape(sha1(Shape::getConf('security', 'salt') . $password))."' OR `user`.`password` = '".MySQL::escape($password)."')
			LIMIT 1;");
		$result = $result->fetch();
		if (!$result || !$result->id || !$result->login)
			throw new Exception("Échec lors de l'identification.");
		if (!$result->confirm)
			throw new Exception("Ce compte n'a pas encore été confirmé par un administrateur.");
		if ($result->disabled)
			throw new Exception("Échec lors de l'identification.");
		self::$id = $result->id;
		$_SESSION['User'] = array('Id' => $result->id);
		return $result->id;
	}

	public static function get($id = 0)
	{
		$id = ($id) ? $id : User::getId();

		if (isset(self::$users[$id]))
			return self::$users[$id];

		$result = MySQL::query("SELECT
				`user`.`name`,
				`user`.`firstname`,
				`user`.`login`,
				`user`.`mail`,
				`user`.`type`,
				`user`.`confirm`
			FROM `user`
			WHERE
				`user`.`id` = '".MySQL::escape($id)."'
				AND `user`.`disabled` = 0
			LIMIT 1;");
		$result = $result->fetch();
		if (!$result || !$result->name)
			throw new Exception("Impossible de trouver ce compte.");

		$rights = MySQL::query("SELECT
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

		$result->string = $result->login;
		if ($result->name && $result->firstname)
			$result->string = $result->name . ' ' . $result->firstname;
		else if ($result->name)
			$result->string = $result->name;
		else if ($$result->firstname)
			$result->string = $result->firstname;

		self::$users[$id] = $result;

		return self::$users[$id];
	}
}


?>