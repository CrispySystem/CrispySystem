CREATE TABLE `admins` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`active` tinyint(1) NOT NULL DEFAULT '0',
	`email` varchar(80) NOT NULL,
	`password` varchar(60),
	`password_reset_token` varchar(60),
	`login_tries` tinyint(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
);

CREATE TABLE `admin_roles` (
	`id` int NOT NULL AUTO_INCREMENT,
	`active` tinyint(1) NOT NULL DEFAULT '0',
	`name` varchar(60) NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `admin_role_permissions` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`active` tinyint(1) NOT NULL DEFAULT '0',
	`name` varchar(60) NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `admins_to_admin_roles` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`active` tinyint(1) NOT NULL DEFAULT '0',
	`admin_id` int(11) NOT NULL,
	`admin_role_id` int(11) NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `admin_roles_to_admin_role_permissions` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`active` tinyint(1) NOT NULL DEFAULT '0',
	`admin_role_id` int(11) NOT NULL,
	`admin_role_permission_id` int(11) NOT NULL,
	PRIMARY KEY (`id`)
);

/**
CONSTRAINTS
**/

ALTER TABLE `admins_to_admin_roles` ADD CONSTRAINT `admins_to_admin_roles_fk0` FOREIGN KEY (`admin_id`) REFERENCES `admins`(`id`);

ALTER TABLE `admins_to_admin_roles` ADD CONSTRAINT `admins_to_admin_roles_fk1` FOREIGN KEY (`admin_role_id`) REFERENCES `admin_roles`(`id`);

ALTER TABLE `admin_roles_to_admin_role_permissions` ADD CONSTRAINT `admin_roles_to_admin_role_permissions_fk0` FOREIGN KEY (`admin_role_id`) REFERENCES `admin_roles`(`id`);

ALTER TABLE `admin_roles_to_admin_role_permissions` ADD CONSTRAINT `admin_roles_to_admin_role_permissions_fk1` FOREIGN KEY (`admin_role_permission_id`) REFERENCES `admin_role_permissions`(`id`);
