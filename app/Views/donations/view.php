<?php
$this->extend('layout');
$this->section('main');
echo $this->include('breadcrumbs');
echo nav()->alertView([
    'success'       => ['Success', 'Alle wijzigingen zijn opgeslagen', 'alert-success'],
    'failed'        => ['Fout', 'Kon de wijzigingen niet opslaan', 'alert-danger'],
    'no-change'     => ['Geen wijzigingen', 'Er waren geen wijzigingen om op te slaan', 'alert-warning'],
    'delete-failed' => ['Fout', 'De donatie kon niet verwijderd worden', 'alert-danger'],
]);

?>
<div class="row">
    <div class="col mb-3 ">
        <div class="card">
            <div class="card-body pt-3">
                <div class="row">
                    <div class="col-md-6 mb-3 display">
                        <label>Supporter</label>
                        <?php if ($donation->supporter !== null): ?>
                        <span><a href="<?= $donation->supporter->url; ?>"><?= $donation->supporter->display_name; ?></a></span>
                        <?php else: ?>
                        <span>Anoniem</span>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-4 mb-3 display">
                        <label>Datum</label>
                        <span>
                            <?php if ($donation->created_at !== null): ?>
                            <?= formatTime($donation->created_at, 'longDate'); ?><br />
                            <small><em><?= $donation->created_at->humanize(); ?></em></small>
                            <?php else: ?>
                            Onbekend
                            <?php endif; ?>
                        </span>
                    </div>
                </div><!--/row-->

                <div class="row">
                    <div class="col-md-6 mb-3 display">
                        <label>Bedrag</label>
                        <span><?= $donation->formattedAmount; ?></span>
                    </div>

                    <div class="col-md-4 mb-3 display">
                        <label>Betaalmethode</label>
                        <span><?= $donation->method; ?></span>
                    </div>
                </div><!--/row-->

                <div class="row">
                    <?php if ($donation->is_recurring): ?>
                    <div class="col-md-3 mb-3 display">
                        <label>Periodieke donatie</label>
                        <span>Ja, elke <?= $donation->interval; ?> maanden</span>
                    </div>
                    <?php if ($donation->has_recurred === 0): ?>
                    <div class="col-md-3 mb-3 display">
                        <label>Volgende donatie</label>
                        <span><?= $donation->next_date->toDateString(); ?></span><br />
                        <em><?= $donation->next_date->humanize(); ?>
                    </div>
                    <?php else: ?>
                    <div class="col-md-3 mb-3 display">
                        <label>Opvolgende donatie verwerkt</label>
                        <span>Ja</span>
                    </div>
                    <?php endif; ?>
                    <?php else: ?>
                    <div class="col-md-3 mb-3 display">
                        <label>Periodieke donatie</label>
                        <span>Nee</span>
                    </div>
                    <?php endif; ?>
                </div><!--/row-recurring-->

                <div class="row">
                    <div class="col mb-3 display">
                        <label>Notitie</label>
                        <p class="ms-2 p-2 text-bg-light"><?= nl2br($donation->note); ?></p>
                    </div>
                </div><!--/row-->

                <div class="row justify-content-end">
                    <div class="col-auto mb-3">
                        <a href="<?= url_to('donations-delete', $donation->id); ?>" class="btn btn-danger confirm-action" data-confirm-msg="Weet u zeker dat u deze donatie wilt verwijderen?">
                            <?= bi('delete'); ?> Verwijderen
                        </a>
                    </div>

                    <div class="col-auto mb-3">
                        <a href="<?= url_to('donations-edit', $donation->id); ?>" class="btn btn-primary">
                            <?= bi('edit'); ?> Bewerken
                        </a>
                    </div>
                </div>
            </div><!--/card-body-->
        </div><!--/card-->
    </div><!--/col-->
</div><!--/row-->
<?php $this->endSection(); ?>