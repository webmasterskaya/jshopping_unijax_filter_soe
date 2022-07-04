<?php

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Uri\Uri;

defined('_JEXEC') or die;

class PlgJshoppingproductsUnijax_Filter_SEO extends CMSPlugin
{
	/**
	 * Application object.
	 *
	 * @var    JApplicationCms
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
	protected $isFilter = false;
	protected $isUnijaxFilter = false;
	protected $isPrepareUnijaxFilter = false;
	/**
	 * @var unijax_filter_seo_helper
	 */
	private $filterHelper;
	/**
	 * @var modJshopping_Unijax_FilterHelper|null
	 */
	private $modHelper;
	private $type;
	/**
	 * @var array
	 */
	private $clearQuery;
	/**
	 * @var mixed
	 */
	private $filterURL;
	/**
	 * @var string
	 */
	private $option;
	/**
	 * @var bool
	 */
	private $enableFilter = false;
	/**
	 * @var mixed|multiLangField
	 */
	private $lang;
	/**
	 * @var int
	 */
	private $manufacturer_id;
	/**
	 * @var int
	 */
	private $category_id;
	/**
	 * @var int
	 */
	private $vendor_id;
	/**
	 * @var int
	 */
	private $label_id;
	/**
	 * @var string
	 */
	private $controller;
	/**
	 * @var bool
	 */
	private $joomShopping;
	/**
	 * @var string
	 */
	private $contextfilter;

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
		$this->app = Factory::getApplication();

		// Фильтр можно использовать только в JShopping
		$component = $this->app->input->getCmd('option');
		if ($component == 'com_jshopping')
		{
			$this->enableFilter = true;
		}

		// Проверка на контроллеры, в которых можно использовать фильтр
		if ($this->enableFilter)
		{
			// Список доступных для работы фильтра контроллеров
			$allowed_controllers = [
				'category',
				'manufacturer',
				'vendor',
				'products'
			];

			$controller = $this->app->input->getCmd('controller');

			if (!in_array($controller, $allowed_controllers))
			{
				$this->enableFilter = false;
			}
		}

		$isFilter            = $this->app->input->get(
			'isFilter', '-1', 'string'
		);
		$urlUnijaxFilter     = $this->app->input->get(
			'urlUnijaxFilter', '-1', 'string'
		);
		$prepareUnijaxFilter = $this->app->input->get(
			'prepareUnijaxFilter', '-1', 'string'
		);

		if ($isFilter !== '-1')
		{
			$this->isFilter = true;
		}

		if ($urlUnijaxFilter !== '-1')
		{
			$this->isUnijaxFilter = true;
			$this->filterURL      = $urlUnijaxFilter;
		}

		if ($prepareUnijaxFilter !== '-1')
		{
			$this->isPrepareUnijaxFilter = true;
		}

		$this->lang            = JSFactory::getLang();
		$this->category_id     = $this->app->input->getInt('category_id');
		$this->manufacturer_id = $this->app->input->getInt('manufacturer_id');
		$this->vendor_id       = $this->app->input->getInt('vendor_id');
		$this->label_id        = $this->app->input->getInt('label_id');
		$this->controller      = $this->app->input->getCmd('controller');
		$this->joomShopping    = $this->app->input->getCmd('option')
			== 'com_jshopping';

		if ($this->joomShopping)
		{
			switch ($this->controller)
			{
			case 'category':
				if ($this->category_id)
				{
					$this->contextfilter = 'jshoping.list.front.product.cat.'
						. $this->category_id;
				}
				break;
			case 'manufacturer':
				if ($this->manufacturer_id)
				{
					$this->contextfilter = 'jshoping.list.front.product.manf.'
						. $this->manufacturer_id;
				}
				break;
			case 'vendor':
				if ($this->vendor_id)
				{
					$this->contextfilter = 'jshoping.list.front.product.vendor.'
						. $this->vendor_id;
				}
				break;
			case 'products':
				$this->contextfilter = 'jshoping.list.front.product.fulllist';
				break;
			default:
				$this->contextfilter = '';
				break;
			}
		}

