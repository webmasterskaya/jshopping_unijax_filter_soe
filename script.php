<?php

defined('_JEXEC') or die;

class pkg_jshopping_unijax_filter_soeInstallerScript
{

	protected $minimumPhp = '7.3';

	protected $minimumJoomla = '3.9.0';

	/**
	 * Runs just before any installation action is performed on the component.
	 * Verifications and pre-requisites should run in this function.
	 *
	 * @param   string     $type    - Type of PreFlight action. Possible values are:
	 *                              - * install
	 *                              - * update
	 *                              - * discover_install
	 * @param   \stdClass  $parent  - Parent object calling object.
	 *
	 * @throws Exception
	 * @return void
	 *
	 * @since __DEPLOYMENT_VERSION__
	 */
	public function preflight($type, $parent)
	{
		/*$app = JFactory::getApplication();

		// Check old Joomla!
		if (!class_exists('Joomla\CMS\Version'))
		{
			$app->enqueueMessage(JText::sprintf('PKG_JSHOPPING_COURIEREXE_ERR_COMPATIBLE_JOOMLA',
				$this->minimumJoomla), 'error');
		}

		$jversion = new JVersion();

		// Check PHP
		if (!(version_compare(PHP_VERSION, $this->minimumPhp) >= 0))
		{
			$app->enqueueMessage(JText::sprintf('PKG_JSHOPPING_COURIEREXE_ERR_COMPATIBLE_PHP',
				$this->minimumPhp), 'error');
		}

		// Check Joomla version
		if (!$jversion->isCompatible($this->minimumJoomla))
		{
			$app->enqueueMessage(JText::sprintf('PKG_JSHOPPING_COURIEREXE_ERR_COMPATIBLE_JOOMLA',
				$this->minimumJoomla), 'error');
		}*/
	}

	/**
	 * Runs right after any installation action is performed on the component.
	 *
	 * @param   string     $type    - Type of PostFlight action. Possible values are:
	 *                              - * install
	 *                              - * update
	 *                              - * discover_install
	 * @param   \stdClass  $parent  - Parent object calling object.
	 *
	 * @return void
	 *
	 * @since __DEPLOYMENT_VERSION__
	 */
	public function postflight($type, $parent)
	{

	}
}