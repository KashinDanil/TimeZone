CREATE TABLE `dst`
(
    `city_id`   char(36) CHARACTER SET ascii NOT NULL,
    `gmtOffset` INT(11)                      NOT NULL DEFAULT 0,
    `dst`       INT(1)                       NOT NULL DEFAULT 0,
    `zoneStart` TIMESTAMP,
    `zoneEnd`   TIMESTAMP,
    UNIQUE `city_id` (`city_id`),
    CONSTRAINT `city_id`
        FOREIGN KEY (`city_id`) REFERENCES `city` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;