ALTER TABLE calendar1_event_date_participation ADD guildMemberID INT(10) DEFAULT NULL;
ALTER TABLE calendar1_event_date_participation ADD guildRoleID INT(10) DEFAULT NULL;

DROP TABLE IF EXISTS guild1_avatar;
CREATE TABLE guild1_avatar (
	avatarID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	gameID INT(10) DEFAULT NULL,
	name VARCHAR(100) NOT NULL DEFAULT '',
	image VARCHAR(100) NOT NULL DEFAULT '',
	autoAssignment INT(10) NOT NULL DEFAULT '0',
	isActive tinyint(1) NOT NULL DEFAULT '0',
	UNIQUE KEY (gameID, name)
);
ALTER TABLE guild1_avatar ADD INDEX isActive (isActive);

DROP TABLE IF EXISTS guild1_game;
CREATE TABLE guild1_game (
	gameID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(50) NOT NULL DEFAULT '',
	tag VARCHAR(10) NOT NULL DEFAULT '',
	apiClass VARCHAR(100) NOT NULL DEFAULT '',
	apiKey VARCHAR(100) NOT NULL DEFAULT '',
	detailsPage VARCHAR(100) NOT NULL DEFAULT '',
	detailsMemberPage VARCHAR(100) NOT NULL DEFAULT '',
	isActive tinyint(1) NOT NULL DEFAULT '0',
	UNIQUE KEY (name)
);
ALTER TABLE guild1_game ADD INDEX isActive (isActive);

DROP TABLE IF EXISTS guild1_guild;
CREATE TABLE guild1_guild (
	guildID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	gameID INT(10) DEFAULT NULL,
	name VARCHAR(50) NOT NULL DEFAULT '',
	apiData VARCHAR(100) NOT NULL DEFAULT '',
	isActive tinyint(1) NOT NULL DEFAULT '0',
	UNIQUE KEY (gameID, name)
);
ALTER TABLE guild1_guild ADD INDEX isActive (isActive);

DROP TABLE IF EXISTS guild1_instance;
CREATE TABLE guild1_instance (
	instanceID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	gameID INT(10) DEFAULT NULL,
	name VARCHAR(50) NOT NULL DEFAULT '',
	encounters INT(10) DEFAULT 0,
	isActive tinyint(1) NOT NULL DEFAULT '0',
	UNIQUE KEY (gameID, name)
);
ALTER TABLE guild1_instance ADD INDEX isActive (isActive);

DROP TABLE IF EXISTS guild1_instance_kills;
CREATE TABLE guild1_instance_kills (
	killsID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	guildID INT(10) DEFAULT NULL,
	instanceID INT(10) DEFAULT NULL,
	kills INT(10) DEFAULT NULL,
	UNIQUE KEY (guildID, instanceID)
);

DROP TABLE IF EXISTS guild1_member;
CREATE TABLE guild1_member (
	memberID int(10) NOT NULL,
	guildID INT(10) DEFAULT NULL,
	name VARCHAR(50) NOT NULL DEFAULT '',
	thumbnail VARCHAR(100) NOT NULL DEFAULT '',
	userID INT(10) DEFAULT NULL,
	groupID INT(10) DEFAULT NULL,
	roleID INT(10) DEFAULT NULL,
	avatarID INT(10) DEFAULT NULL,
	isMain tinyint(1) NOT NULL DEFAULT '0',
	isActive tinyint(1) NOT NULL DEFAULT '0',
	isApiActive tinyint(1) NOT NULL DEFAULT '0',
	UNIQUE KEY (memberID, guildID)
);
ALTER TABLE guild1_member ADD INDEX isActive (isActive);

DROP TABLE IF EXISTS guild1_role;
CREATE TABLE guild1_role (
	roleID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	gameID INT(10) DEFAULT NULL,
	name VARCHAR(50) NOT NULL DEFAULT '',
	isActive tinyint(1) NOT NULL DEFAULT '0'
);
ALTER TABLE guild1_role ADD INDEX isActive (isActive);

DROP TABLE IF EXISTS guild1_wow_encounter;
CREATE TABLE guild1_wow_encounter (
	encounterID INT(10) NOT NULL PRIMARY KEY,
	instanceID INT(10) DEFAULT NULL,
	name VARCHAR(50) NOT NULL DEFAULT ''
);

DROP TABLE IF EXISTS guild1_wow_encounter_kills;
CREATE TABLE guild1_wow_encounter_kills (
	encounterID INT(10) DEFAULT NULL,
	guildID INT(10) DEFAULT NULL,
	isKilled tinyint(1) NOT NULL DEFAULT '0',
	UNIQUE KEY (encounterID, guildID)
);

DROP TABLE IF EXISTS guild1_wow_instance;
CREATE TABLE guild1_wow_instance (
	instanceID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	mapID INT(10) NOT NULL DEFAULT '0',
	name VARCHAR(100) NOT NULL DEFAULT '',
	isRaid tinyint(1) NOT NULL DEFAULT '0',
	title VARCHAR(50) NOT NULL DEFAULT '',
	difficulty INT(10) NOT NULL DEFAULT '0',
	isActive tinyint(1) NOT NULL DEFAULT '0'
);
ALTER TABLE guild1_wow_instance ADD INDEX isActive (isActive);

DROP TABLE IF EXISTS guild1_wow_statistic;
CREATE TABLE guild1_wow_statistic (
	statisticID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	memberID INT(10) DEFAULT NULL,
	guildID INT(10) DEFAULT NULL,
	week INT(10) DEFAULT NULL,
	typeID INT(10) DEFAULT NULL,
	value VARCHAR(100) DEFAULT NULL,
	UNIQUE KEY (memberID, guildID, week, typeID)
);

DROP TABLE IF EXISTS guild1_wow_statistic_type;
CREATE TABLE guild1_wow_statistic_type (
	typeID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	type VARCHAR(50) NOT NULL DEFAULT '',
	value VARCHAR(100) NOT NULL DEFAULT '',
	value2 VARCHAR(100) NOT NULL DEFAULT '',
	description VARCHAR(200) NOT NULL DEFAULT ''
);

