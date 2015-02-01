CREATE TABLE IF NOT EXISTS civirule_action (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(64) NULL,
  label VARCHAR(128) NULL,
  api_entity VARCHAR(45) NULL,
  api_action VARCHAR(45) NULL,
  data_selector_id INT UNSIGNED NULL,
  is_active TINYINT NULL DEFAULT 1,
  PRIMARY KEY (id),
  UNIQUE INDEX id_UNIQUE (id ASC),
  INDEX fk_data_selector_idx (data_selector_id ASC),
  CONSTRAINT fk_data_selector
    FOREIGN KEY (data_selector_id)
    REFERENCES civirule_data_selector (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
