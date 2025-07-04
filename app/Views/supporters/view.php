<?php

$this->extend('layout');
$this->section('main');
echo $this->include('breadcrumbs');
echo nav()->alertView([
    'success'   => ['Success', 'Alle wijzigingen zijn opgeslagen', 'alert-success'],
    'created'   => ['Success', 'Nieuwe suppoter aangemaakt', 'alert-success'],
    'no_change' => ['Geen wijzigingen', 'Er waren geen wijzigingen om op te slaan', 'alert-warning'],
]);

?>
<div class="row">
    <div class="col mb-3">
        <p>
            <em>Aangemaakt <?= lcfirst($supporter->created_at->humanize()); ?>, laaste wijziging <?= lcfirst($supporter->updated_at->humanize()); ?> door <?= katini()->getUsernameById($supporter->updated_by); ?></em>.
        </p>

        <div class="card">
            <div class="card-body pt-3">
                <div class="row">

                    <div class="col-sm-5 mb-3 display">
                        <label>Voornaam</label>
                        <span><?= $supporter->first_name; ?></span>
                    </div>

                    <div class="col-sm-5 mb-3 display">
                        <label>Achternaam</label>
                        <span><?= implode(' ', [$supporter->infix, $supporter->last_name]); ?></span>
                    </div>

                    <?php if (! empty($supporter->title)): ?>
                    <div class="col-sm-2 mb-3 display">
                        <label>Aanhef</label>
                        <span><?= $supporter->title; ?></span>
                    </div>
                    <?php endif; ?>
                </div><!--/row-names-->

                <div class="row">
                    <div class="col-sm-5 mb-3 display">
                        <label>Bedrijf of Organisatie</label>
                        <span><?= $supporter->org_name; ?></span>
                    </div><!--/col-->

                    <div class="col-sm-3 mb-3 display">
                        <label>Telefoonnummer</label>
                        <span><a href="tel://<?= $supporter->getPhoneUrl(); ?>"><?= $supporter->phone; ?></a></span>
                    </div>

                    <div class="col-sm-3 mb-3 display">
                        <label>E-mailadres</label>
                        <span><?= mailto($supporter->email); ?></span>
                    </div>
                </div><!--/row-email-phone-->

                <div class="row">
                    <div class="col-auto mb-3 display">
                        <label>Adres</label>
                        <span><?= $supporter->getAddress('<br />'); ?></span>
                    </div>
                </div><!--/row-address-->

                <div class="row">
                    <div class="col-lg-4 mb-3 display">
                        <label>Geboortedatum</label>
                        <span><?= $supporter->getDateOfBirthString(); ?></span>
                    </div>

                    <div class="col-lg-4 mb-3 display">
                        <label>Supporter sinds</label>
                        <span><?= formatTime($supporter->created_at, 'longDate'); ?></span><br />
                        <em><?= $supporter->created_at->humanize(); ?></em>
                    </div>

                    <div class="col-lg-4 mb-3 display">
                        <label>IBAN</label>
                        <span><?= $supporter->iban; ?></span>
                    </div>
                </div><!--/row-dob-iban-->

                <div class="row">
                    <div class="col-lg-8 mb-3 display">
                        <label>Notitie</label>
                        <p class="ms-2 p-2 text-bg-light"><?= nl2br($supporter->note); ?></p>
                    </div>
                </div><!--/row-note-->

                <div class="row justify-content-end">
                    <div class="col-auto">
                            <a href="<?= url_to('supporters-delete', $supporter->id); ?>" class="btn btn-danger confirm-action" data-confirm-msg="Weet u zeker dat u de supporter wilt verwijderen?" data-confirm-detail="<?= $supporter->compileDisplayName(); ?>">
                                <?= bi('delete'); ?> Verwijderen
                            </a>
                        </div>

                    <div class="col-auto">
                        <a href="<?= url_to('supporters-edit', $supporter->id); ?>" class="btn btn-primary"><?= bi('edit'); ?> Bewerk</a>
                    </div><!--/col-->
                </div><!--/row-->
            </div><!--/card-body-->
        </div><!--/card-->
    </div>
</div><!--/row-->

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Recente Donaties</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <?php
                        $donations = $supporter->getDonations(10);
if (count($donations) === 0):
    ?>
                        <li class="list-group-item">
                            Nog geen donaties van deze supporter
                        </li>
                    <?php
else:
    foreach ($donations as $donation): ?>
                    ?>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-5"><?= $donation->created_at->humanize(); ?></div>
                            <div class="col-sm-7">
                                <a href="<?= $donation->url; ?>" class="stretched-link">€<?= number_format($donation->amount, 2); ?></a>
                                <?= bi('dot'); ?>
                                <span class="text-muted"><?= $donation->method; ?></span>
                            </div>
                        </div><!--/row-->
                    </li>
                    <?php
    endforeach;
endif;
?>
                    <a href="<?= url_to('donations-supporter', $supporter->id); ?>" class="btn btn-primary w-100">
                        <?= bi('donation'); ?> Alle donaties
                    </a>
                  </ul>
            </div><!--/card-body-->

        </div><!--/card-->
    </div><!--/col-->

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Kringen</h5>
            </div>
            <div class="card-body">
                <?php if (count($supporter->circles) > 0): ?>
                <?php foreach ($supporter->circles as $circle): ?>
                <a href="<?= site_url("circles/{$circle->id}"); ?>" class="badge bg-secondary me-1 mb-1 link-underline link-underline-opacity-0 fs-6">
                    <?= $circle->circle_name; ?>
                </a>
                <?php endforeach; ?>
                <?php else: ?>
                Supporter bevind zich niet in een kring
                <?php endif; ?>
                <a href="<?= site_url("supporters/{$supporter->id}/circles"); ?>" class="btn btn-primary w-100 mt-3">
                    <?= bi('circle'); ?> Kringen beheren
                </a>
            </div><!--/card-body-->
        </div><!--/card-->
    </div><!--/col-->
</div><!--/row--->


<?php $this->endSection(); ?>