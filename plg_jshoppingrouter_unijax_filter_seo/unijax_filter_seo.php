<?php

use Joomla\CMS\Plugin\CMSPlugin;

defined('_JEXEC') or die;

class PlgJshoppingrouterUnijax_Filter_SEO extends CMSPlugin
{
	/**
	 * Application object.
	 *
	 * @var    JApplicationCms
	 * @since  __DEPLOYMENT_VERSION__
	 */
	protected $app;

	/**
	 * Database object.
	 *
	 * @var    JDatabaseDriver
	 * @since  __DEPLOYMENT_VERSION__
	 */
	protected $db;

	/**
	 * Load plugin language file automatically so that it can be used inside component
	 *
	 * @var    boolean
	 * @since  __DEPLOYMENT_VERSION__
	 */
	protected $autoloadLanguage = true;

	protected $isFilter = false;
	protected $filterString = '';
	protected $menuItem;

	/**
	 * Constructor.
	 *
	 * @param   object  &$subject  The object to observe.
	 * @param   array    $config   An optional associative array of configuration settings.
	 *
	 * @since   __DEPLOYMENT_VERSION__
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
	}

	public function onBeforeParseRoute(&$vars, &$segments)
	{
		/*$menu           = JFactory::getApplication()->getMenu();
		$this->menuItem = $menu->getActive();
		if (!isset($this->menuItem->query['controller']) && isset($this->menuItem->query['view']))
		{
			$this->menuItem->query['controller'] = $this->menuItem->query['view'];
		}

		if (in_array($this->menuItem->query['controller'], ['category', 'manufacturer', 'vendor']))
		{
			if ($segments[0] == 'filter')
			{
				$this->isFilter     = true;
				$this->filterString = $segments[1];
				$segments[0]        = '';
			}
			else
			{
				$segments_count = count($segments);
				if ($segments[$segments_count - 2] == 'filter')
				{
					$this->isFilter     = true;
					$this->filterString = array_pop($segments);
					array_pop($segments);
				}
			}
		}*/
	}

	public function onAfterParseRoute(&$vars, &$segments)
	{
		/*if (!$vars['controller'] && $this->isFilter && $this->filterString)
		{
			$vars['controller'] = $this->menuItem->query['controller'];
			$vars['task']       = $this->menuItem->query['task'];
			if ($vars['controller'] == 'category')
			{
				$vars['category_id'] = $this->menuItem->query['category_id'];
			}
			if ($vars['controller'] == 'manufacturer')
			{
				$vars['manufacturer_id'] = $this->menuItem->query['manufacturer_id'];
			}

			try
			{
				$filters = json_decode(bzdecompress(base64_decode(urldecode(urldecode($this->filterString)))), true);
			}
			catch (\Exception $e)
			{
				throw new \Exception(_JSHOP_PAGE_NOT_FOUND, 404);
			}

			if (!empty($filters))
			{
				require_once JPATH_ADMINISTRATOR . '/components/com_jshopping/helpers/unijax_filter_seo.php';
				$filterHelper = unijax_filter_seo_helper::getInstance();
				$filterHelper->setFilterFromRequest($filters);
			}
		}*/
	}
}