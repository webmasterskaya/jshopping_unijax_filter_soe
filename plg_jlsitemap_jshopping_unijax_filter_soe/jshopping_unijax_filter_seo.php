<?php

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\CMSPlugin;

require_once JPATH_SITE . '/components/com_jshopping/lib/factory.php';

class plgJLSitemapJshopping_Unijax_Filter_SEO extends CMSPlugin
{
	protected $autoloadLanguage = true;

	public function onGetUrls(&$urls, $config)
	{
		$changefreq = $config->get('changefreq', 'weekly');
		$priority   = $config->get('priority', '0.5');
		$multilang  = $config->get('multilanguage');

		$db = Factory::getDbo();

		$defaultLanguage = ComponentHelper::getParams('com_languages')->get(
			'site', 'en-GB'
		);

		if ($multilang)
		{
			try
			{
				$db->setQuery(
					$db->getQuery(true)
						->select($db->qn('language'))
						->from($db->qn('#__jshopping_languages'))
						->where($db->qn('publish') . ' = 1')
				);
				$languages = $db->loadColumn();
			}
			catch (\RuntimeException $e)
			{
				$languages = array($defaultLanguage);
			}
		}
		else
		{
			$languages = array($defaultLanguage);
		}

		try
		{
			$query = $db->getQuery(true);

			$query->select($db->qn('link_encode'))
				->from($db->qn('#__jshopping_unijax_filter_seo'));

			foreach ($languages as $lang)
			{
				$query->select($db->qn('name_' . $lang));
				$query->select($db->qn('title_' . $lang));
			}

			$db->setQuery($query);

			$links = $db->loadObjectList() ?: [];
		}
		catch (\RuntimeException $e)
		{
			$links = [];
		}

		foreach ($links as $link)
		{
			foreach ($languages as $lang)
			{
				$url        = new stdClass();
				$url->type  = Text::_('JoomShopping - Ссылка на фильтр');
				$url->title = $link->{'title_' . $lang} ?: $link->{'name_' . $lang};
				$url->loc   = base64_decode($link->link_encode);

				if ($multilang)
				{
					$url->alternates = array();
					foreach ($languages as $tag)
					{
						$url->alternates[$tag] = $url->loc . '&lang=' . $tag;
					}
					$url->loc .= '&lang=' . $lang;
				}
				else
				{
					$url->alternates = false;
				}

				$url->changefreq = $changefreq;
				$url->priority   = $priority;
				$url->lastmod    = Date::getInstance()->toSql();
				$url->exclude    = false;

				$urls[] = $url;
			}
		}

		return $urls;
	}
}