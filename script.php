<?php

defined('_JEXEC') or die;

class pkg_jshopping_unijax_filter_soeInstallerScript
{

	protected $minimumPhp = '7.0';

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