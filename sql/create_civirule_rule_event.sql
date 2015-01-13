CREATE TABLE IF NOT EXISTS .`civirule_rule_event` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `rule_id` INT UNSIGNED NULL,
  `event_id` INT UNSIGNED NULL,
  `is_active` TINYINT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `rule_idx` (`rule_id` ASC),
  INDEX `event_idx` (`event_id` ASC),
  CONSTRAINT `fk_rule`
    FOREIGN KEY (`rule_id`)
    REFERENCES `civirule_rule` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_event`
    FOREIGN KEY (`event_id`)
    REFERENCES `civirule_event` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
