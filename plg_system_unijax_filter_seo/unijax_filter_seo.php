<?php

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;

defined('_JEXEC') or die;

class PlgSystemUnijax_Filter_SEO extends CMSPlugin
{
	/**
	 * Application object.
	 *
	 * @var    CMSApplication
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
	 * @var unijax_filter_seo_helper
	 *
	 * @since __DEPLOYMENT_VERSION__
	 */
	protected $filterHelper;


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

	public function onAfterInitialise()
	{
		if ($this->app->isClient('administrator'))
		{
			$this->app->input->cookie->set(
				'unijax_filter_seo_admin',
				Factory::getUser()->id,
				(new Date('now + 1 days'))->toUnix(),
				$this->app->get('cookie_path', '/'),
				$this->app->get('cookie_domain'),
				$this->app->isSSLConnection()
			);
		}
	}

	public function onAfterRenderModule(&$module, &$attribs)
	{
		if ($this->app->isClient('administrator'))
		{
			return;
		}
		if ($userID = (int) $this->app->input->cookie->get(
			'unijax_filter_seo_admin'
		))
		{
			if (Factory::getUser($userID)->authorise('core.login.admin'))
			{
				if ($module->module == 'mod_jshopping_unijax_filter')
				{
					require_once JPATH_ADMINISTRATOR
						. '/components/com_jshopping/helpers/unijax_filter_seo.php';

					ob_start(); ?>
					<a href="#"
					   class="btn btn-primary uk-button uk-button-primary"
					   style="width: 100%" target="_blank">
						Редактировать SEO
					</a>
					<?php
					$module->content .= ob_get_clean();

					$module->content = str_replace(
						'method="post"', 'method="get"', $module->content
					);
				}
			}
		}
	}
}