<?php
$this->extend('layout');
$this->section('main');
echo $this->include('breadcrumbs');
?>
<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
    <div class="col mb-3">
        <div class="card h-100">
            <div class="card-header">
                <div class="fs-4">Donaties</div>
            </div>
            <div class="card-body align-item-center d-flex align-items-center justify-content-center">
                <div class="text-center">
                    <span class="fs-2">
                        <?= $donationStats['count']; ?>
                        <?= bi('dot'); ?>
                        <?= formatAmount($donationStats['sum']); ?>
                    </span><br />
                    <em>Afgelopen
                        <?= $donationStats['period']; ?> dagen
                    </em>
                    <a href="<?= site_url('donations'); ?>" class="stretched-link"></a>
                </div>
            </div>
        </div><!--/card-->
    </div><!--/col-->

    <div class="col mb-3">
        <div class="card text-bg-success h-100">
            <div class="card-body d-flex align-items-center justify-content-center">
                <a href="<?= site_url('donations/new'); ?>"
                    class="fs-4 text-white link-underline link-underline-opacity-0 stretched-link">
                    <?= bi('add'); ?> Donatie toevoegen
                </a>
            </div>
        </div><!--/card-->
    </div><!--/col-->
</div><!--/row-->

<div class="row">
    <div class="col">
        <h2>Laatste 10 donaties</h2>
        <?= $this->include('donations/table'); ?>
        <a href="<?= site_url('donations/all'); ?>" class="btn btn-primary w-100">
            Alle donaties bekijken
        </a>
    </div><!--/col-->
</div><!--/row-->

<?php $this->endSection(); ?>