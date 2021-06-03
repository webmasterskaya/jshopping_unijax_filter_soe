<?php

defined('_JEXEC') or die();
include_once JPATH_JOOMSHOPPING . '/models/productlistinterface.php';

class jshopUnijax_filter_seo extends JTableAvto
{
	function __construct(&$_db)
	{
		parent::__construct(
			'#__jshopping_unijax_filter_seo', 'filter_hash', $_db
		);

		$this->_autoincrement = false;
	}
}
