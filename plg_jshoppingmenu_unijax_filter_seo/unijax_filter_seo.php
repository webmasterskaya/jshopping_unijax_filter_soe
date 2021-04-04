<?php

use Joomla\CMS\Plugin\CMSPlugin;

defined('_JEXEC') or die;

class PlgJshoppingmenuUnijax_Filter_SEO extends CMSPlugin
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

	function onBeforeAdminOptionPanelIcoDisplay(&$menu){
		$menu['unijax_filter_seo'] = array('SEO для фильтра', 'index.php?option=com_jshopping&controller=unijax_filter_seo', 'jshop_configuration_b.png', 1);
	}

	function onBeforeAdminOptionPanelMenuDisplay(&$menu){
		$menu['unijax_filter_seo'] = array('SEO для фильтра', 'index.php?option=com_jshopping&controller=unijax_filter_seo', 'jshop_country_list_b.png', 1);
	}
}