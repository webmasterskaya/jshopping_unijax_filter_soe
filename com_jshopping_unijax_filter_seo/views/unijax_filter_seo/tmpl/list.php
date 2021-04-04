<?php

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

$rows    = $this->rows;
$pageNav = $this->pageNav;
$i       = 0;

$classMain = '';
if ($this->sidebar)
{
	$classMain = ' class="span10"';
	echo '<div id="j-sidebar-container" class="span2">' . $this->sidebar . '</div>';
} ?>

<div id="j-main-container"<?php echo $classMain; ?>>
	<?php displaySubmenuOptions(); ?>
	<form action="<?php echo JRoute::_('index.php?option=com_jshopping&controller=unijax_filter_seo&view=list'); ?>"
		  method="post" id="adminForm"
		  name="adminForm">
		<table class="table table-striped">
			<thead>
			<tr>
				<th width="1%" class="center">
					<input type="checkbox" name="checkall-toggle" value=""
						   title="<?php echo Text::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
				</th>
				<th class="left">
					Хэш фильтра
				</th>
				<th class="left">
					Title
				</th>
				<th class="left">
					Description
				</th>
				<th class="left">
					Алиас
				</th>
			</tr>
			</thead>
			<?php $count = count($rows); ?>
			<?php foreach ($rows as $row): ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center">
						<?php echo HTMLHelper::_('grid.id', $i, $row->filter_hash); ?>
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
						<?php echo $row->description; ?>
					</td>
					<td class="left">
						<?php echo $row->alias; ?>
					</td>
				</tr>
			<?php endforeach; ?>
	</form>
</div>
