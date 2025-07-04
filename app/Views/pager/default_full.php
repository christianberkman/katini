<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>
<div class="row justify-content-center mb-3">
		<?php if ($pager->hasPrevious()) : ?>
			<div class="col-auto">
				<a href="<?= $pager->getFirst(); ?>" class="btn btn-sm btn-outline-primary">
					<?= lang('Pager.first'); ?>
				</a>
			</div>

			<div class="col-auto">
				<a href="<?= $pager->getPrevious(); ?>" class="btn btn-sm btn-outline-primary">
					<?= bi('chevron-double-left'); ?>
				</a>
			</div>
		<?php endif ?>

		<?php foreach ($pager->links() as $link) : ?>
			<div class="col-auto">
				<a href="<?= $link['uri'] ?>" class="btn btn-sm <?= $link['active'] ? 'btn-primary' : 'btn-outline-primary'; ?>">
					<?= $link['title'] ?>
				</a>
			</div>
		<?php endforeach ?>

		<?php if ($pager->hasNext()) : ?>
			<div class="col-auto">
				<a href="<?= $pager->getNext(); ?>" class="btn btn-sm btn-outline-primary">
					<?= bi('chevron-double-right'); ?>
				</a>
			</div>
			<div class="col-auto">
				<a href="<?= $pager->getLast(); ?>" class="btn btn-sm btn-outline-primary">
					<?= lang('Pager.last'); ?>
				</a>
			</div>
		<?php endif ?>
</div><!--/pager row-->
