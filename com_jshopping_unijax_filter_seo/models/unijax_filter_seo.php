<?php

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

defined('_JEXEC') or die;

class JshoppingModelUnijax_filter_seo extends BaseDatabaseModel
{
	public function getAllFilters($limitstart = null, $limit = null)
	{
		$db    = Factory::getDBO();
		$lang  = JSFactory::getLang();
		$query = $db->getQuery(true);

		$query->select($db->quoteName(
			['filter_hash', $lang->get("title"), $lang->get("description"), $lang->get("alias")],
			['filter_hash', 'title', 'description', 'alias']
		))
			->from($db->quoteName('#__jshopping_unijax_filter_seo'));
		$db->setQuery($query, $limitstart, $limit);

		return $db->loadObjectList();
	}

	function getAllFiltersCount()
	{
		$db    = Factory::getDBO();
		$query = $db->getQuery(true);

		$query->select('COUNT(' . $db->quoteName('filter_hash') . ')')
			->from($db->quoteName('#__jshopping_unijax_filter_seo'));

		$db->setQuery($query);

		return $db->loadResult();
	}
}