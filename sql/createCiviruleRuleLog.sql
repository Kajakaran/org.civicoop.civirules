CREATE TABLE IF NOT EXISTS civirule_rule_log (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  rule_id INT UNSIGNED NULL,
  contact_id INT UNSIGNED NULL,
  log_date DATETIME NOT NULL,
  PRIMARY KEY (id),
  INDEX rule_idx (rule_id ASC),
  INDEX contact_idx (contact_id ASC),
  INDEX rule_contact_idx (rule_id ASC, contact_id ASC))
  ENGINE = InnoDB
  DEFAULT CHARACTER SET = utf8
  COLLATE = utf8_general_ci