ALTER TABLE calendar1_event_date_participation ADD FOREIGN KEY (guildMemberID) REFERENCES guild1_member (memberID) ON DELETE SET NULL;
ALTER TABLE calendar1_event_date_participation ADD FOREIGN KEY (guildRoleID) REFERENCES guild1_role (roleID) ON DELETE SET NULL;
ALTER TABLE guild1_avatar ADD FOREIGN KEY (gameID) REFERENCES guild1_game (gameID) ON DELETE CASCADE;
ALTER TABLE guild1_guild ADD FOREIGN KEY (gameID) REFERENCES guild1_game (gameID) ON DELETE CASCADE;
ALTER TABLE guild1_instance ADD FOREIGN KEY (gameID) REFERENCES guild1_game (gameID) ON DELETE CASCADE;
ALTER TABLE guild1_instance_kills ADD FOREIGN KEY (guildID) REFERENCES guild1_guild (guildID) ON DELETE CASCADE;
ALTER TABLE guild1_instance_kills ADD FOREIGN KEY (instanceID) REFERENCES guild1_instance (instanceID) ON DELETE CASCADE;
ALTER TABLE guild1_member ADD FOREIGN KEY (guildID) REFERENCES guild1_guild (guildID) ON DELETE CASCADE;
ALTER TABLE guild1_member ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE SET NULL;
ALTER TABLE guild1_member ADD FOREIGN KEY (groupID) REFERENCES wcf1_user_group (groupID) ON DELETE SET NULL;
ALTER TABLE guild1_member ADD FOREIGN KEY (roleID) REFERENCES guild1_role (roleID) ON DELETE SET NULL;
ALTER TABLE guild1_member ADD FOREIGN KEY (avatarID) REFERENCES guild1_avatar (avatarID) ON DELETE SET NULL;
ALTER TABLE guild1_role ADD FOREIGN KEY (gameID) REFERENCES guild1_game (gameID) ON DELETE CASCADE;
ALTER TABLE guild1_wow_encounter ADD FOREIGN KEY (instanceID) REFERENCES guild1_wow_instance (instanceID) ON DELETE CASCADE;
ALTER TABLE guild1_wow_encounter_kills ADD FOREIGN KEY (encounterID) REFERENCES guild1_wow_encounter (encounterID) ON DELETE CASCADE;
ALTER TABLE guild1_wow_encounter_kills ADD FOREIGN KEY (guildID) REFERENCES guild1_guild (guildID) ON DELETE CASCADE;
ALTER TABLE guild1_wow_statistic ADD FOREIGN KEY (memberID) REFERENCES guild1_member (memberID) ON DELETE CASCADE;
ALTER TABLE guild1_wow_statistic ADD FOREIGN KEY (guildID) REFERENCES guild1_guild (guildID) ON DELETE CASCADE;

INSERT INTO guild1_game
		(gameID, name, tag, apiClass, apiKey, detailsPage, detailsMemberPage, isActive)
VALUES	(1, 'World of Warcraft', 'wow', 'guild\\system\\game\\api\\wow\\WoW', '', 'wow', 'wowmember', 1);
INSERT INTO guild1_game
		(gameID, name, tag, apiClass, apiKey, detailsPage, detailsMemberPage, isActive)
VALUES	(2, 'Star Wars: The Old Republic', 'swtor', '', '', '', '', 1);

INSERT INTO guild1_avatar
		(avatarID, gameID, name, image, autoAssignment, isActive)
VALUES	(1, 1, 'guild.user.avatar.wow.warrior', 'images/wow/avatar/warrior.png', 1, 1);
INSERT INTO guild1_avatar
		(avatarID, gameID, name, image, autoAssignment, isActive)
VALUES	(2, 1, 'guild.user.avatar.wow.paladin', 'images/wow/avatar/paladin.png', 2, 1);
INSERT INTO guild1_avatar
		(avatarID, gameID, name, image, autoAssignment, isActive)
VALUES	(3, 1, 'guild.user.avatar.wow.hunter', 'images/wow/avatar/hunter.png', 3, 1);
INSERT INTO guild1_avatar
		(avatarID, gameID, name, image, autoAssignment, isActive)
VALUES	(4, 1, 'guild.user.avatar.wow.rouge', 'images/wow/avatar/rouge.png', 4, 1);
INSERT INTO guild1_avatar
		(avatarID, gameID, name, image, autoAssignment, isActive)
VALUES	(5, 1, 'guild.user.avatar.wow.priest', 'images/wow/avatar/priest.png', 5, 1);
INSERT INTO guild1_avatar
		(avatarID, gameID, name, image, autoAssignment, isActive)
VALUES	(6, 1, 'guild.user.avatar.wow.deathknight', 'images/wow/avatar/deathknight.png', 6, 1);
INSERT INTO guild1_avatar
		(avatarID, gameID, name, image, autoAssignment, isActive)
VALUES	(7, 1, 'guild.user.avatar.wow.shamane', 'images/wow/avatar/shamane.png', 7, 1);
INSERT INTO guild1_avatar
		(avatarID, gameID, name, image, autoAssignment, isActive)
VALUES	(8, 1, 'guild.user.avatar.wow.mage', 'images/wow/avatar/mage.png', 8, 1);
INSERT INTO guild1_avatar
		(avatarID, gameID, name, image, autoAssignment, isActive)
VALUES	(9, 1, 'guild.user.avatar.wow.warlock', 'images/wow/avatar/warlock.png', 9, 1);
INSERT INTO guild1_avatar
		(avatarID, gameID, name, image, autoAssignment, isActive)
VALUES	(10, 1, 'guild.user.avatar.wow.monk', 'images/wow/avatar/monk.png', 10, 1);
INSERT INTO guild1_avatar
		(avatarID, gameID, name, image, autoAssignment, isActive)
