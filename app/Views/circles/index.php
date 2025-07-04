<?php
$this->extend('layout');
$this->section('main');
echo $this->include('breadcrumbs');
echo nav()->alertView([
    'success' => ['Success', 'Alle wijzigingen zijn opgeslagen', 'alert-success'],
    'failed'  => ['Fout', 'Kon de wijzigingen niet opslaan', 'alert-danger'],
]);

?>
<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
    <div class="col mb-3">
        <div class="card h-100 text-bg-success h-100">
            <div class="card-body d-flex align-items-center justify-content-center">
                <a href="<?= site_url('circles/new'); ?>"
                    class="fs-4 text-white link-underline link-underline-opacity-0 stretched-link">
                    <?= bi('add'); ?> Nieuwe kring
                </a>
            </div>
        </div><!--/card-->
    </div><!--/col-->
    <?php foreach ($circles_with as $circle): ?>
    <div class="col mb-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <span class="fs-3">
                    <?= $circle->circle_name; ?>
                </span>
                <a href="<?= site_url(" circles/{$circle->id}"); ?>" class="stretched-link"></a>
            </div>
            <div class="card-footer text-center">
                <?= $circle->supporters_count ?? '0'; ?> Supporters
            </div>
        </div>
    </div><!--/col-->
    <?php endforeach; ?>
</div><!--/row-->

<h4>Lege kringen</h4>
<div class="row row-cols-4 mb-3">
    <?php foreach ($circles_without as $circle): ?>
    <div class="col mb-3">
        <div class="card">
            <div class="card-body text-center">
                <span class="fs-5">
                    <?= $circle->circle_name; ?>
                </span>
                <a href="<?= site_url(" circles/{$circle->id}/edit"); ?>" class="stretched-link"></a>
            </div>
        </div>
    </div><!--/col-->
    <?php endforeach; ?>
</div><!--/row-->

<?php $this->endSection(); ?>