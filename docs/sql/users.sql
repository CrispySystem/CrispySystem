CREATE TABLE `users` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`active` tinyint(1) NOT NULL DEFAULT '0',
	`email` varchar(80) NOT NULL,
	`password` varchar(60),
	`password_reset_token` varchar(60),
	`login_tries` tinyint(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
);

CREATE TABLE `user_roles` (
	`id` int NOT NULL AUTO_INCREMENT,
	`active` tinyint(1) NOT NULL DEFAULT '0',
	`name` varchar(60) NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `user_role_permissions` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`active` tinyint(1) NOT NULL DEFAULT '0',
	`name` varchar(60) NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `users_to_user_roles` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`active` tinyint(1) NOT NULL DEFAULT '0',
	`user_id` int(11) NOT NULL,
	`user_role_id` int(11) NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `user_roles_to_user_role_permissions` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`active` tinyint(1) NOT NULL DEFAULT '0',
	`user_role_id` int(11) NOT NULL,
	`user_role_permission_id` int(11) NOT NULL,
	PRIMARY KEY (`id`)
);

/**
CONSTRAINTS
**/

ALTER TABLE `users_to_user_roles` ADD CONSTRAINT `users_to_user_roles_fk0` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`);

ALTER TABLE `users_to_user_roles` ADD CONSTRAINT `users_to_user_roles_fk1` FOREIGN KEY (`user_role_id`) REFERENCES `user_roles`(`id`);

ALTER TABLE `user_roles_to_user_role_permissions` ADD CONSTRAINT `user_roles_to_user_role_permissions_fk0` FOREIGN KEY (`user_role_id`) REFERENCES `user_roles`(`id`);

ALTER TABLE `user_roles_to_user_role_permissions` ADD CONSTRAINT `user_roles_to_user_role_permissions_fk1` FOREIGN KEY (`user_role_permission_id`) REFERENCES `user_role_permissions`(`id`);