VALUES	(11, 1, 'guild.user.avatar.wow.druide', 'images/wow/avatar/druide.png', 11, 1);
INSERT INTO guild1_avatar
		(avatarID, gameID, name, image, autoAssignment, isActive)
VALUES	(12, 1, 'guild.user.avatar.wow.deamonhunter', 'images/wow/avatar/deamonhunter.png', 12, 1);
INSERT INTO guild1_avatar
		(avatarID, gameID, name, image, autoAssignment, isActive)
VALUES	(13, 2, 'guild.user.avatar.swtor.jediconsular', 'images/swtor/avatar/jediconsular.png', 0, 1);
INSERT INTO guild1_avatar
		(avatarID, gameID, name, image, autoAssignment, isActive)
VALUES	(14, 2, 'guild.user.avatar.swtor.jediknight', 'images/swtor/avatar/jediknight.png', 0, 1);
INSERT INTO guild1_avatar
		(avatarID, gameID, name, image, autoAssignment, isActive)
VALUES	(15, 2, 'guild.user.avatar.swtor.smuggler', 'images/swtor/avatar/smuggler.png', 0, 1);
INSERT INTO guild1_avatar
		(avatarID, gameID, name, image, autoAssignment, isActive)
VALUES	(16, 2, 'guild.user.avatar.swtor.trooper', 'images/swtor/avatar/trooper.png', 0, 1);
INSERT INTO guild1_avatar
		(avatarID, gameID, name, image, autoAssignment, isActive)
VALUES	(17, 2, 'guild.user.avatar.swtor.bountyhunter', 'images/swtor/avatar/bountyhunter.png', 0, 1);
INSERT INTO guild1_avatar
		(avatarID, gameID, name, image, autoAssignment, isActive)
VALUES	(18, 2, 'guild.user.avatar.swtor.imperialagent', 'images/swtor/avatar/imperialagent.png', 0, 1);
INSERT INTO guild1_avatar
		(avatarID, gameID, name, image, autoAssignment, isActive)
VALUES	(19, 2, 'guild.user.avatar.swtor.sithinquisitor', 'images/swtor/avatar/sithinquisitor.png', 0, 1);
INSERT INTO guild1_avatar
		(avatarID, gameID, name, image, autoAssignment, isActive)
VALUES	(20, 2, 'guild.user.avatar.swtor.sithwarrior', 'images/swtor/avatar/sithwarrior.png', 0, 1);

INSERT INTO guild1_role
		(roleID, gameID, name, isActive)
VALUES	(1, 1, 'guild.user.role.tank', 1);
INSERT INTO guild1_role
		(roleID, gameID, name, isActive)
VALUES	(2, 1, 'guild.user.role.heal', 1);
INSERT INTO guild1_role
		(roleID, gameID, name, isActive)
VALUES	(3, 1, 'guild.user.role.range', 1);
INSERT INTO guild1_role
		(roleID, gameID, name, isActive)
VALUES	(4, 1, 'guild.user.role.melee', 1);
INSERT INTO guild1_role
		(roleID, gameID, name, isActive)
VALUES	(5, 2, 'guild.user.role.tank', 1);
INSERT INTO guild1_role
		(roleID, gameID, name, isActive)
VALUES	(6, 2, 'guild.user.role.heal', 1);
INSERT INTO guild1_role
		(roleID, gameID, name, isActive)
VALUES	(7, 2, 'guild.user.role.range', 1);
INSERT INTO guild1_role
		(roleID, gameID, name, isActive)
VALUES	(8, 2, 'guild.user.role.melee', 1);

INSERT INTO guild1_instance
		( gameID, name, encounters, isActive)
VALUES	(2, 'SM: Ewige Kammer', 5, 1);
INSERT INTO guild1_instance
		(gameID, name, encounters, isActive)
VALUES	(2, 'HC: Ewige Kammer', 5, 1);
INSERT INTO guild1_instance
		(gameID, name, encounters, isActive)
VALUES	(2, 'SM: Karaggas Palast', 5, 1);
INSERT INTO guild1_instance
		(gameID, name, encounters, isActive)
VALUES	(2, 'HC: Karaggas Palast', 5, 1);
INSERT INTO guild1_instance
		(gameID, name, encounters, isActive)
VALUES	(2, 'SM: Explosiver Konflikt (Denova)', 4, 1);
INSERT INTO guild1_instance
		(gameID, name, encounters, isActive)
VALUES	(2, 'HC: Explosiver Konflikt (Denova)', 4, 1);
INSERT INTO guild1_instance
		(gameID, name, encounters, isActive)
VALUES	(2, 'SM: chrecken aus der Tiefe (Asation)', 4, 1);
INSERT INTO guild1_instance
		(gameID, name, encounters, isActive)
VALUES	(2, 'HC: chrecken aus der Tiefe (Asation)', 4, 1);
INSERT INTO guild1_instance
		(gameID, name, encounters, isActive)
VALUES	(2, 'NiM: chrecken aus der Tiefe (Asation)', 4, 1);
INSERT INTO guild1_instance
		(gameID, name, encounters, isActive)
VALUES	(2, 'SM: Schreckenspalast', 5, 1);
INSERT INTO guild1_instance
		(gameID, name, encounters, isActive)
VALUES	(2, 'HC: Schreckenspalast', 5, 1);
INSERT INTO guild1_instance
		(gameID, name, encounters, isActive)
VALUES	(2, 'SM: Abschaum und Verkommenheit', 7, 1);
INSERT INTO guild1_instance
		(gameID, name, encounters, isActive)
VALUES	(2, 'HC: Abschaum und Verkommenheit', 7, 1);
INSERT INTO guild1_instance
		(gameID, name, encounters, isActive)
VALUES	(2, 'NiM: Abschaum und Verkommenheit', 7, 1);
INSERT INTO guild1_instance
		(gameID, name, encounters, isActive)
VALUES	(2, 'SM: Schreckensfestung', 5, 1);
INSERT INTO guild1_instance
		(gameID, name, encounters, isActive)
