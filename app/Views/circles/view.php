<?php
$this->extend('layout');
$this->section('main');
echo $this->include('breadcrumbs');
echo nav()->alertView([
    'success' => ['Success', 'Alle wijzigingen zijn opgeslagen', 'alert-success'],
    'failed'  => ['Fout', 'Kon de wijzigingen niet opslaan', 'alert-danger'],
]);

?>
<div class="row justify-content-end mb-3">
    <div class="col-auto">
        <a href="<?= site_url("circles/{$circle->id}/edit"); ?>" class="btn btn-primary">
            <?= bi('edit'); ?> Kring bewerken
        </a>
    </div>
</div><!--/row-->

<?= view('supporters/table', $supporters); ?>

<?php $this->endSection(); ?>