		parent::__construct($subject, $config);
	}

	public function onBeforeLoadProductList()
	{
		$this->setFilterContext();
		if ($this->isPrepareUnijaxFilter)
		{
			return;
		}

		require_once JPATH_ADMINISTRATOR
			. '/components/com_jshopping/helpers/unijax_filter_seo.php';
		$this->filterHelper = unijax_filter_seo_helper::getInstance();
		$this->modHelper    = $this->filterHelper->getModHelper();
		$input              = $this->app->input;

		$this->clearQuery = $this->filterHelper->parseClearQuery($input);

		if (empty($this->clearQuery) && $this->contextfilter)
		{
			$contextFilterArr = array(
				'pricefrom',
				'priceto',
				'categorys',
				'manufacturers',
				'vendors',
				'characteristics',
				'd_attributes',
				'attributes',
				'labels',
				'delivery_times',
				'photo',
				'availability',
				'sales',
				'additional_prices',
				'reviews'
			);
			foreach ($contextFilterArr as $contextFilter)
			{
				$this->app->setUserState(
					$this->contextfilter . $contextFilter, null
				);
			}

			return;
		}
		else
		{
			foreach ($this->clearQuery as $param => $value)
			{
				$input->set(
					$param,
					array_map(
						[$this->filterHelper, 'encodeUrl'],
						$value
					)
				);
			}
		}

		if ($this->isUnijaxFilter)
		{
			$arQuery = [];
			$this->filterHelper->buildClearQuery($this->clearQuery, $arQuery);

			$redirectTo = base64_decode($this->filterURL) . '?'
				. Uri::buildQuery($arQuery);
			$this->app->redirect($redirectTo);
		}
	}

	private function setFilterContext()
	{
		if (!$this->enableFilter)
		{
			return;
		}
	}

	public function onBeforeQueryCountProductList(
		$type, &$adv_result, &$adv_from, &$adv_query, &$filters
	)
	{
		if ($this->isPrepareUnijaxFilter)
		{
			return;
		}
		$this->type = $type;
	}

	public function onBeforeQueryGetProductList(
		$type, &$adv_result, &$adv_from, &$adv_query, &$order_query, &$filters
	)
	{
		if ($this->isPrepareUnijaxFilter)
		{
			return;
		}
		$this->type = $type;
	}

	public function onBeforeDisplayProductListView(&$view, &$productlist)
	{
		if ($this->isPrepareUnijaxFilter)
		{
			return;
		}

		if (!empty($this->clearQuery))
		{


			$meta = (object) [
				'title'       => '',
				'description' => ''
			];

			$doc = Factory::getDocument();

			if ($this->joomShopping)
			{
				$filter_hash = unijax_filter_seo_helper::getFilterHash();
				Table::addIncludePath(
					JPATH_ADMINISTRATOR . '/components/com_jshopping/tables/'
				);
				$filter_seo = JSFactory::getTable('unijax_filter_seo', 'jshop');
				$filter_seo->load($filter_hash);

				$breadcrumbs_item_name = 'Фильтр';

				if (!empty($filter_seo->filter_hash))
				{
					$lang              = JSFactory::getLang();
					$meta->title       = $filter_seo->{$lang->get('title')};
					$meta->description = $filter_seo->{$lang->get(
						'description'
					)};

					$text = $filter_seo->{$lang->get('text')};

					if (!empty($text))
					{
						$view->category->description = $text;
					}

					$short_description = $filter_seo->{$lang->get(
						'short_description'
					)};

					if (!empty($short_description))
					{
						$view->category->short_description = $short_description;
					}

					$h1 = $filter_seo->{$lang->get(
						'name'
					)};

					if (!empty($h1))
					{
						$view->category->name = $h1;
					}

					$breadcrumbs = $filter_seo->{$lang->get('breadcrumbs')};

					if (!empty($breadcrumbs))
					{
						$breadcrumbs_item_name = $breadcrumbs;
					}
				}
				else
				{
					$doc->setMetadata('robots', 'noindex,nofollow');
				}

				// Добавляем в крошки
				$pathway = $this->app->getPathway();
				$pathway->addItem($breadcrumbs_item_name);

				$title_set = false;
				if (!empty($meta->title))
				{
					$doc->setTitle($meta->title);
					$title_set = true;
				}

				$description_set = false;
				if (!empty($meta->description))
				{
					$doc->setDescription($meta->description);
					$description_set = true;
				}

				$conditions      = [];
				$full_conditions = [];

				foreach ($this->clearQuery as $name => $value)
				{
					switch ($name)
					{
					case 'pricefrom':
					case 'priceto':
					case 'additional_prices':
						$conditions[] = 'цене';
						if ($name == 'pricefrom')
						{
							$full_conditions[] = 'цена от ' . $value;
						}
						if ($name == 'priceto')
						{
							$full_conditions[] = 'цена до ' . $value;
						}
						break;
					case 'categorys':
						$conditions[]   = 'категории';
						$categories     = JSFactory::getTable(
							'category', 'jshop'
						);
						$all_categories = $categories->getAllCategories(
							1, 1, false
						);
						$cat_conditions = [];

						foreach ($all_categories as $cat)
						{
							if (is_array($value))
							{
								if (in_array($cat->category_id, $value))
								{
									$cat_conditions[] = $cat->name;
								}
							}
							else
							{
								if ($cat->category_id == $value)
								{
									$cat_conditions[] = $cat->name;
									break;
								}
							}
						}

						if (count($cat_conditions) == 1)
						{
							$full_conditions[] = 'категория ' . implode(
									$cat_conditions
								);
						}
						else
						{
							if (count($cat_conditions) > 2)
							{
								$full_conditions[] = 'категории ' . implode(
										', ', $cat_conditions
									);
							}
							else
							{
								$full_conditions[] = 'категории ' . implode(
										' и ', $cat_conditions
									);
							}
						}
						break;
					case 'manufacturers':
						$conditions[] = 'производителю';

						$man_conditions = [];

						$manufacturers = JSFactory::getTable(
							'manufacturer', 'jshop'
						);
						$all_manufacturers
						               = $manufacturers->getAllManufacturers(
							1
						);
						foreach ($all_manufacturers as $man)
						{
							if (is_array($value))
							{
								if (in_array($man->manufacturer_id, $value))
								{
									$man_conditions[] = $man->name;
								}
							}
							else
							{
								if ($man->category_id == $value)
								{
									$man_conditions[] = $man->name;
									break;
								}
							}
						}

						if (count($man_conditions) == 1)
						{
							$full_conditions[] = 'производитель ' . implode(
									$man_conditions
								);
						}
						else
						{
							if (count($man_conditions) > 2)
							{
								$full_conditions[] = 'производители ' . implode(
										', ', $man_conditions
									);
							}
							else
							{
								$full_conditions[] = 'производители ' . implode(
										' и ', $man_conditions
									);
							}
						}
						break;
					case 'vendors':
						$conditions[] = 'продавцу';
						break;
					case 'characteristics':
					case 'd_attributes':
					case 'attributes':
						$conditions[] = 'характеристикам';
						if ($name == 'characteristics')
						{
							$display_characteristics
								                      = $this->filterHelper->getModHelper(
							)->getDisplayCharacteristics();
							$all_characteristics
								                      = $this->filterHelper->getModHelper(
							)->allProductExtraField;
							$selected_characteristics = array_keys($value);

							foreach ($all_characteristics as $characteristic)
							{
								if (in_array(
									$characteristic->id,
									$selected_characteristics
								))
								{
									$values = $value[$characteristic->id];
									if ($characteristic->type == '1')
									{
										$full_conditions[]
											= $characteristic->name . ' '
											. implode(', ', $values);
									}
								}
							}
						}
						break;
					case 'labels':
						$conditions[] = 'отметкам';
						break;
					case 'photo':
						$conditions[] = 'наличию фото';
						break;
					case 'availability':
						$conditions[] = 'доступности к заказу';
						break;
					case 'sales':
						$conditions[] = 'участию в акции';
						break;
					case 'reviews':
						$conditions[] = 'наличию отзывов';
						break;
					}
				}

				if (empty($conditions))
				{
					$conditions[] = 'параметрам';
				}

				if (count($conditions) > 2)
				{
					$conditions_string = implode(', ', $conditions);
				}
				else
				{
					$conditions_string = implode(' и ', $conditions);
				}

				$full_conditions_string = implode('; ', $full_conditions);

				switch ($this->controller)
				{
				case 'category':
					if ($this->category_id)
					{
						$category = JSFactory::getTable('category', 'jshop');
						$category->load($this->category_id);
						$category->getDescription();

						$meta->title       = $category->name;
						$meta->description = 'Товары из категории "'
							. $category->name . '"';
					}
					break;
				case 'manufacturer':
					if ($this->manufacturer_id)
					{
						$manufacturer = JSFactory::getTable(
							'manufacturer', 'jshop'
						);
						$manufacturer->load($this->manufacturer_id);
						$manufacturer->getDescription();

						$meta->title       = 'Товары производителя '
							. $manufacturer->name;
						$meta->description = 'Товары производителя "'
							. $manufacturer->name . '"';
					}
					break;
				case 'vendor':
					if ($this->vendor_id)
					{
						$vendor = JSFactory::getTable('vendor', 'jshop');
						$vendor->load($this->vendor_id);
						$vendor->getDescription();

						$meta->title       = 'Товары продавца ' . $vendor->name;
						$meta->description = 'Товары продавца "' . $vendor->name
							. '"';
					}
					break;
				default:
					$meta->title       = 'Товары';
					$meta->description = 'Товары';
					break;
				}
				$meta->title .= ' подобранные по ' . $conditions_string;
				if (empty($full_conditions_string))
				{
					$meta->description .= ' которые подошли по параметрам поиска';
				}
				else
				{
					$meta->description .= ' у которых '
						. $full_conditions_string;
				}

				if (!$title_set)
				{
					$doc->setTitle($meta->title);
					$view->category->meta_title = $meta->title;
				}
				if (!$description_set)
				{
					$doc->setDescription($meta->description);
					$view->category->meta_description = $meta->description;
				}
			}
		}
	}
}