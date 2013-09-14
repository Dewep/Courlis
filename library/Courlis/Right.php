<?php


class Courlis_Right
{
	public static function set($data, $id = 0)
	{
		$id = ($id) ? $id : User::getId();

		$desactive = array();
		$insert = array();
		foreach ($data as $right => $value) {
			if ((!$value || $value == "false") && User::get($id)->rights->$right)
				$desactive[] = $right;
			else if ($value && $value != "false" && !User::get($id)->rights->$right)
				$insert[] = array(
						'id_user' => $id,
						'id_right' => new MySQL_Expr("(SELECT `id` FROM `right` WHERE `name` = '" . MySQL::escape($right) . "')")
					);
		}

		if (count($desactive))
			MySQL::exec("DELETE `user_right` FROM `user_right`
				JOIN `right`
					ON `right`.`id` = `user_right`.`id_right`
				WHERE
					`user_right`.`id_user` = $id
					AND `right`.`name` IN (" . MySQL::escapeArray($desactive) . ");");
		if (count($insert))
			MySQL::insert("user_right", $insert);
	}

	public static function get($id = 0)
	{
		$id = ($id) ? $id : User::getId();

		$rights = MySQL::query("SELECT
				`right`.`name`,
				" . ((User::get($id)->type != 'admin') ? ((User::get($id)->type == 'root') ? "1" : "0") : "(CASE WHEN `user_right`.`id_user` IS NOT NULL THEN 1 ELSE 0 END)") . " AS 'value'
			FROM `right`
				LEFT JOIN `user_right` ON `right`.`id` = `user_right`.`id_right` AND `user_right`.`id_user` = '".MySQL::escape($id)."';");
		$result = new stdClass();
		foreach ($rights as $right)
			$result->{$right->name} = ($right->value == '1') ? true : false;
		return $result;
	}

	public static function definitions()
	{
		$query = MySQL::query("SELECT
				-- `id`,
				`name`,
				`help`
			FROM `right`;");
		return $query->fetchAll();
	}
}


?>