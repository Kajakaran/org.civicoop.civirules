CREATE TABLE IF NOT EXISTS civirule_rule_condition (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  rule_id INT UNSIGNED NULL,
  condition_link VARCHAR(3) NULL,
  condition_id INT UNSIGNED NULL,
  condition_params TEXT NULL,
  is_active TINYINT NULL DEFAULT 1,
  PRIMARY KEY (id),
  UNIQUE INDEX id_UNIQUE (id ASC),
  INDEX fk_rc_rule_idx (rule_id ASC),
  INDEX fk_rc_condition_idx (condition_id ASC),
  CONSTRAINT fk_rc_rule
    FOREIGN KEY (rule_id)
    REFERENCES civirule_rule (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_rc_condition
    FOREIGN KEY (condition_id)
    REFERENCES civirule_condition (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
