<?php
$this->extend('layout');
$this->section('main');
echo $this->include('breadcrumbs');
echo nav()->alertView([
    'success' => ['Success', 'Alle wijzigingen zijn opgeslagen', 'alert-success'],
    'failed'  => ['Fout', 'Kon de wijzigingen niet opslaan', 'alert-danger'],
]);

?>
<form method="post" action="<?= current_url(); ?>" id="donationForm">
    <div class="row">
        <div class="col mb-3 ">
            <div class="card">
                <div class="card-body pt-3">
                    <div class="row">
                        <div class="col-md-6 mb-3 display">
                            <label>Supporter</label>
                            <input type="hidden" name="id" id="supporterId" value="<?= $supporter->id ?? null; ?>" />
                            <div class="row d-flex">
                                <div class="col-auto flex-grow-1" id="supporterDisplayName">
                                    <?= $supporter->display_name ?? 'Anoniem'; ?>
                                </div>
                                <div class="col-auto">
                                    <button type="button" id="btnFindSupporter" class="btn btn-outline-primary btn-sm "
                                        title="Supporter vinden">
                                        <?= bi('search'); ?>
                                    </button>
                                    <button type="button" id="btnRemoveSupporter" class="btn btn-outline-danger btn-sm"
                                        title="Supporter verwijderen van deze donatie"
                                        data-confirm-msg="Het verwijderen van de supporter maakt deze donatie anoniem. Weet u het zeker?">
                                        <?= bi('delete'); ?>
                                    </button>
                                </div>
                            </div><!--/row-flex-->
                        </div><!--/col-supporter-->

                        <div class="col-md-4 mb-3">
                            <label for="created_at" class="form-label">Datum</label>
                            <input name="created_at" type="date" class="form-control" value="<?= old('created_at') ?? $donation->created_at->toDateString(); ?>" max="<?= ymdToday(); ?>" />
                        </div>
                    </div><!--/row-->

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="amount">Bedrag (€)</label>
                            <input type="number" name="amount" id="amount" min="0" step="0.01"
                                value="<?= old('amount') ?? $donation->amount; ?>" class="form-control" />
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="method" class="form-label">Betaalmethode</label>
                            <?= view('snippets/optionlist_select', ['listName' => 'paymentMethods', 'name' => 'method', 'selected' => (old('method') ?? $donation->method)]); ?>
                        </div>
                    </div><!--/row-->

                    <div class="row align-items-end">
                        <div class="col-md-3 mb-3">
                            <div class="form-check">
                                <label for="isRecurring" class="form-label">Periodieke donatie</label>
                                <input type="checkbox" class="form-check-input" id="isRecurring" name="is_recurring" value="1" <?= (old('is_recurring') ?? $donation->is_recurring) ? 'checked' : ''; ?> />
                            </div><!--/form-check-->
                        </div>

                        <div class="col-md-3 mb-3 d-none" id="intervalCol">
                            <label class="form-label" for="interval">Interval (maanden)</label>
                            <input type="number" id="interval" name="interval" class="form-control" max="12" value="<?= old('interval') ?? $donation->interval ?? 1; ?>" min="1" />
                        </div>
                    </div><!--/row-recurring-->

                    <div class="row">
                        <div class="col mb-3">
                            <label for="note" class="form-label">Notitie</label>
                            <textarea name="note" id="note" rows="5"
                                class="form-control"><?= strip_tags(old('note') ?? $donation->note); ?></textarea>
                        </div>
                    </div><!--/row-->

                    <div class="row justify-content-end">
                        <div class="col-auto mb-3">
                            <button type="submit" class="btn btn-success">
                                <?= bi('check'); ?> Opslaan
                            </button>
                        </div>
                    </div>
                </div><!--/row-->
            </div><!--/card-body-->
        </div><!--/card-->
    </div><!--/col-->
    </div><!--/row-->
</form>
<?php
d($donation);
$this->endSection();
$this->section('script');
?>
<script type="module">
    import {
        findSupporterModal
    } from '/assets/Katini/modals.js';

    $(function () {
        /**
         * Find supporter
         */
        let anonymousLabel = '<em>Anoniem</em>'
        let supporterIdInput = $('#supporterId')
        let supporterDisplayName = $('#supporterDisplayName')

        const findSupporter = findSupporterModal({
            triggerBtn: '#btnFindSupporter'
        })

        findSupporter.onSelect(function (supporter) {
            supporterIdInput.val(supporter.id)
            supporterDisplayName.html(supporter.name)
        })

        $('#btnRemoveSupporter').click(function (e) {
            const confirmAction = window.confirm($(this).attr('data-confirm-msg'))
            if (confirmAction == false) {
                e.preventDefault();
                return false
            }

            removeSupporter()
        })

        function removeSupporter() {
            supporterIdInput.val('')
            supporterDisplayName.html(anonymousLabel)
        }

        /**
         * Recurring
         */
        $('#isRecurring').change(function(){
            isRecurring = $(this)

            if(isRecurring.prop('checked'))
            {
              showIntervalField()
            } else{
                $('#interval').addClass('d-none');
                validator.removeField('#intervalCol')
            }
        })


        /**
         * Validation
         */
        const validator = new window.JustValidate('#donationForm', window.justValidateConfig);

        validator.addField('#amount', [
            {
                rule: 'required',
                errorMessage: 'Voer een bedrag in'
            },
        ])

        if($('#isRecurring').prop('checked')) showIntervalField()

        function showIntervalField()
        {
            $('#intervalCol').removeClass('d-none');
            validator.addField('#interval', [
                    {
                        rule: 'required',
                        errorMessage: 'Interval is verplicht',
                    },
                    {
                        rule : 'integer',
                        errorMessage: 'Interval moet minstens 1 en maximaal 12 zijn'
                    },
                    {
                        rule: 'minNumber',
                        value: 1,
                        errorMessage: 'Minimum interval is 1 maand',
                    },
                    {
                        rule: 'maxNumber',
                        value: 12,
                        errorMessage: 'Maximum interval is 12 maanden',
                    }
                ])
        }

    });
</script>
?>
<?php $this->endSection(); ?>