VALUES	(2, 'HC: Schreckensfestung', 5, 1);
INSERT INTO guild1_instance
		(gameID, name, encounters, isActive)
VALUES	(2, 'NiM: Schreckensfestung', 5, 1);
INSERT INTO guild1_instance
		(gameID, name, encounters, isActive)
VALUES	(2, 'SM: Die Wüter', 5, 1);
INSERT INTO guild1_instance
		(gameID, name, encounters, isActive)
VALUES	(2, 'HC: Die Wüter', 5, 1);
INSERT INTO guild1_instance
		(gameID, name, encounters, isActive)
VALUES	(2, 'SM: Tempel des Opfers', 4, 1);
INSERT INTO guild1_instance
		(gameID, name, encounters, isActive)
VALUES	(2, 'HC: Tempel des Opfers', 4, 1);
INSERT INTO guild1_instance
		(gameID, name, encounters, isActive)
VALUES	(2, 'SM: Götter aus der Maschine', 4, 1);
INSERT INTO guild1_instance
		(gameID, name, encounters, isActive)
VALUES	(2, 'HC: TGötter aus der Maschine', 4, 1);

INSERT INTO guild1_wow_instance
		(instanceID, mapID, name, isRaid, title, difficulty, isActive)
VALUES	(1, 1094, 'Normal: Der Smaragdgrüne Alptraum', 1, 'Der Smaragdgrüne Alptraum (N)', 0, 0);
INSERT INTO guild1_wow_instance
		(instanceID, mapID, name, isRaid, title, difficulty, isActive)
VALUES	(2, 1094, 'Heroisch: Der Smaragdgrüne Alptraum', 1, 'Der Smaragdgrüne Alptraum (H)', 15, 0);
INSERT INTO guild1_wow_instance
		(instanceID, mapID, name, isRaid, title, difficulty, isActive)
VALUES	(3, 1094, 'Mythisch: Der Smaragdgrüne Alptraum', 1, 'Der Smaragdgrüne Alptraum (M)', 0, 1);
INSERT INTO guild1_wow_instance
		(instanceID, mapID, name, isRaid, title, difficulty, isActive)
VALUES	(4, 1114, 'Normal: Prüfung der Tapferkeit', 1, 'Prüfung der Tapferkeit (N)', 0, 0);
INSERT INTO guild1_wow_instance
		(instanceID, mapID, name, isRaid, title, difficulty, isActive)
VALUES	(5, 1114, 'Heroisch: Prüfung der Tapferkeit', 1, 'Prüfung der Tapferkeit (H)', 15, 0);
INSERT INTO guild1_wow_instance
		(instanceID, mapID, name, isRaid, title, difficulty, isActive)
VALUES	(6, 1114, 'Mythisch: Prüfung der Tapferkeit', 1, 'Prüfung der Tapferkeit (M)', 0, 1);
INSERT INTO guild1_wow_instance
		(instanceID, mapID, name, isRaid, title, difficulty, isActive)
VALUES	(7, 1088, 'Normal: Die Nachtfestung', 1, 'Die Nachtfestung (N)', 0, 0);
INSERT INTO guild1_wow_instance
		(instanceID, mapID, name, isRaid, title, difficulty, isActive)
VALUES	(8, 1088, 'Heroisch: Die Nachtfestung', 1, 'Die Nachtfestung (H)', 15, 0);
INSERT INTO guild1_wow_instance
		(instanceID, mapID, name, isRaid, title, difficulty, isActive)
VALUES	(9, 1088, 'Mythisch: Die Nachtfestung', 1, 'Die Nachtfestung (M)', 0, 1);
INSERT INTO guild1_wow_instance
		(instanceID, mapID, name, isRaid, title, difficulty, isActive)
VALUES	(10, 1147, 'Normal: Das Grabmal des Sargeras', 1, 'Das Grabmal des Sargeras (N)', 0, 0);
INSERT INTO guild1_wow_instance
		(instanceID, mapID, name, isRaid, title, difficulty, isActive)
VALUES	(11, 1147, 'Heroisch: Das Grabmal des Sargeras', 1, 'Das Grabmal des Sargeras (H)', 15, 0);
INSERT INTO guild1_wow_instance
		(instanceID, mapID, name, isRaid, title, difficulty, isActive)
VALUES	(12, 1147, 'Mythisch: Das Grabmal des Sargeras', 1, 'Das Grabmal des Sargeras (M)', 0, 1);
INSERT INTO guild1_wow_instance
		(instanceID, mapID, name, isRaid, title, difficulty, isActive)
VALUES	(13, 1188, 'Normal: Antorus, der Brennende Thron', 1, 'Antorus, der Brennende Thron (N)', 0, 0);
INSERT INTO guild1_wow_instance
		(instanceID, mapID, name, isRaid, title, difficulty, isActive)
VALUES	(14, 1188, 'Heroisch: Antorus, der Brennende Thron', 1, 'Antorus, der Brennende Thron (H)', 15, 0);
INSERT INTO guild1_wow_instance
		(instanceID, mapID, name, isRaid, title, difficulty, isActive)
VALUES	(15, 1188, 'Mythisch: Antorus, der Brennende Thron', 1, 'Antorus, der Brennende Thron (M)', 0, 1);
INSERT INTO guild1_wow_instance
		(instanceID, mapID, name, isRaid, title, difficulty, isActive)
VALUES	(16, 1081, 'Mythisch: Die Rabenwehr', 0, '', 0, 1);
INSERT INTO guild1_wow_instance
		(instanceID, mapID, name, isRaid, title, difficulty, isActive)
VALUES	(17, 1146, 'Mythisch: Die Kathedrale der Ewigen Nacht', 0, '', 0, 1);
INSERT INTO guild1_wow_instance
		(instanceID, mapID, name, isRaid, title, difficulty, isActive)
