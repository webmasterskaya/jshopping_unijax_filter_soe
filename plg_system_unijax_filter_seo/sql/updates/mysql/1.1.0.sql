alter table `#__jshopping_unijax_filter_seo`
    add `name_en-GB` text null after `title_ru-RU`;

alter table `#__jshopping_unijax_filter_seo`
    add `name_de-DE` text null after `name_en-GB`;

alter table `#__jshopping_unijax_filter_seo`
    add `name_ru-RU` text null after `name_de-DE`;

alter table `#__jshopping_unijax_filter_seo`
    add `short_description_en-GB` text null after `description_ru-RU`;

alter table `#__jshopping_unijax_filter_seo`
    add `short_description_de-DE` text null after `short_description_en-GB`;

alter table `#__jshopping_unijax_filter_seo`
    add `short_description_ru-RU` text null after `short_description_de-DE`;

