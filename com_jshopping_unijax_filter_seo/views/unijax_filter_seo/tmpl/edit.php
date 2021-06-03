<?php

use Joomla\CMS\Editor\Editor;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Input\Input;

defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR
	. '/components/com_jshopping/helpers/unijax_filter_seo.php';

$link = Route::link(
	'site', base64_decode($this->filter_seo->link_encode)
);

$filter_uri   = Uri::getInstance($link);
$filter_query = $filter_uri->getQuery(true);
$filter_input = new Input($filter_query);
$filter_clear = unijax_filter_seo_helper::parseClearQuery($filter_input);

if (!empty($filter_clear['categorys']))
{
	$categories     = JSFactory::getTable('category', 'jshop');
	$all_categories = $categories->getAllCategories(1, 1, false);

	$selected_categories = [];
	foreach ($all_categories as $cat)
	{
		if (is_array($filter_clear['categorys']))
		{
			if (in_array($cat->category_id, $filter_clear['categorys']))
			{
				$selected_categories[] = $cat->name;
			}
		}
		else
		{
			if ($cat->category_id == $filter_clear['categorys'])
			{
				$selected_categories[] = $cat->name;
				break;
			}
		}
	}
}

if (!empty($filter_clear['manufacturers']))
{
	$manufacturers     = JSFactory::getTable('manufacturer', 'jshop');
	$all_manufacturers = $manufacturers->getAllManufacturers(1);

	$selected_manufacturers = [];
	foreach ($all_manufacturers as $man)
	{
		if (is_array($filter_clear['manufacturers']))
		{
			if (in_array($man->manufacturer_id, $filter_clear['manufacturers']))
			{
				$selected_manufacturers[] = $man->name;
			}
		}
		else
		{
			if ($man->category_id == $filter_clear['manufacturers'])
			{
				$selected_manufacturers[] = $man->name;
				break;
			}
		}
	}
}

if (!empty($filter_clear['characteristics']))
{
	$fields                = JSFactory::getAllProductExtraField();
	$fieldvalues           = JSFactory::getAllProductExtraFieldValue();
	$selected_fields       = [];
	$selected_fields_names = [];
	$selected_fields_ids   = array_keys($filter_clear['characteristics']);
	foreach ($fields as $field)
	{
		if (in_array($field->id, $selected_fields_ids))
		{
			$selected_fields_names[$field->id] = $field->name;
			if ($field->type == 1)
			{
				$selected_fields[$field->id]
					= $filter_clear['characteristics'][$field->id];
			}
		}
	}
}

function printFiltersRow($key, $value, $collspan = 1)
{
	echo "<tr>";
	if ($collspan > 1)
	{
		echo "<td class='key' collspan='" . $collspan . "'>" . $key . "</td>";
	}
	else
	{
		echo "<td class='key'>" . $key . "</td>";
	}
	echo "<td>" . $value . "</td>";
	echo "</tr>";
}

