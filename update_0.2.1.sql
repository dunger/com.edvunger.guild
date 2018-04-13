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

ALTER TABLE guild1_instance ADD FOREIGN KEY (gameID) REFERENCES guild1_game (gameID) ON DELETE CASCADE;
ALTER TABLE guild1_instance_kills ADD FOREIGN KEY (guildID) REFERENCES guild1_guild (guildID) ON DELETE CASCADE;
ALTER TABLE guild1_instance_kills ADD FOREIGN KEY (instanceID) REFERENCES guild1_instance (instanceID) ON DELETE CASCADE;

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