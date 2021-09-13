<?php

use Joomla\CMS\Plugin\CMSPlugin;

defined('_JEXEC') or die;

class PlgJshoppingmenuUnijax_Filter_SEO extends CMSPlugin
{
	/**
	 * Application object.
	 *
	 * @var    JApplicationCms
	 * @since  1.0.0
	 */
	protected $app;

	/**
	 * Database object.
	 *
	 * @var    JDatabaseDriver
	 * @since  1.0.0
	 */
	protected $db;

	/**
	 * Load plugin language file automatically so that it can be used inside component
	 *
	 * @var    boolean
	 * @since  1.0.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * Constructor.
	 *
	 * @param   object  &$subject  The object to observe.
	 * @param   array    $config   An optional associative array of configuration settings.
	 *
	 * @since   1.0.0
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