CREATE TABLE IF NOT EXISTS `#__jshopping_unijax_filter_seo`
(
    `filter_hash`       varchar(128) NOT NULL UNIQUE,
    `title_en-GB`       text,
    `title_de-DE`       text,
    `title_ru-RU`       text,
    `description_en-GB` text,
    `description_de-DE` text,
    `description_ru-RU` text,
    `text_en-GB`        text,
    `text_de-DE`        text,
    `text_ru-RU`        text,
    `breadcrumbs_en-GB` text,
    `breadcrumbs_de-DE` text,
    `breadcrumbs_ru-RU` text,
    `link_encode`       text,
    primary key (`filter_hash`)
);