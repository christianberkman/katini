<?php
$this->extend('layout');
$this->section('main');
echo $this->include('breadcrumbs');
?>
<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
    <div class="col mb-3">
        <div class="card">
            <div class="card-header">
                <div class="fs-4">Aantal supporters</div>
            </div>
            <div class="card-body text-center">
                <span class="fs-2">
                    <?= $supporters_count ?? '0'; ?>
                </span>
                <a href="<?= site_url('supporters/all'); ?>" class="stretched-link"></a>
            </div>
        </div><!--/card-->
    </div><!--/col-->

    <div class="col mb-3">
        <div class="card text-bg-success h-100">
            <div class="card-body d-flex align-items-center justify-content-center">
                <a href="<?= site_url('supporters/new'); ?>"
                    class="fs-4 text-white link-underline link-underline-opacity-0 stretched-link">
                    <?= bi('add'); ?> Supporter toevoegen
                </a>
            </div>
        </div><!--/card-->
    </div><!--/col-->

    <div class="col mb-3">
        <div class="card text-bg-primary h-100">
            <div class="card-body d-flex align-items-center justify-content-center">
                <a href="<?= site_url('supporters/find'); ?>"
                    class="fs-4 text-white link-underline link-underline-opacity-0 stretched-link">
                    <?= bi('find'); ?> Supporter vinden
                </a>
            </div>
        </div><!--/card-->
    </div><!--/col-->
</div><!--/row-->

<div class="row">
    <div class="col mb-3">
        <h2>Meest recente supporters</h2>
        <?= $this->include('supporters/table'); ?>
        <a href="<?= site_url('supporters/all'); ?>" class="btn btn-primary w-100">
            Alle supporters bekijken
        </a>
    </div><!--/col-->
</div><!--/col-->

<?php $this->endSection(); ?>