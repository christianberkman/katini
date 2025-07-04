<?php
$this->extend('layout');
$this->section('main');
echo $this->include('breadcrumbs');
?>


<div class="row row-cols-3 mb-3" id="lists">
    <div class="col mb-3">
        <div class="card h-100">
            <div class="card-body mt-3">
                <h3><?= bi('gear'); ?> Algemeen</h3>
                Algemene instellingen
                <a href="<?= site_url('settings/general'); ?>" class="stretched-link"></a>
            </div>
        </div><!--/card-->
    </div><!--/col-->


    <div class="col mb-3">
        <div class="card h-100">
            <div class="card-body mt-3">
                <h3><?= bi('list'); ?> Keuzelijsten</h3>
                Keuzelijsten bevatten keuzes voor velden zoals aanhef en betaalmethode.
                <a href="<?= site_url('settings/optionlists'); ?>" class="stretched-link"></a>
            </div>
        </div><!--/card-->
    </div><!--/col-->

    <div class="col mb-3">
        <div class="card h-100">
            <div class="card-body mt-3">
                <h3><?= bi('list'); ?> Mijn voorkeuren</h3>
                Voorkeuren voor de ingelogde gebruiker (<?= auth()->user()->email; ?>)
                <a href="<?= site_url('settings/user'); ?>" class="stretched-link"></a>
            </div>
        </div><!--/card-->
    </div><!--/col-->

    <div class="col mb-3">
        <div class="card h-100">
            <div class="card-body mt-3">
                <h3><?= bi('user'); ?> Gebruikersbeheer</h3>
                Beheer gebruikers, groepen en rechten
                <a href="<?= site_url('settings/users'); ?>" class="stretched-link"></a>
            </div>
        </div><!--/card-->
    </div><!--/col-->
</div><!--/row-->
<?php $this->endSection(); ?>