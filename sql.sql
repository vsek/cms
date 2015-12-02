# ------------------------------------------------------------- uvodni inicializace ------------------------------------------------------------------

CREATE TABLE `email` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `system_name` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `modifier` varchar(255) DEFAULT NULL,
  `subject` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `email_log` (
  `id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `adress` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `error` varchar(255) DEFAULT NULL,
  `subject` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `permission` (
  `role_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `privilege_id` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `permission` (`role_id`, `resource_id`, `privilege_id`, `id`) VALUES
(1, 6, 2, 9),
(1, 6, 3, 10),
(1, 6, 4, 11),
(1, 6, 1, 12),
(1, 12, 2, 33),
(1, 12, 3, 34),
(1, 12, 4, 35),
(1, 12, 1, 36),
(1, 13, 1, 37),
(1, 14, 2, 160),
(1, 14, 1, 161);
CREATE TABLE `privilege` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `system_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `privilege` (`id`, `name`, `system_name`) VALUES
(1, 'Zobrazení', 'default'),
(2, 'Editace', 'edit'),
(3, 'Mazání', 'delete'),
(4, 'Vytváøení', 'new'),
(5, 'Oprávnìní', 'permission'),
(6, 'Nastavit práva', 'set'),
(7, 'Detail', 'detail'),
(8, 'Log', 'log');
CREATE TABLE `resource` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `system_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `resource` (`id`, `name`, `system_name`) VALUES
(2, 'Privilegia', 'privilege'),
(3, 'Zdroje', 'resource'),
(4, 'Role', 'role'),
(6, 'Stránky', 'page'),
(12, 'Uživatelé', 'user'),
(13, 'Nastavení', 'setting'),
(14, 'Email', 'email');
CREATE TABLE `resource_privilege` (
  `resource_id` int(11) NOT NULL,
  `privilege_id` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `resource_privilege` (`resource_id`, `privilege_id`, `id`) VALUES
(2, 2, 5),
(2, 3, 6),
(2, 4, 7),
(2, 1, 8),
(3, 2, 9),
(3, 1, 12),
(4, 2, 13),
(4, 3, 14),
(4, 6, 15),
(4, 5, 16),
(4, 4, 17),
(4, 1, 18),
(3, 4, 20),
(3, 3, 21),
(6, 2, 22),
(6, 3, 23),
(6, 4, 24),
(6, 1, 25),
(12, 2, 46),
(12, 3, 47),
(12, 4, 48),
(12, 1, 49),
(13, 1, 50),
(14, 2, 193),
(14, 4, 194),
(14, 1, 195),
(14, 7, 238),
(14, 8, 239);
CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `system_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `role` (`id`, `name`, `system_name`) VALUES
(1, 'Admin', 'admin'),
(2, 'Super admin', 'super_admin');
CREATE TABLE `setting` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `google_analytics` text,
  `facebook_link` varchar(255) DEFAULT NULL,
  `twitter_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `setting` (`id`, `name`, `email`, `google_analytics`, `facebook_link`, `twitter_link`) VALUES
(1, 'WebNoLimit CMS', 'vsek@seznam.cz', NULL, NULL, NULL);
CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `surname` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `password` varchar(255) NOT NULL,
  `hash` varchar(255) DEFAULT NULL,
  `role_id` int(11) NOT NULL DEFAULT '1',
  `verified_email` datetime DEFAULT NULL,
  `new_password_hash` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `user` (`id`, `email`, `name`, `surname`, `created`, `password`, `hash`, `role_id`, `verified_email`, `new_password_hash`) VALUES
(1, 'vsek@seznam.cz', 'Václav', 'Stodùlka', '2014-04-09 15:15:51', '72e0ea0d711ba3cb1d2755193095f0fb', NULL, 2, NULL, NULL);
ALTER TABLE `email`
  ADD PRIMARY KEY (`id`);
  ALTER TABLE `email_log`
  ADD PRIMARY KEY (`id`);
  ALTER TABLE `permission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `resource_id` (`resource_id`),
  ADD KEY `privilege_id` (`privilege_id`);
ALTER TABLE `privilege`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `system_name` (`system_name`);
ALTER TABLE `resource`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `system_name` (`system_name`);
ALTER TABLE `resource_privilege`
  ADD PRIMARY KEY (`id`),
  ADD KEY `resource_id` (`resource_id`),
  ADD KEY `privilege_id` (`privilege_id`);
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`);
ALTER TABLE `email`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `email_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
ALTER TABLE `permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;
ALTER TABLE `privilege`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
ALTER TABLE `resource`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
ALTER TABLE `resource_privilege`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=240;
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
ALTER TABLE `setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `permission`
  ADD CONSTRAINT `permission_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `resource` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_ibfk_3` FOREIGN KEY (`privilege_id`) REFERENCES `privilege` (`id`) ON DELETE CASCADE;
ALTER TABLE `resource_privilege`
  ADD CONSTRAINT `resource_privilege_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resource` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `resource_privilege_ibfk_2` FOREIGN KEY (`privilege_id`) REFERENCES `privilege` (`id`) ON DELETE CASCADE;
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE;
