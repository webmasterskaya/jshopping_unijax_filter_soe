<?php

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Installer\Adapter\PackageAdapter;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Version;

defined('_JEXEC') or die;

class pkg_jshopping_unijax_filter_seoInstallerScript
{
	/**
	 * Minimum PHP version required to install the extension.
	 *
	 * @var  string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $minimumPhp = '7.0';

	/**
	 * Minimum Joomla version required to install the extension.
	 *
	 * @var  string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $minimumJoomla = '3.9.0';

	/**
	 * Minimum MySQL version required to install the extension.
	 *
	 * @var  string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $minimumMySQL = '8.0';

	/**
	 * Minimum MariaDb version required to install the extension.
	 *
	 * @var  string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $minimumMariaDb = '10.0';

	/**
	 * Runs right before any installation action.
	 *
	 * @param   string                           $type    Type of PostFlight action.
	 * @param   InstallerAdapter|PackageAdapter  $parent  Parent object calling object.
	 *
	 * @throws  Exception
	 *
	 * @return  boolean True on success, false on failure.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	function preflight($type, $parent)
	{
		// Check compatible
		if (!$this->checkCompatible('PKG_JSHOPPING_UNIJAX_FILTER_SEO_'))
		{
			return false;
		}

		if (!Folder::exists(
			JPATH_ADMINISTRATOR . '/components/com_jshopping/helpers'
		))
		{
			Folder::create(
				JPATH_ADMINISTRATOR . '/components/com_jshopping/helpers', 0755
			);
		}

		if (!Folder::exists(
			JPATH_ADMINISTRATOR . '/components/com_jshopping/tables'
		))
		{
			Folder::create(
				JPATH_ADMINISTRATOR . '/components/com_jshopping/tables', 0755
			);
		}

		return true;
	}

	/**
	 * Method to check compatible.
	 *
	 * @param   string  $prefix  Language constants prefix.
	 *
	 * @throws  Exception
	 *
	 * @return  boolean True on success, false on failure.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected function checkCompatible($prefix = null)
	{
		// Check old Joomla
		if (!class_exists('Joomla\CMS\Version'))
		{
			JFactory::getApplication()->enqueueMessage(
				JText::sprintf(
					$prefix . 'ERROR_COMPATIBLE_JOOMLA',
					$this->minimumJoomla
				), 'error'
			);

			return false;
		}

		$app = Factory::getApplication();

		// Check PHP
		if (!(version_compare(PHP_VERSION, $this->minimumPhp) >= 0))
		{
			$app->enqueueMessage(
				Text::sprintf(
					$prefix . 'ERROR_COMPATIBLE_PHP', $this->minimumPhp
				),
				'error'
			);

			return false;
		}

		// Check joomla version
		if (!(new Version())->isCompatible($this->minimumJoomla))
		{
			$app->enqueueMessage(
				Text::sprintf(
					$prefix . 'ERROR_COMPATIBLE_JOOMLA', $this->minimumJoomla
				),
				'error'
			);

			return false;
		}

		$query = $db->getQuery(true);

		$query->select('COUNT(*)')
			->from($db->qn('#__extensions'))
			->where($db->qn('element') . ' = ' . $db->q('com_jshopping'));

		$db->setQuery($query);
		$jshopping = (int) $db->loadResult();

		if (!$jshopping)
		{
			$app->enqueueMessage(
				Text::sprintf(
					'Для работы приложения требуется установленный и активированный компонент JoomShopping'
				), 'error'
			);

			return false;
		}

		$query = $db->getQuery(true);

		$query->select('COUNT(*)')
			->from($db->qn('#__extensions'))
			->where(
				$db->qn('element') . ' = ' . $db->q(
					'mod_jshopping_unijax_filter'
				)
			);

		$db->setQuery($query);
		$jshopping_filter = (int) $db->loadResult();

		if (!$jshopping_filter)
		{
			$app->enqueueMessage(
				Text::sprintf(
					'Для работы приложения требуется установленный и активированный пакет Универсальный AJAX Фильтр'
				), 'error'
			);

			return false;
		}

		return true;
	}

	/**
	 * Runs right after any installation action.
	 *
	 * @param   string            $type    Type of PostFlight action. Possible values are:
	 * @param   InstallerAdapter  $parent  Parent object calling object.
	 *
	 * @throws  Exception
	 *
	 * @return  boolean True on success, false on failure.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	function postflight($type, $parent)
	{
		// Enable plugin
		if ($type == 'install')
		{
			$this->enablePlugin($parent);
		}

		// Refresh media
		if ($type === 'update')
		{
			(new Version())->refreshMediaVersion();
		}

		return true;
	}

	/**
	 * Enable plugin after installation.
	 *
	 * @param   InstallerAdapter  $parent  Parent object calling object.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected function enablePlugin($parent)
	{
		// Prepare plugin object
		$plugin          = new stdClass();
		$plugin->type    = 'plugin';
		$plugin->element = $parent->getElement();
		$plugin->folder  = (string) $parent->getParent()->manifest->attributes(
		)['group'];
		$plugin->enabled = 1;

		// Update record
		Factory::getDbo()->updateObject(
			'#__extensions', $plugin, array('type', 'element', 'folder')
		);
	}
}