VALUES	(18, 1087, 'Mythisch: Der Hof der Sterne', 0, '', 0, 1);
INSERT INTO guild1_wow_instance
		(instanceID, mapID, name, isRaid, title, difficulty, isActive)
VALUES	(19, 1067, 'Mythisch: Das Finsterherzdickicht', 0, '', 0, 1);
INSERT INTO guild1_wow_instance
		(instanceID, mapID, name, isRaid, title, difficulty, isActive)
VALUES	(20, 1046, 'Mythisch: Das Auge Azsharas', 0, '', 0, 1);
INSERT INTO guild1_wow_instance
		(instanceID, mapID, name, isRaid, title, difficulty, isActive)
VALUES	(21, 1041, 'Mythisch: Die Hallen der Tapferkeit', 0, '', 0, 1);
INSERT INTO guild1_wow_instance
		(instanceID, mapID, name, isRaid, title, difficulty, isActive)
VALUES	(22, 1042, 'Mythisch: Der Seelenschlund', 0, '', 0, 1);
INSERT INTO guild1_wow_instance
		(instanceID, mapID, name, isRaid, title, difficulty, isActive)
VALUES	(23, 1065, 'Mythisch: Neltharions Hort', 0, '', 0, 1);
INSERT INTO guild1_wow_instance
		(instanceID, mapID, name, isRaid, title, difficulty, isActive)
VALUES	(24, 1115, 'Rückkehr nach Karazhan', 0, '', 0, 1);
INSERT INTO guild1_wow_instance
		(instanceID, mapID, name, isRaid, title, difficulty, isActive)
VALUES	(25, 1079, 'Mythisch: Der Arkus', 0, '', 0, 1);
INSERT INTO guild1_wow_instance
		(instanceID, mapID, name, isRaid, title, difficulty, isActive)
VALUES	(26, 1045, 'Mythisch: Das Verlies der Wächterinnen', 0, '', 0, 1);
INSERT INTO guild1_wow_instance
		(instanceID, mapID, name, isRaid, title, difficulty, isActive)
VALUES	(27, 1066, 'Mythisch: Sturm auf die Violette Festung', 0, '', 0, 1);

INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(0, NULL, 'Trash Mob');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10912, 1, 'Nythendra');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10913, 2, 'Nythendra');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10914, 3, 'Nythendra');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10916, 1, 'Ursoc');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10917, 2, 'Ursoc');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10919, 3, 'Ursoc');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10921, 1, 'Elerethe Renferal');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10922, 2, 'Elerethe Renferal');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10923, 3, 'Elerethe Renferal');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10925, 1, 'Il\'gynoth');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10926, 2, 'Il\'gynoth');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10927, 3, 'Il\'gynoth');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10929, 1, 'Drachen des Alptraums');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10930, 2, 'Drachen des Alptraums');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10931, 3, 'Drachen des Alptraums');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10933, 1, 'Cenarius');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10934, 2, 'Cenarius');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10935, 3, 'Cenarius');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10937, 1, 'Xavius');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10938, 2, 'Xavius');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10939, 3, 'Xavius');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10941, 7, 'Skorpyron');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10942, 8, 'Skorpyron');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10943, 9, 'Skorpyron');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10945, 7, 'Chronomatische Anomalie');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10946, 8, 'Chronomatische Anomalie');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10947, 9, 'Chronomatische Anomalie');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10949, 7, 'Trilliax');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10950, 8, 'Trilliax');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10951, 9, 'Trilliax');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10953, 7, 'Aluriel die Zauberklinge');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10954, 8, 'Aluriel die Zauberklinge');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10955, 9, 'Aluriel die Zauberklinge');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10957, 7, 'Sterndeuter Etraeus');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10959, 8, 'Sterndeuter Etraeus');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10960, 9, 'Sterndeuter Etraeus');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10962, 7, 'Hochbotaniker Tel\'arn');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10963, 8, 'Hochbotaniker Tel\'arn');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10964, 9, 'Hochbotaniker Tel\'arn');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10966, 7, 'Tichondrius');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10967, 8, 'Tichondrius');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10968, 9, 'Tichondrius');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10970, 7, 'Krosus');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10971, 8, 'Krosus');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10972, 9, 'Krosus');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10974, 7, 'Großmagistrix Elisande');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10975, 8, 'Großmagistrix Elisande');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10976, 9, 'Großmagistrix Elisande');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10978, 7, 'Gul\'dan');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10979, 8, 'Gul\'dan');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(10980, 9, 'Gul\'dan');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11408, 4, 'Odyn');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11409, 5, 'Odyn');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11410, 6, 'Odyn');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11412, 4, 'Guarm');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11413, 5, 'Guarm');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11414, 6, 'Guarm');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11416, 4, 'Helya');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11417, 5, 'Helya');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11418, 6, 'Helya');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11878, 10,'Goroth');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11879, 11,'Goroth');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11880, 12,'Goroth');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11882, 10,'Dämonische Inquisition');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11883, 11,'Dämonische Inquisition');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11884, 12,'Dämonische Inquisition');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11886, 10,'Harjatan');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11887, 11,'Harjatan');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11888, 12,'Harjatan');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11890, 10,'Schwestern des Mondes');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11891, 11,'Schwestern des Mondes');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11892, 12,'Schwestern des Mondes');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11894, 10,'Herrin Sassz\'ine');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11895, 11,'Herrin Sassz\'ine');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11896, 12,'Herrin Sassz\'ine');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11898, 10,'Die trostlose Heerschar');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11899, 11,'Die trostlose Heerschar');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11900, 12,'Die trostlose Heerschar');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11902, 10,'Wachsame Maid');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11903, 11,'Wachsame Maid');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11904, 12,'Wachsame Maid');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11906, 10,'Gefallener Avatar');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11907, 11,'Gefallener Avatar');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11908, 12,'Gefallener Avatar');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11910, 10,'Kil\'jaeden');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11911, 11,'Kil\'jaeden');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11912, 12,'Kil\'jaeden');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11954, 13, 'Weltenbrecher der Garothi');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11955, 14, 'Weltenbrecher der Garothi');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11956, 15, 'Weltenbrecher der Garothi');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11957, 13, 'Teufelshunde des Sargeras');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11958, 14, 'Teufelshunde des Sargeras');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11959, 15, 'Teufelshunde des Sargeras');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11960, 13, 'Antorisches Oberkommando');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11961, 14, 'Antorisches Oberkommando');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11962, 15, 'Antorisches Oberkommando');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11963, 13, 'Portalhüterin Hasabel');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11964, 14, 'Portalhüterin Hasabel');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11965, 15, 'Portalhüterin Hasabel');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11966, 13, 'Die Verteidigung von Eonar');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11967, 14, 'Die Verteidigung von Eonar');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11968, 15, 'Die Verteidigung von Eonar');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11969, 13, 'Imonar der Seelenjäger');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11970, 14, 'Imonar der Seelenjäger');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11971, 15, 'Imonar der Seelenjäger');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11972, 13, 'Kin\'garoth');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11973, 14, 'Kin\'garoth');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11974, 15, 'Kin\'garoth');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11975, 13, 'Varimathras');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11976, 14, 'Varimathras');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11977, 15, 'Varimathras');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11978, 13, 'Der Zirkel der Shivarra');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11979, 14, 'Der Zirkel der Shivarra');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11980, 15, 'Der Zirkel der Shivarra');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11981, 13, 'Aggramar');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11982, 14, 'Aggramar');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11983, 15, 'Aggramar');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11984, 13, 'Argus, der Zerrütter');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11985, 14, 'Argus, der Zerrütter');
INSERT INTO guild1_wow_encounter
		(encounterID, instanceID, name)
