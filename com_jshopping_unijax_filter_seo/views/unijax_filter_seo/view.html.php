<?php

use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class JshoppingViewUnijax_filter_seo extends HtmlView
{
	public $rows;
	public $pageNav;

	function displayList($tpl = null){
		JToolBarHelper::title( 'SEO для фильтра', 'generic.png' );
		JToolBarHelper::addNew();
		JToolBarHelper::deleteList();
		parent::display($tpl);
	}

	function displayEdit($tpl = null){
		JToolBarHelper::title( 'Редактирование параметров SEO для фильтра', 'generic.png' );
		JToolBarHelper::save();
		JToolBarHelper::spacer();
		JToolBarHelper::apply();
		JToolBarHelper::spacer();
		JToolBarHelper::cancel();
		parent::display($tpl);
	}
}