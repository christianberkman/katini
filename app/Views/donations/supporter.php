<?php
$this->extend('layout');
$this->section('main');
echo $this->include('breadcrumbs');
?>
<div class="row">
    <div class="card pt-4">
        <div class="card-body">
            <ul class="list-group list-group-flush">
            <?php foreach ($donations as $donation): ?>
            <li class="list-group-item">
                <a href="<?= $donation->url; ?>" class="stretched-link">
                    <strong><?= $donation->formattedAmount; ?></strong>
                    <?= bi('dot'); ?>
                    <?= $donation->method; ?>
                    <?= bi('dot'); ?>
                    <?= formatTime($donation->donation_date ?? $donation->created_at, 'longDate'); ?>
                </a>
            </li>
            <?php endforeach; ?>
            </ul>
        </div>
    </div><!--/card-->
</div><!--/row-->
<?php $this->endSection(); ?>
