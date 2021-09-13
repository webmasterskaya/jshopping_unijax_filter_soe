<?php

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Uri\Uri;

defined('_JEXEC') or die;

class PlgSystemUnijax_Filter_SEO extends CMSPlugin
{
	/**
	 * Application object.
	 *
	 * @var    CMSApplication
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
	 * @var unijax_filter_seo_helper
	 *
	 * @since 1.0.0
	 */
	protected $filterHelper;


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

					$filter_hash = unijax_filter_seo_helper::getFilterHash();
					$link_encode = unijax_filter_seo_helper::getLinkEncode();
					$clear_query = unijax_filter_seo_helper::parseClearQuery($this->app->input);

					if (empty($clear_query))
					{
						return;
					}

					$query = [
						'option'       => 'com_jshopping',
						'controller'   => 'unijax_filter_seo',
						'task'         => 'add',
						'hidemainmenu' => 0,
						'boxchecked'   => 0,
					];

					$link = Uri::root() . 'administrator/index.php?'
						. Uri::buildQuery($query);

					$module->content = str_replace(
						'method="post"', 'method="get"', $module->content
					);

					ob_start(); ?>
					<form action="<?php echo $link; ?>" method="post"
						  target="_blank">
						<input type="hidden" name="filter_hash"
							   value="<?php echo $filter_hash; ?>">
						<input type="hidden" name="link_encode"
							   value="<?php echo $link_encode; ?>">
						<input type="submit"
							   class="btn btn-primary uk-button uk-button-primary"
							   style="width: 100%"
							   value="Редактировать SEO"
						>
					</form>
					<?php
					$module->content .= ob_get_clean();
				}
			}
		}
	}
}