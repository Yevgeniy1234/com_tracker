<?php
/**
 * @version			2.5.0
 * @package			Joomla
 * @subpackage	com_tracker
 * @copyright		Copyright (C) 2007 - 2012 Hugo Carvalho (www.visigod.com). All rights reserved.
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');

$user		= $this->user;
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder	= $listOrder == 'a.ordering';
?>

<form action="<?php echo JRoute::_('index.php?option=com_tracker&view=banhosts'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('JSEARCH_FILTER'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
	</fieldset>
	<div class="clr"> </div>

	<table class="adminlist">
		<thead>
			<tr>
				<th width="1%" class='left' nowrap><?php echo JHtml::_('grid.sort',	'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?></th>

				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
				</th>

				<th class='left'><?php echo JHtml::_('grid.sort',  'COM_TRACKER_BANHOST_BEGIN', 'a.begin', $listDirn, $listOrder); ?></th>

				<th class='left'><?php echo JHtml::_('grid.sort',  'COM_TRACKER_BANHOST_END', 'a.end', $listDirn, $listOrder); ?></th>

				<th class='left'><?php echo JHtml::_('grid.sort',  'COM_TRACKER_BANHOST_COMMENT', 'a.comment', $listDirn, $listOrder); ?></th>

				<th class='left'><?php echo JHtml::_('grid.sort',  'COM_TRACKER_BANHOST_CREATED_USER_ID', 'a.created_user_id', $listDirn, $listOrder); ?></th>

				<th class='left'><?php echo JHtml::_('grid.sort',  'COM_TRACKER_BANHOST_CREATED_TIME', 'a.created_time', $listDirn, $listOrder); ?></th>

				<th width="10%">
					<?php echo JHtml::_('grid.sort',	'JGRID_HEADING_ORDERING', 'a.ordering', $listDirn, $listOrder); ?>
					<?php if ($saveOrder) :?>
						<?php echo JHtml::_('grid.order',	$this->items, 'filesave.png', 'banhosts.saveorder'); ?>
					<?php endif; ?>
				</th>

			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="8"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) :
			$ordering	= ($listOrder == 'a.ordering');
			$canCreate	= $user->authorise('core.create',		'com_tracker');
			$canEdit	= $user->authorise('core.edit',			'com_tracker');
			$canCheckin	= $user->authorise('core.manage',		'com_tracker');
			$canChange	= $user->authorise('core.edit.state',	'com_tracker');
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td width="1%" nowrap><?php echo $item->id;?></td>

				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>

				<td><?php echo long2ip($item->begin); ?></td>

				<td><?php echo long2ip($item->end); ?></td>

				<td>
				<?php if ($canEdit) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_tracker&task=banhost.edit&id='.(int) $item->id); ?>">
					<?php echo $this->escape($item->comment); ?></a>
				<?php else : ?>
					<?php echo $this->escape($item->comment); ?>
				<?php endif; ?>
				</td>

				<td><?php echo $item->username; ?></td>

				<td><?php echo $item->created_time; ?></td>

				<td class="order">
					<?php if ($canChange) : ?>
						<?php if ($saveOrder) :?>
							<?php if ($listDirn == 'asc') : ?>
								<span><?php echo $this->pagination->orderUpIcon($i, 1 == 1, 'donations.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
								<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, (1 == 1), 'donations.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
							<?php elseif ($listDirn == 'desc') : ?>
								<span><?php echo $this->pagination->orderUpIcon($i, (1 == 1), 'donations.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
								<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, (1 == 1), 'donations.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
							<?php endif; ?>
						<?php endif; ?>
						<?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
						<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text-area-order" />
					<?php else : ?>
						<?php echo $item->ordering; ?>
					<?php endif; ?>
				</td>

			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>