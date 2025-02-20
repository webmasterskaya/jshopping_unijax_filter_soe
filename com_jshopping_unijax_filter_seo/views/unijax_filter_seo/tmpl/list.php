<?php

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

defined('_JEXEC') or die;

$rows = $this->rows;
$pageNav = $this->pageNav;
$i = 0;

$classMain = '';
if ($this->sidebar)
{
	$classMain = ' class="span10"';
	echo '<div id="j-sidebar-container" class="span2">' . $this->sidebar
		. '</div>';
} ?>

<div id="j-main-container"<?php echo $classMain; ?>>
	<?php displaySubmenuOptions(); ?>
	<form action="<?php echo JRoute::_(
		'index.php?option=com_jshopping&controller=unijax_filter_seo&view=list'
	); ?>"
		  method="post" id="adminForm"
		  name="adminForm">
		<table class="table table-striped">
			<thead>
			<tr>
				<th width="1%" class="center">
					<input type="checkbox" name="checkall-toggle" value=""
						   title="<?php echo Text::_('JGLOBAL_CHECK_ALL'); ?>"
						   onclick="Joomla.checkAll(this)"/>
				</th>
				<th class="left">
					Хэш фильтра
				</th>
				<th class="left">
					Title
				</th>
				<th class="left">
					H1
				</th>
				<th class="left">
					Description
				</th>
				<th class="left">
					Хлебные крошки
				</th>
				<th class="center">
					Посмотреть на сайте
				</th>
			</tr>
			</thead>
			<tbody>
			<?php $count = count($rows); ?>
			<?php foreach ($rows as $row): ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center">
						<?php echo HTMLHelper::_(
							'grid.id', $i, $row->filter_hash
						); ?>
					</td>
					<td class="left">
						<a href="index.php?option=com_jshopping&controller=unijax_filter_seo&task=edit&filter_hash=<?php echo $row->filter_hash; ?>">
							<?php echo $row->filter_hash; ?>
						</a>
					</td>
					<td class="left">
						<?php echo $row->title; ?>
					</td>
					<td class="left">
						<?php echo $row->name; ?>
					</td>
					<td class="left">
						<?php echo $row->description; ?>
					</td>
					<td class="left">
						<?php echo $row->breadcrumbs; ?>
					</td>
					<td class="center">
						<?php $link = Route::link(
							'site',
							base64_decode($row->link_encode)
						);
						echo HTMLHelper::link(
							$link, 'На сайт', ['target' => '_blank']
						);
						?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>

		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="hidemainmenu" value="0"/>
		<input type="hidden" name="boxchecked" value="0"/>
	</form>
</div>
