ALTER TABLE users
	DROP `role`,
	ADD `description` VARCHAR(255) NULL COLLATE utf8mb4_unicode_ci DEFAULT NULL AFTER `phone`,
	ADD `avatar` VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci DEFAULT 'default.png' AFTER `description`,
	ADD `email_verified_at` TIMESTAMP NULL AFTER `status`,
	ADD `remember_token` VARCHAR(100) NULL COLLATE utf8mb4_unicode_ci AFTER `password`;

UPDATE users
	SET `avatar` = `profileimage` WHERE `profileimage` != 'default.jpg';

ALTER TABLE users
	DROP `profileimage`;

ALTER TABLE jcr
    ADD COLUMN `assembled_time` TIME AFTER `assembled`,
	ADD COLUMN `assembled_date` DATE AFTER `assembled`,
    ADD COLUMN `depOffice_time` TIME AFTER `depOffice`,
	ADD COLUMN `depOffice_date` DATE AFTER `depOffice`,
    ADD COLUMN `arrivalSite_time` TIME AFTER `arrivalSite`,
	ADD COLUMN `arrivalSite_date` DATE AFTER `arrivalSite`,
    ADD COLUMN `indented_time` TIME AFTER `indented`,
	ADD COLUMN `indented_date` DATE AFTER `indented`,
    ADD COLUMN `wellReadiness_time` TIME AFTER `wellReadiness`,
	ADD COLUMN `wellReadiness_date` DATE AFTER `wellReadiness`,
    ADD COLUMN `wellTaken_time` TIME AFTER `wellTaken`,
	ADD COLUMN `wellTaken_date` DATE AFTER `wellTaken`,
    ADD COLUMN `rigUP_time` TIME AFTER `rigUP`,
	ADD COLUMN `rigUP_date` DATE AFTER `rigUP`,
    ADD COLUMN `wellHandOver_time` TIME AFTER `wellHandOver`,
	ADD COLUMN `wellHandOver_date` DATE AFTER `wellHandOver`,
    ADD COLUMN `depSite_time` TIME AFTER `depSite`,
	ADD COLUMN `depSite_date` DATE AFTER `depSite`,
    ADD COLUMN `arrivalOffice_time` TIME AFTER `arrivalOffice`,
	ADD COLUMN `arrivalOffice_date` DATE AFTER `arrivalOffice`;
UPDATE jcr
    SET `assembled_time` = SUBSTRING_INDEX(`assembled`, ' ', -1),
	`assembled_date` = SUBSTRING_INDEX(`assembled`, ' ', 1),
    `depOffice_time` = SUBSTRING_INDEX(`depOffice`, ' ', -1),
	`depOffice_date` = SUBSTRING_INDEX(`depOffice`, ' ', 1),
    `arrivalSite_time` = SUBSTRING_INDEX(`arrivalSite`, ' ', -1),
	`arrivalSite_date` = SUBSTRING_INDEX(`arrivalSite`, ' ', 1),
    `indented_time` = SUBSTRING_INDEX(`indented`, ' ', -1),
	`indented_date` = SUBSTRING_INDEX(`indented`, ' ', 1),
    `wellReadiness_time` = SUBSTRING_INDEX(`wellReadiness`, ' ', -1),
	`wellReadiness_date` = SUBSTRING_INDEX(`wellReadiness`, ' ', 1),
    `wellTaken_time` = SUBSTRING_INDEX(`wellTaken`, ' ', -1),
	`wellTaken_date` = SUBSTRING_INDEX(`wellTaken`, ' ', 1),
    `rigUP_time` = SUBSTRING_INDEX(`rigUP`, ' ', -1),
	`rigUP_date` = SUBSTRING_INDEX(`rigUP`, ' ', 1),
    `wellHandOver_time` = SUBSTRING_INDEX(`wellHandOver`, ' ', -1),
	`wellHandOver_date` = SUBSTRING_INDEX(`wellHandOver`, ' ', 1),
    `depSite_time` = SUBSTRING_INDEX(`depSite`, ' ', -1),
	`depSite_date` = SUBSTRING_INDEX(`depSite`, ' ', 1),
    `arrivalOffice_time` = SUBSTRING_INDEX(`arrivalOffice`, ' ', -1),
	`arrivalOffice_date` = SUBSTRING_INDEX(`arrivalOffice`, ' ', 1);

ALTER TABLE jcr
	DROP `assembled`,
	DROP `depOffice`,
	DROP `arrivalSite`,
	DROP `indented`,
	DROP `wellReadiness`,
	DROP `wellTaken`,
	DROP `rigUP`,
	DROP `wellHandOver`,
	DROP `depSite`,
	DROP `arrivalOffice`;


ALTER TABLE jcr
    ADD COLUMN `lastcirc_to` DATETIME AFTER `lastcirc`,
    ADD COLUMN `lastcirc_from` DATETIME AFTER `lastcirc`;

UPDATE jcr
    SET lastcirc_from = STR_TO_DATE(lastcirc, '%Y-%m-%d %H:%i:%s') WHERE lastcirc IS NOT NULL;

UPDATE jcr
    SET lastcirc_from = DATE_SUB(lastcirc_to, INTERVAL 2 HOUR) WHERE (lastcirc_to!=NULL OR lastcirc_to != '0000-00-00 00:00:00');

UPDATE jcr
    SET lastcirc_from = NULL WHERE (lastcirc_from<='2023-07-15 00:00:00' OR lastcirc_from>='2024-07-07 00:00:00');

UPDATE jcr
    SET lastcirc_to = NULL WHERE lastcirc_from IS NULL;

ALTER TABLE jcr
	DROP `lastcirc`;

ALTER TABLE `logsrecorded`
    ADD COLUMN `jcr_id` INT AFTER `id`;

UPDATE logsrecorded lr
JOIN jcrlogs jl ON lr.id = jl.logs_id
SET lr.jcr_id = jl.jcr_id;

DELETE FROM `logsrecorded` WHERE `jcr_id` is NULL;

UPDATE logsrecorded
SET
	`bottomShotDepth` = NULLIF(`bottomShotDepth`, ''),
	`topShotDepth` = NULLIF(`topShotDepth`, ''),
	`charge` = NULLIF(`charge`, ''),
	`chargeNo` = NULLIF(`chargeNo`, ''),
	`primaChord` = NULLIF(`primaChord`, ''),
	`primaChordQty` = NULLIF(`primaChordQty`, ''),
	`fuse` = NULLIF(`fuse`, ''),
	`fuseNo` = NULLIF(`fuseNo`, ''),
	`fMf` = NULLIF(`fMf`, '');

ALTER TABLE `logsrecorded`
	ADD CONSTRAINT `fk_logsrecorded_jcr` FOREIGN KEY (`jcr_id`) REFERENCES `jcr`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `explosiveused`
	ADD CONSTRAINT `fk_explosiveused_jcr` FOREIGN KEY (`jcr_id`) REFERENCES `jcr`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `explosiveused` CHANGE COLUMN `jcr_id` `jcr_id` INT(11) AFTER `id`;


UPDATE jcruser ju
JOIN users us ON us.cpf = ju.user_id
SET ju.user_id = us.id;

ALTER TABLE `jcruser`
	DROP FOREIGN KEY `jcruser_ibfk_1`;

ALTER TABLE `jcruser`
	ADD CONSTRAINT `jcruser_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
