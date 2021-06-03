<?php

use Joomla\CMS\Factory;
use Joomla\CMS\Table\Table;

defined('_JEXEC') or die;

class JshoppingModelUnijax_filter_seo extends JshoppingModelBaseadmin
{
	public function getAllFilters($limitstart = null, $limit = null)
	{
		$db    = Factory::getDBO();
		$lang  = JSFactory::getLang();
		$query = $db->getQuery(true);

		$query->select(
			$db->quoteName(
				['filter_hash', $lang->get("title"), $lang->get("description"), 'link_encode'],
				['filter_hash', 'title', 'description', 'link_encode']
			)
		)
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

	public function save(array $post, $image = null)
	{
		Table::addIncludePath(
			JPATH_ADMINISTRATOR . '/components/com_jshopping/tables/'
		);
		$filter_seo = JSFactory::getTable('unijax_filter_seo', 'jshop');
		$filter_seo->bind($post);

		if (!$filter_seo->store())
		{
			Factory::getApplication()->enqueueMessage(
				_JSHOP_ERROR_SAVE_DATABASE, 'error'
			);

			return 0;
		}

		return $filter_seo;
	}
}