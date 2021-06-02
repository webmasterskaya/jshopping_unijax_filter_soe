<?php

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\Registry\Registry;

defined('_JEXEC') or die;

class unijax_filter_seo_helper
{
	private static $instance;
	private static $non_sef_url;
	private $modHelper;

	private function __construct()
	{
	}

	public static function getInstance(): unijax_filter_seo_helper
	{
		if (empty(static::$instance))
		{
			static::$instance = new static();
		}

		return static::$instance;
	}

	public static function getPageHash()
	{
		return base64_encode(self::getNonSefUrl());
	}

	public static function getNonSefUrl()
	{
		if (empty(static::$non_sef_url))
		{
			$app   = Factory::getApplication();
			$input = $app->input;

			$query = [
				'option'      => $input->getCmd('option'),
				'controller'  => $input->getCmd('controller'),
				'task'        => $input->getCmd('task'),
				'category_id' => $input->getCmd('category_id'),
			];
			$query = array_filter($query);

			$clearQuery  = self::parseClearQuery($input);
			$filterQuery = [];
			self::buildClearQuery($clearQuery, $filterQuery);

			$query = array_merge($query, $filterQuery);

			static::$non_sef_url = 'index.php?' . Uri::buildQuery($query);
		}

		return static::$non_sef_url;
	}

	public static function parseClearQuery($input): array
	{
		// Описание всех доступных фильтров
		$arrFilterParams = [
			[
				'name'    => 'categorys',
				'default' => [],
				'filter'  => 'array'
			],
			[
				'name'    => 'manufacturers',
				'default' => [],
				'filter'  => 'array'
			],
			[
				'name'    => 'vendors',
				'default' => [],
				'filter'  => 'array'
			],
			[
				'name'    => 'labels',
				'default' => [],
				'filter'  => 'array'
			],
			[
				'name'    => 'pricefrom',
				'default' => '',
				'filter'  => 'string'
			],
			[
				'name'    => 'priceto',
				'default' => '',
				'filter'  => 'string'
			],
			[
				'name'    => 'photo',
				'default' => "0",
				'filter'  => 'string'
			],
			[
				'name'    => 'availability',
				'default' => "0",
				'filter'  => 'string'
			],
			[
				'name'    => 'sales',
				'default' => [],
				'filter'  => 'array'
			],
			[
				'name'    => 'additional_prices',
				'default' => [],
				'filter'  => 'array'
			],
			[
				'name'    => 'reviews',
				'default' => [],
				'filter'  => 'array'
			],
			[
				'name'    => 'delivery_times',
				'default' => [],
				'filter'  => 'array'
			],
			[
				'name'    => 'delivery_times',
				'default' => "0",
				'filter'  => 'string'
			],
			[
				'name'    => 'characteristics',
				'default' => [],
				'filter'  => 'array'
			],
			[
				'name'    => 'attributes',
				'default' => [],
				'filter'  => 'array'
			],
			[
				'name'    => 'd_attributes',
				'default' => [],
				'filter'  => 'array'
			]
		];

		$arFilterResults = [];
		foreach ($arrFilterParams as $arrFilterParam)
		{
			/** @var \Joomla\Input\Input $input */
			$arFilterResults[$arrFilterParam['name']] = $input->get(
				$arrFilterParam['name'], $arrFilterParam['default'],
				$arrFilterParam['filter']
			);
		}

		return self::preparedClearQueryArray($arFilterResults);
	}

	/**
	 * Декодирует массив с запросом
	 *
	 * @param   array  $query
	 *
	 * @return array
	 *
	 * @since 1.0.0
	 */
	protected static function preparedClearQueryArray(array $query): array
	{
		$result = [];

		foreach ($query as $key => $value)
		{
			if (is_array($value))
			{
				$value = self::preparedClearQueryArray($value);
				$value = array_filter($value);
			}
			else
			{
				$value = urldecode($value);
			}

			$result[$key] = $value;
		}

		$result = array_filter($result);

		return $result;
	}

	public static function buildClearQuery(
		iterable $data, &$result, $path = null
	)
	{
		$isFirstLevel = false;

		if ($path === null)
		{
			$isFirstLevel = true;
		}

		foreach ($data as $key => $value)
		{
			if ($isFirstLevel)
			{
				$nextLevel = $key;
			}
			else
			{
				$nextLevel = $path . '[' . $key . ']';
			}

			if (is_iterable($value))
			{
				self::buildClearQuery($value, $result, $nextLevel);
			}
			else
			{
				if ($value !== '')
				{
					$result[urlencode($nextLevel)] = urlencode($value);
				}
			}
		}
	}

	/**
	 * Рекурсивное кодирование строки в url формат
	 *
	 * @param $data
	 *
	 * @return array|string
	 *
	 * @since 1.0.0
	 */
	public static function encodeUrl($data)
	{
		if (is_array($data))
		{
			$data = array_map([self::class, 'encodeUrl'], $data);
		}
		else
		{
			$data = urlencode($data);
		}

		return $data;
	}

	public function __wakeup()
	{
		throw new \Exception("Cannot unserialize a singleton.");
	}

	public function getModHelper()
	{
		if (empty($modHelper))
		{
			require_once JPATH_SITE
				. '/modules/mod_jshopping_unijax_filter/helper.php';
			$module = ModuleHelper::getModule('mod_jshopping_unijax_filter');
			$params = new Registry;
			$params->loadString($module->params);
			$this->modHelper = modJshopping_Unijax_FilterHelper::getInstance(
				$params
			);
		}

		return $this->modHelper;
	}
}