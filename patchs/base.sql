-- 2013-08-25 21:00:00 : Base de la BDD

CREATE TABLE IF NOT EXISTS `activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `nb_max_registrations` int(11) DEFAULT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `details` text COLLATE utf8_bin NOT NULL,
  `age_min` int(11) DEFAULT NULL,
  `age_max` int(11) DEFAULT NULL,
  `type_callback` enum('repas','normal','sans') COLLATE utf8_bin NOT NULL,
  `id_creator` int(11) NOT NULL,
  `time_create` datetime NOT NULL,
  `id_modifier` int(11) DEFAULT NULL,
  `mtime` datetime DEFAULT NULL,
  `disabled` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `activity_registration` (
  `id_child` int(11) NOT NULL,
  `id_activity` int(11) NOT NULL,
  `id_modifier` int(11) NOT NULL,
  `mtime` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `content` text COLLATE utf8_bin NOT NULL,
  `id_creator` int(11) NOT NULL,
  `time_create` datetime NOT NULL,
  `id_modifier` int(11) DEFAULT NULL,
  `mtime` datetime DEFAULT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `child` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `firstname` varchar(50) COLLATE utf8_bin NOT NULL,
  `birthday` date NOT NULL,
  `mtime` datetime DEFAULT NULL,
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `cron` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_parent` int(11) DEFAULT NULL,
  `type` varchar(40) COLLATE utf8_bin NOT NULL,
  `subtype` varchar(40) COLLATE utf8_bin DEFAULT NULL,
  `id_reference` int(11) DEFAULT NULL,
  `time_create` datetime NOT NULL,
  `id_creator` int(11) NOT NULL,
  `time_to_exec` datetime NOT NULL,
  `time_exec` datetime DEFAULT NULL,
  `params` text COLLATE utf8_bin,
  `etat` enum('wait','in_process','suspend','canceled','failed','successed') COLLATE utf8_bin NOT NULL,
  `result` text COLLATE utf8_bin,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `right` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_bin NOT NULL,
  `help` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=13 ;

INSERT INTO `right` (`id`, `name`, `help`) VALUES
(1, 'manage_blog', 'Gérer les articles du Blog'),
(2, 'manage_files', 'Pouvoir gérer les fichiers'),
(3, 'manage_quick_info', 'Gérer les infos rapides'),
(4, 'manage_admin', 'Gérer les admins'),
(5, 'manage_parent', 'Gérer les parents'),
(6, 'manage_activity', 'Gérer les activités'),
(7, 'promote_activitiy', 'Promouvoir les activités'),
(8, 'confirm_user', 'Confirmer les nouveaux comptes'),
(9, 'logas', 'Logas sur les parents'),
(10, 'send_mail', 'Envoyer des mails'),
(11, 'edit_footer_mail', 'Modifier le footer des mails'),
(12, 'manage_cron', 'Gérer les tâches cron');

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `firstname` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `mail` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `login` varchar(40) COLLATE utf8_bin NOT NULL,
  `password` varchar(40) COLLATE utf8_bin NOT NULL,
  `ip_registration` varchar(40) COLLATE utf8_bin DEFAULT NULL,
  `time_registration` datetime NOT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin DEFAULT NULL,
  `atime` datetime DEFAULT NULL,
  `id_modifier` int(11) DEFAULT NULL,
  `mtime` datetime DEFAULT NULL,
  `type` enum('parent','admin','root') COLLATE utf8_bin NOT NULL,
  `confirm` tinyint(1) NOT NULL DEFAULT '0',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

INSERT INTO `user` (`id`, `name`, `firstname`, `mail`, `login`, `password`, `ip_registration`, `time_registration`, `last_ip`, `atime`, `id_modifier`, `mtime`, `type`, `confirm`, `disabled`) VALUES
(1, NULL, NULL, NULL, 'root', '3685a8ae01af1984367ce7dc320db181cf36c1a3', '127.0.0.1', '2013-08-25 18:09:26', NULL, NULL, NULL, NULL, 'root', 1, 0);

CREATE TABLE IF NOT EXISTS `user_right` (
  `id_user` int(11) NOT NULL,
  `id_right` int(11) NOT NULL,
  `id_modifier` int(11) NOT NULL,
  `mtime` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

