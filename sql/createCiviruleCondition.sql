CREATE TABLE IF NOT EXISTS civirule_condition (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(80) NULL,
  label VARCHAR(128) NULL,
  class_name VARCHAR(128) NULL,
  is_active TINYINT NULL DEFAULT 1,
  created_date DATE NULL,
  created_user_id INT NULL,
  modified_date DATE NULL,
  modified_user_id INT NULL,
  PRIMARY KEY (id),
  UNIQUE INDEX id_UNIQUE (id ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
