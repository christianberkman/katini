<?php
$this->extend('layout');
$this->section('main');
echo $this->include('breadcrumbs');
echo nav()->alertView([
    'delete-failed' => ['Fout', 'Kon kring niet verwijderen', 'alert-danger'],
    'not-empty'     => ['Fout', 'Een kring kan alleen verwijderd worden als deze leeg is', 'alert-warning'],
]);
?>
<div class="row">
    <div class="col col-lg-6 m-auto">
        <div class="card">
            <div class="card-body pt-3">
                <form action="<?= current_url(); ?>" method="post" id="circleForm">
                    <div class="mb-3">
                        <label for="circleName" class="form-label">Naam</label>
                        <input type="text" name="circle_name" id="circleName" value="<?= old('circle_name') ?? $circle->circle_name ?? null; ?>" maxlength="32" class="form-control" />
                    </div><!--/row-->

                    <div class="mb-4">
                        <label for="notes" class="form-label">
                            Notitie
                        </label>
                        <textarea name="note" id="note" rows="5" class="form-control"><?= strip_tags(old('note') ?? $circle->note ?? null); ?></textarea>
                    </div>

                    <div class="row justify-content-end">
                        <?php if (($circle->id ?? null) !== null && $circle->supporters_count === 0): ?>
                            <div class="col-auto mb-3">
                                <a href="<?= site_url("/circles/{$circle->id}/delete"); ?>" class="btn btn-danger confirm-action">
                                    <?= bi('delete'); ?> Kring verwijderen
                                </a>
                            </div><!--/col-->
                        <?php endif; ?>

                        <div class="col-auto mb-3">
                            <button type="submit" class="btn btn-success">
                                <?= bi('check'); ?> Opslaan
                            </button>
                        </div>
                    </div>
            </div><!--/card-body-->
        </div>
    </div><!--/col-->
</div><!--/row-->
<?php
$this->endSection();
$this->section('script');
?>
<script>
    $(function(){
        /**
         * Validation
         */
         const validator = new window.JustValidate('#circleForm', window.justValidateConfig);

        validator.addField('#circleName', [
            {
                rule: 'required',
                errorMessage: 'Voer een naam voor de kring in'
            },
        ])
    })
</script>
<?php $this->endSection(); ?>
