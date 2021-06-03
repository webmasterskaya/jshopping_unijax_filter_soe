<?php

use Joomla\CMS\Pagination\Pagination;
use Joomla\CMS\Table\Table;

defined('_JEXEC') or die;

class JshoppingControllerUnijax_filter_seo extends JshoppingControllerBaseadmin
{
	/**
	 * @var \Joomla\Input\Input
	 */
	protected $input;

	public function __construct($config = array())
	{
		parent::__construct($config);
		checkAccessController("unijax_filter_seo");
		addSubmenu("other");
	}

	function display($cachable = false, $urlparams = false)
	{
		$mainframe  = JFactory::getApplication();
		$context    = "jshoping.list.admin.unijax_filter_seo";
		$limit      = $mainframe->getUserStateFromRequest(
			$context . 'limit', 'limit', $mainframe->getCfg('list_limit'), 'int'
		);
		$limitstart = $mainframe->getUserStateFromRequest(
			$context . 'limitstart', 'limitstart', 0, 'int'
		);

		/** @var JshoppingModelUnijax_filter_seo $model */
		$model = $this->getModel('unijax_filter_seo');

		$total = $model->getAllFiltersCount();

		$pageNav = new Pagination($total, $limitstart, $limit);

		$rows = $model->getAllFilters($pageNav->limitstart, $pageNav->limit);

		/** @var JshoppingViewUnijax_filter_seo $view */
		$view = $this->getView("unijax_filter_seo", 'html');
		$view->setLayout("list");
		$view->rows    = $rows;
		$view->pageNav = $pageNav;
		$view->sidebar = JHtmlSidebar::render();
		$view->displayList();
	}

	function edit()
	{
		$mainframe = JFactory::getApplication();

		$filter_hash = $this->input->getCmd("filter_hash");

		if (empty($filter_hash))
		{
			$mainframe->enqueueMessage(
				'Нельзя просто так взять и добавить новый итем. Нужно передать хэш фильтра! Добавляйте с фронта!',
				'error'
			);

			$mainframe->redirect(
				'index.php?option=com_jshopping&controller=unijax_filter_seo'
			);
		}

		Table::addIncludePath(
			JPATH_ADMINISTRATOR . '/components/com_jshopping/tables/'
		);
		$filter_seo = JSFactory::getTable('unijax_filter_seo', 'jshop');
		$filter_seo->load($filter_hash);

		$edit = (int) !empty($filter_seo->filter_hash);

		if (!$edit)
		{
			$filter_seo->filter_hash = $filter_hash;
			$filter_seo->link_encode = $this->input->getCmd("link_encode");
		}

		$_lang     = JSFactory::getModel("languages");
		$languages = $_lang->getAllLanguages(1);
		$multilang = count($languages) > 1;

		$view = $this->getView("unijax_filter_seo", 'html');
		$view->setLayout("edit");
		$view->edit         = $edit;
		$view->filter_seo   = $filter_seo;
		$view->languages    = $languages;
		$view->etemplatevar = '';
		$view->multilang    = $multilang;
		$view->displayEdit();
	}

	public function getUrlEditItem($id){
		return "index.php?option=com_jshopping&controller=".$this->getNameController()."&task=edit&filter_hash=".$id;
	}
}