?>
<div class="jshop_edit">
	<table class="admintable" style="max-width: 100%;">
		<tr>
			<td class="key">
				Хэш фильтра
			</td>
			<td>
				<span><?php echo $this->filter_seo->filter_hash; ?></span>
			</td>
		</tr>
		<tr>
			<td class="key">
				Ссылка
			</td>
			<td>
				<?php echo HTMLHelper::link(
					$link,
					$link,
					['target' => '_blank']
				); ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				Применённые фильтры
			</td>
			<td>
				<table class="admintable table-striped">
					<thead>
					<tr>
						<th class="key">Фильтр</th>
						<th>Знаения</th>
					</tr>
					</thead>
					<?php foreach ($filter_clear as $name => $value): ?>
						<tbody>
						<?php switch ($name):
						case 'pricefrom':
							printFiltersRow('Цена от', $value);
							break;
						case 'priceto':
							printFiltersRow('Цена до', $value);
							break;
						case 'categorys':
							$selected_categories_counter = 0;
							foreach ($selected_categories as $selected_category)
							{
								if ($selected_categories_counter == 0)
								{
									printFiltersRow(
										'Категории', $selected_category,
										count($selected_categories)
									);
								}
								else
								{
									printFiltersRow('', $selected_category);
								}
								$selected_categories_counter++;
							}
							break;
						case 'manufacturers':
							$selected_manufacturers_counter = 0;
							foreach (
								$selected_manufacturers as
								$selected_manufacturer
							)
							{
								if ($selected_manufacturers_counter == 0)
								{
									printFiltersRow(
										'Производители', $selected_manufacturer,
										count($selected_manufacturers)
									);
								}
								else
								{
									printFiltersRow('', $selected_manufacturer);
								}
								$selected_manufacturers_counter++;
							}
							break;
						case 'characteristics':
							foreach (
								$selected_fields as $field_id => $selected_field
							)
							{
								$selected_field_counter = 0;
								foreach ($selected_field as $value)
								{
									if ($selected_field_counter == 0)
									{
										printFiltersRow(
											$selected_fields_names[$field_id],
											$value,
											count($selected_field)
										);
									}
									else
									{
										printFiltersRow('', $value);
									}
									$selected_field_counter++;
								}
							}
							break;
						endswitch; ?>
						</tbody>
					<?php endforeach; ?>
				</table>
			</td>
		</tr>
	</table>

	<hr>

	<form action="index.php?option=com_jshopping&controller=unijax_filter_seo"
		  method="post" enctype="multipart/form-data" name="adminForm"
		  id="adminForm">
		<ul class="nav nav-tabs">
			<?php $i = 0;
			foreach ($this->languages as $lang)
			{
				$i++; ?>
				<li <?php if ($i == 1){ ?>class="active"<?php } ?>>
					<a href="#<?php print $lang->language . '-page' ?>"
					   data-toggle="tab">
						SEO <?php if ($this->multilang) { ?> (<?php print $lang->lang ?>)
							<img class="tab_image"
								 src="components/com_jshopping/images/flags/<?php print $lang->lang ?>.gif" /><?php } ?>
					</a>
				</li>
			<?php } ?>
		</ul>
		<div id="editdata-document" class="tab-content">
			<?php
			$i = 0;
			foreach ($this->languages as $lang)
			{
				$i++;
				$description      = "text_" . $lang->language;
				$meta_title       = "title_" . $lang->language;
				$meta_description = "description_" . $lang->language;
				$breadcrumbs      = "breadcrumbs_" . $lang->language;
				?>
				<div id="<?php print $lang->language . '-page' ?>"
					 class="tab-pane<?php if ($i == 1) { ?> active<?php } ?>">
					<div class="col100">
						<table class="admintable" style="max-width: 100%;">


							<tr>
								<td class="key">
									<?php echo _JSHOP_META_TITLE; ?>
								</td>
								<td>
									<input type="text" class="inputbox w100"
										   name="<?php print $meta_title ?>"
										   value="<?php print $this->filter_seo->{$meta_title} ?>"/>
								</td>
							</tr>
							<tr>
								<td class="key">
									<?php echo _JSHOP_META_DESCRIPTION; ?>
								</td>
								<td>
									<input type="text" class="inputbox w100"
										   name="<?php print $meta_description; ?>"
										   value="<?php print $this->filter_seo->{$meta_description}; ?>"/>
								</td>
							</tr>
							<tr>
								<td class="key">
									Подпись в хлебных крошках
								</td>
								<td>
									<input type="text" class="inputbox w100"
										   name="<?php print $breadcrumbs; ?>"
										   value="<?php print $this->filter_seo->{$breadcrumbs}; ?>"/>
								</td>
							</tr>
							<tr>
								<td class="key">
									<?php echo _JSHOP_DESCRIPTION; ?>
								</td>
								<td>
									<?php
									$editor_conf
										    = Factory::getConfig()
										->get(
											'editor'
										);
									$editor = Editor::getInstance($editor_conf);
									print $editor->display(
										$description,
										$this->filter_seo->{$description},
										'100%', '350', '75',
										'20'
									);
									?>
								</td>
							</tr>
						</table>
					</div>
					<div class="clr"></div>
				</div>
			<?php } ?>
		</div>
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="filter_hash"
			   value="<?php echo $this->filter_seo->filter_hash; ?>">
		<input type="hidden" name="link_encode"
			   value="<?php echo $this->filter_seo->link_encode; ?>">
		<?php echo HTMLHelper::_('form.token'); ?>
		<script type="text/javascript">
			Joomla.submitbutton = function (task) {
				Joomla.submitform(task, document.getElementById('adminForm'));
			}
		</script>
	</form>
</div>