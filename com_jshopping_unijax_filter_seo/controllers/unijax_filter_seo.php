<?php

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Pagination\Pagination;

defined('_JEXEC') or die;

class JshoppingControllerUnijax_filter_seo extends BaseController
{
	public function __construct($config = array())
	{

		parent::__construct($config);
		JSFactory::loadExtLanguageFile('addon_states');
		$this->registerTask('add', 'edit');
		$this->registerTask('apply', 'save');
		checkAccessController("unijax_filter_seo");
	}

	function display($cachable = false, $urlparams = false)
	{
		$mainframe  = JFactory::getApplication();
		$context    = "jshoping.list.admin.unijax_filter_seo";
		$limit      = $mainframe->getUserStateFromRequest($context . 'limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($context . 'limitstart', 'limitstart', 0, 'int');

		/** @var JshoppingModelUnijax_filter_seo $model */
		$model = $this->getModel('unijax_filter_seo');

		$total = $model->getAllFiltersCount();

		$pageNav = new Pagination($total, $limitstart, $limit);

		$rows = $model->getAllFilters($pageNav->limitstart, $pageNav->limit);

		var_dump($rows);

		/** @var JshoppingViewUnijax_filter_seo $view */
		$view = $this->getView("unijax_filter_seo", 'html');
		$view->setLayout("list");
		$view->assign('rows', $rows);
		$view->assign('pageNav', $pageNav);
		$view->displayList();
	}
}