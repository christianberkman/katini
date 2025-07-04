<?php
$this->extend('layout');
$this->section('main');
echo $this->include('breadcrumbs');
echo nav()->alertView([
    'success' => ['Success', 'De lijst is opgeslagen', 'alert-success'],
]);
?>


<div class="row row-cols-3 mb-3" id="lists">
    <?php foreach ($lists as $listName => $list): ?>
    <div class="col">
        <div class="card">
            <div class="card-header"><?= $list['title']; ?></div>
            <ul class="list-group list-group-flush">
                <?php foreach ($list['items'] as $item): ?>
                <li class="list-group-item"><?= $item; ?></li>
                <?php endforeach; ?>
              </ul>
              <div class="card-footer">
                <a href="<?= site_url("settings/optionlists/edit/{$listName}"); ?>" class="btn btn-primary btn-sm w-100">
                        Lijst bewerken
                </a>
            </div>
        </div><!--/card-->
    </div><!--/col-->

    <?php endforeach; ?>

</div>


<?php $this->endSection(); ?>