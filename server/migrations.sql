/* INI 2017-12-26 */

CREATE TABLE `plopi_app_ring`.`lugares` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NULL,
  `estado` INT NULL,
  `nombre` VARCHAR(45) NULL,
  `lat` float(10, 6 ) NULL,
  `lng` float( 10, 6 ) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`));

/* ya migrado desktop-plopi */
/* ya migrado server-plopi */
/* ya migrado notebook-plopi */
/* FIN 2017-12-26 */

/* INI 2017-12-27 */

ALTER TABLE `plopi_app_ring`.`lugares` 
ADD COLUMN `slug` VARCHAR(150) NULL DEFAULT NULL AFTER `nombre`;

ALTER TABLE `plopi_app_ring`.`users` 
ADD COLUMN `fcm_token` VARCHAR(255) NULL DEFAULT NULL AFTER `remember_token`;

/* ya migrado desktop-plopi */
/* ya migrado server-plopi */
/* ya migrado notebook-plopi */
/* FIN 2017-12-27 */


/* INI 2017-12-31 */

ALTER TABLE `plopi_app_ring`.`lugares` 
ADD COLUMN `image` VARCHAR(150) NULL DEFAULT NULL AFTER `slug`;

ALTER TABLE `plopi_app_ring`.`lugares` 
CHANGE COLUMN `image` `imagen` VARCHAR(150) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ;

/* ya migrado notebook-plopi-desktop */
/* FIN 2017-12-31 */


/* INI 2018-11-22 */
CREATE TABLE `plopi_app_ring`.`visitas` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `lugar_id` INT ZEROFILL NULL,
  `estado` INT NULL DEFAULT 1,
  `texto` VARCHAR(150) NULL,
  `respuesta` VARCHAR(150) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`));

ALTER TABLE `plopi_app_ring`.`visitas` 
ADD COLUMN `lat` FLOAT(10,6) NULL DEFAULT NULL AFTER `respuesta`,
ADD COLUMN `lng` FLOAT(10,6) NULL DEFAULT NULL AFTER `lat`;

ALTER TABLE `plopi_app_ring`.`visitas` 
ADD COLUMN `llamadas` INT NULL DEFAULT NULL AFTER `respuesta`;

ALTER TABLE `plopi_app_ring`.`visitas` 
ADD COLUMN `referencia` VARCHAR(64) NULL DEFAULT NULL AFTER `lugar_id`;

ALTER TABLE `plopi_app_ring`.`visitas` 
CHANGE COLUMN `lugar_id` `lugar_id` INT NULL DEFAULT NULL ;

/* ya migrado desktop-plopi */
/* FIN 2018-11-22 */
