CREATE TABLE IF NOT EXISTS `#__jshopping_unijax_filter_soe`
(
    `filter_hash`       varchar(255) NOT NULL UNIQUE,
    `title_en-GB`       varchar(255),
    `title_de-DE`       varchar(255),
    `title_ru-RU`       varchar(255),
    `description_en-GB` varchar(255),
    `description_de-DE` varchar(255),
    `description_ru-RU` varchar(255),
    `alias_en-GB`       varchar(255),
    `alias_de-DE`       varchar(255),
    `alias_ru-RU`       varchar(255),
    `text_en-GB`        text,
    `text_de-DE`        text,
    `text_ru-RU`        text,
    primary key (`filter_hash`)
);