VALUES	(11986, 15, 'Argus, der Zerrütter');

INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(1, 'charakter', 'name', '', '');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(2, 'charakter', 'class', '', '');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(3, 'charakter', 'race', '', '');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(4, 'charakter', 'gender', '', '');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(5, 'charakter', 'achievementPoints', '', '');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(6, 'charakter', 'thumbnail', '', '');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(7, 'charakter', 'iLevel', '', '');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(8, 'charakter', 'lastModified', '', '');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(9, 'charakter', 'artefactweaponLevel', '', '');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(10, 'charakter', 'level', '', '');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(11, 'instance', '10880', '20', 'Mythisch: Das Auge Azsharas');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(12, 'instance', '10883', '19', 'Mythisch: Das Finsterherzdickicht');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(13, 'instance', '10886', '23', 'Mythisch: Neltharions Hort');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(14, 'instance', '10889', '21', 'Mythisch: Die Hallen der Tapferkeit');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(15, 'instance', '10892;10895', '27', 'Mythisch: Sturm auf die Violette Festung');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(16, 'instance', '10898', '26', 'Mythisch: Das Verlies der Wächterinnen');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(17, 'instance', '10901', '16', 'Mythisch: Die Rabenwehr');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(18, 'instance', '10904', '22', 'Mythisch: Der Seelenschlund');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(19, 'instance', '10907', '25', 'Mythisch: Der Arkus');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(20, 'instance', '10910', '18', 'Mythisch: Der Hof der Sterne');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(21, 'instance', '11406', '24', 'Rückkehr nach Karazhan');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(22, 'instance', '10912', '1', 'Siege über Nythendra (Normal: Der Smaragdgrüne Alptraum)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(23, 'instance', '10913', '2', 'Siege über Nythendra (Heroisch: Der Smaragdgrüne Alptraum)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(24, 'instance', '10914', '3', ' Siege über Nythendra (Mythisch: Der Smaragdgrüne Alptraum)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(25, 'instance', '10921', '1', 'Siege über Elerethe Renferal (Normal: Der Smaragdgrüne Alptraum)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(26, 'instance', '10922', '2', 'Siege über Elerethe Renferal (Heroisch: Der Smaragdgrüne Alptraum)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(27, 'instance', '10923', '3', 'Siege über Elerethe Renferal (Mythisch: Der Smaragdgrüne Alptraum)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(28, 'instance', '10925', '1', 'Siege über Il\'gynoth (Normal: Der Smaragdgrüne Alptraum)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(29, 'instance', '10926', '2', 'Siege über Il\'gynoth (Heroisch: Der Smaragdgrüne Alptraum)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(30, 'instance', '10927', '3', 'Siege über Il\'gynoth (Mythisch: Der Smaragdgrüne Alptraum)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(31, 'instance', '10916', '1', 'Siege über Ursoc (Normal: Der Smaragdgrüne Alptraum)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(32, 'instance', '10917', '2', 'Siege über Ursoc (Heroisch: Der Smaragdgrüne Alptraum)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(33, 'instance', '10919', '3', 'Siege über Ursoc (Mythisch: Der Smaragdgrüne Alptraum)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(34, 'instance', '10929', '1', 'Siege über die Drachen des Alptraums (Normal: Der Smaragdgrüne Alptraum)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(35, 'instance', '10930', '2', 'Siege über die Drachen des Alptraums (Heroisch: Der Smaragdgrüne Alptraum)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(36, 'instance', '10931', '3', 'Siege über die Drachen des Alptraums (Mythisch: Der Smaragdgrüne Alptraum)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(37, 'instance', '10933', '1', 'Erlösungen von Cenarius (Normal: Der Smaragdgrüne Alptraum)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(38, 'instance', '10934', '2', 'Erlösungen von Cenarius (Heroisch: Der Smaragdgrüne Alptraum)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(39, 'instance', '10935', '3', 'Erlösungen von Cenarius (Mythisch: Der Smaragdgrüne Alptraum)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(40, 'instance', '10937', '1', 'Siege über Xavius (Normal: Der Smaragdgrüne Alptraum)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(41, 'instance', '10938', '2', 'Siege über Xavius (Heroisch: Der Smaragdgrüne Alptraum)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(42, 'instance', '10939', '3', 'Siege über Xavius (Mythisch: Der Smaragdgrüne Alptraum)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(43, 'instance', '11408', '4', 'Siege über Odyn (Normal: Prüfung der Tapferkeit)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(44, 'instance', '11409', '5', 'Siege über Odyn (Heroisch: Prüfung der Tapferkeit)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(45, 'instance', '11410', '6', 'Siege über Odyn (Mythisch: Prüfung der Tapferkeit)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(46, 'instance', '11412', '4', 'Siege über Guarm (Normal: Prüfung der Tapferkeit)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(47, 'instance', '11413', '5', 'Siege über Guarm (Heroisch: Prüfung der Tapferkeit)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(48, 'instance', '11414', '6', 'Siege über Guarm (Mythisch: Prüfung der Tapferkeit)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(49, 'instance', '11416', '4', 'Siege über Helya (Normal: Prüfung der Tapferkeit)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(50, 'instance', '11417', '5', 'Siege über Helya (Heroisch: Prüfung der Tapferkeit)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(51, 'instance', '11418', '6', 'Siege über Helya (Mythisch: Prüfung der Tapferkeit)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(52, 'instance', '10941', '7', 'Siege über Skorpyron (Normal: Die Nachtfestung)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(53, 'instance', '10942', '8', 'Siege über Skorpyron (Heroisch: Die Nachtfestung)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(54, 'instance', '10943', '9', 'Siege über Skorpyron (Mythisch: Die Nachtfestung)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(55, 'instance', '10945', '7', 'Siege über die Chronomatische Anomalie (Normal: Die Nachtfestung)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(56, 'instance', '10946', '8', 'Siege über die Chronomatische Anomalie (Heroisch: Die Nachtfestung)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(57, 'instance', '10947', '9', 'Siege über die Chronomatische Anomalie (Mythisch: Die Nachtfestung)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(58, 'instance', '10949', '7', 'Siege über Trilliax (Normal: Die Nachtfestung)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(59, 'instance', '10950', '8', 'Siege über Trilliax (Heroisch: Die Nachtfestung)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(60, 'instance', '10951', '9', 'Siege über Trilliax (Mythisch: Die Nachtfestung)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(61, 'instance', '10953', '7', 'Siege über Aluriel die Zauberklinge (Normal: Die Nachtfestung)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(62, 'instance', '10954', '8', 'Siege über Aluriel die Zauberklinge (Heroisch: Die Nachtfestung)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(63, 'instance', '10955', '9', 'Siege über Aluriel die Zauberklinge (Mythisch: Die Nachtfestung)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(64, 'instance', '10957', '7', 'Siege über Sterndeuter Etraeus (Normal: Die Nachtfestung)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(65, 'instance', '10959', '8', 'Siege über Sterndeuter Etraeus (Heroisch: Die Nachtfestung)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(66, 'instance', '10960', '9', 'Siege über Sterndeuter Etraeus (Mythisch: Die Nachtfestung)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(67, 'instance', '10962', '7', 'Siege über Hochbotaniker Tel\'arn (Normal: Die Nachtfestung)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(68, 'instance', '10963', '8', 'Siege über Hochbotaniker Tel\'arn (Heroisch: Die Nachtfestung)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(69, 'instance', '10964', '9', 'Siege über Hochbotaniker Tel\'arn (Mythisch: Die Nachtfestung)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(70, 'instance', '10966', '7', 'Siege über Tichondrius (Normal: Die Nachtfestung)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(71, 'instance', '10967', '8', 'Siege über Tichondrius (Heroisch: Die Nachtfestung)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(72, 'instance', '10968', '9', 'Siege über Tichondrius (Mythisch: Die Nachtfestung)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(73, 'instance', '10970', '7', 'Siege über Krosus (Normal: Die Nachtfestung)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(74, 'instance', '10971', '8', 'Siege über Krosus (Heroisch: Die Nachtfestung)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(75, 'instance', '10972', '9', 'Siege über Krosus (Mythisch: Die Nachtfestung)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(76, 'instance', '10974', '7', 'Siege über Großmagistrix Elisande (Normal: Die Nachtfestung)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(77, 'instance', '10975', '8', 'Siege über Großmagistrix Elisande (Heroisch: Die Nachtfestung)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(78, 'instance', '10976', '9', 'Siege über Großmagistrix Elisande (Mythisch: Die Nachtfestung)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(79, 'instance', '10978', '7', 'Siege über Gul\'dan (Normal: Die Nachtfestung)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(80, 'instance', '10979', '8', 'Siege über Gul\'dan (Heroisch: Die Nachtfestung)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(81, 'instance', '10980', '9', 'Siege über Gul\'dan (Mythisch: Die Nachtfestung)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(82, 'achievements', '33096', '', 'My+2');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(83, 'achievements', '33097', '', 'My+5');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(84, 'achievements', '33098', '', 'My+10');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(85, 'achievements', '32028', '', 'My+15');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(86, 'achievements', '30103', 'artefactPower', 'artefact power');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(87, 'achievements', '31466', 'artefactKnowledge', 'artefact knowledge');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(88, 'achievements', '33094', 'worldQuest', 'worldquess');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(89, 'instance', '11878', '10', 'Siege über Goroth (Normal: Das Grabmal des Sargeras)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(90, 'instance', '11879', '11', 'Siege über Goroth (Heroisch: Das Grabmal des Sargeras)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(91, 'instance', '11880', '12', 'Siege über Goroth (Mythisch: Das Grabmal des Sargeras)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(92, 'instance', '11882', '10', 'Siege über die dämonische Inquisition (Normal: Das Grabmal des Sargeras)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(93, 'instance', '11883', '11', 'Siege über die dämonische Inquisition (Heroisch: Das Grabmal des Sargeras)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(94, 'instance', '11884', '12', 'Siege über die dämonische Inquisition (Mythisch: Das Grabmal des Sargeras)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(95, 'instance', '11886', '10', 'Siege über Harjtan (Normal: Das Grabmal des Sargeras)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(96, 'instance', '11887', '11', 'Siege über Harjatan (Heroisch: Das Grabmal des Sargeras)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(97, 'instance', '11888', '12', 'Siege über Harjatan (Mythisch: Das Grabmal des Sargeras)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(98, 'instance', '11890', '10', 'Siege über die Schwestern des Mondes (Normal: Das Grabmal des Sargeras)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(99, 'instance', '11891', '11', 'Siege über die Schwestern des Mondes (Heroisch: Das Grabmal des Sargeras)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(100, 'instance', '11892', '12', 'Siege über die Schwestern des Mondes (Mythisch: Das Grabmal des Sargeras)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(101, 'instance', '11894', '10', 'Siege über Herrin Sassz\'ine (Normal: Das Grabmal des Sargeras)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(102, 'instance', '11895', '11', 'Siege über Herrin Sassz\'ine (Heroisch: Das Grabmal des Sargeras)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(103, 'instance', '11896', '12', 'Siege über Herrin Sassz\'ine (Mythisch: Das Grabmal des Sargeras)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(104, 'instance', '11898', '10', 'Siege über die trostlose Heerschar (Normal: Das Grabmal des Sargeras)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(105, 'instance', '11899', '11', 'Siege über die trostlose Heerschar (Heroisch: Das Grabmal des Sargeras)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(106, 'instance', '11900', '12', 'Siege über die trostlose Heerschar (Mythisch: Das Grabmal des Sargeras)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(107, 'instance', '11902', '10', 'Siege über die wachsame Maid (Normal: Das Grabmal des Sargeras)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(108, 'instance', '11903', '11', 'Siege über die wachsame Maid (Heroisch: Das Grabmal des Sargeras)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(109, 'instance', '11904', '12', 'Siege über die wachsame Maid (Mythisch: Das Grabmal des Sargeras)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(110, 'instance', '11906', '10', 'Siege über den gefallenen Avatar (Normal: Das Grabmal des Sargeras)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(111, 'instance', '11907', '11', 'Siege über den gefallenen Avatar (Heroisch: Das Grabmal des Sargeras)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(112, 'instance', '11908', '12', 'Siege über den gefallenen Avatar (Mythisch: Das Grabmal des Sargeras)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(113, 'instance', '11910', '10', 'Siege über Kil\'jaeden (Normal: Das Grabmal des Sargeras)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(114, 'instance', '11911', '11', 'Siege über Kil\'jaeden (Heroisch: Das Grabmal des Sargeras)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(115, 'instance', '11912', '12', 'Siege über Kil\'jaeden (Mythisch: Das Grabmal des Sargeras)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(116, 'instance', 11954, '13', 'Siege über Weltenbrecher der Garothi (Normal: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(117, 'instance', 11955, '14', 'Siege über Weltenbrecher der Garothi (Heroisch: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(118, 'instance', 11956, '15', 'Siege über Weltenbrecher der Garothi (Mythisch: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(119, 'instance', 11957, '13', 'Siege über Teufelshunde des Sargeras (Normal: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(120, 'instance', 11958, '14', 'Siege über Teufelshunde des Sargeras (Heroisch: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(121, 'instance', 11959, '15', 'Siege über Teufelshunde des Sargeras (Mythisch: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(122, 'instance', 11960, '13', 'Siege über Antorisches Oberkommando (Normal: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(123, 'instance', 11961, '14', 'Siege über Antorisches Oberkommando (Heroisch: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(124, 'instance', 11962, '15', 'Siege über Antorisches Oberkommando (Mythisch: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(125, 'instance', 11963, '13', 'Siege über Portalhüterin Hasabel (Normal: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(126, 'instance', 11964, '14', 'Siege über Portalhüterin Hasabel (Heroisch: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(127, 'instance', 11965, '15', 'Siege über Portalhüterin Hasabel (Mythisch: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(128, 'instance', 11966, '13', 'Siege über Die Verteidigung von Eonar (Normal: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(129, 'instance', 11967, '14', 'Siege über Die Verteidigung von Eonar (Heroisch: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(130, 'instance', 11968, '15', 'Siege über Die Verteidigung von Eonar (Mythisch: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(131, 'instance', 11969, '13', 'Siege über Imonar der Seelenjäger (Normal: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(132, 'instance', 11970, '14', 'Siege über Imonar der Seelenjäger (Heroisch: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(133, 'instance', 11971, '15', 'Siege über Imonar der Seelenjäger (Mythisch: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(134, 'instance', 11972, '13', 'Siege über Kin\'garoth (Normal: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(135, 'instance', 11973, '14', 'Siege über Kin\'garoth (Heroisch: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(136, 'instance', 11974, '15', 'Siege über Kin\'garoth (Mythisch: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(137, 'instance', 11975, '13', 'Siege über Varimathras (Normal: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(138, 'instance', 11976, '14', 'Siege über Varimathras (Heroisch: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(139, 'instance', 11977, '15', 'Siege über Varimathras (Mythisch: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(140, 'instance', 11978, '13', 'Siege über Der Zirkel der Shivarra (Normal: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(141, 'instance', 11979, '14', 'Siege über Der Zirkel der Shivarra (Heroisch: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(142, 'instance', 11980, '15', 'Siege über Der Zirkel der Shivarra (Mythisch: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(143, 'instance', 11981, '13', 'Siege über Aggramar (Normal: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(144, 'instance', 11982, '14', 'Siege über Aggramar (Heroisch: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(145, 'instance', 11983, '15', 'Siege über Aggramar (Mythisch: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(146, 'instance', 11984, '13', 'Siege über Argus, der Zerrütter (Normal: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(147, 'instance', 11985, '14', 'Siege über Argus, der Zerrütter (Heroisch: Antorus, der Brennende Thron)');
INSERT INTO guild1_wow_statistic_type
		(typeID, type, value, value2, description)
VALUES	(148, 'instance', 11986, '15', 'Siege über Argus, der Zerrütter (Mythisch: Antorus, der Brennende Thron)');