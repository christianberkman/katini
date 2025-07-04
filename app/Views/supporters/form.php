<?php
$this->extend('layout');
$this->section('main');
echo $this->include('breadcrumbs');
echo nav()->alertView([
    'failed' => ['Fout', 'De wijzigingen konden niet opgeslagen worden. Controleer alle velden.', 'alert-danger'],
]);
?>
<form method="post" action="<?= current_url(); ?>" id="supporterForm" />
    <div class="row">
        <div class="col mb-3">
            <div class="card">
                <div class="card-body pt-3">
                    <div class="row">

                        <div class="col-lg-4 col-md-12 mb-3">
                            <label class="form-label">Voornaam</label>
                            <input type="text" name="first_name" id="firstName" class="form-control" maxlength="45" value="<?= old('first_name') ?? $supporter->first_name; ?>" />
                        </div>

                        <div class="col-lg-2 col-md-4 mb-3">
                            <label class="form-label">Tussenvoegsel</label>
                            <input type="text" name="infix" id="infix" class="form-control" maxlength="16" value="<?= old('infix') ?? $supporter->infix; ?>" />
                        </div>

                        <div class="col-lg-4 col-md-8 mb-3">
                            <label class="form-label">Achternaam</label>
                            <input type="text" name="last_name" id="lastName" class="form-control" maxlength="45" value="<?= old('last_name') ?? $supporter->last_name; ?>" />
                        </div>

                        <div class="col-lg-2 mb-3">
                            <label class="form-label">Aanhef</label>
                            <?= view('snippets/optionlist_select', ['listName' => 'titles', 'name' => 'title', 'selected' => (old('title') ?? $supporter->title)]); ?>
                        </div>
                    </div><!--/row-names-->

                    <?php if (! empty($supporter->org_name)): ?>
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label">Bedrijf of Organisatie</label>
                            <span><?= ss($supporter->org_name); ?></span>
                        </div><!--/col-->
                    </div><!--/row-org-name-->
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-lg-4 mb-3">
                            <label class="form-label">Bedrijf / Organisatie</label>
                            <input name="org_name" id="orgName" type="text" class="form-control" maxlength="60" value="<?= old('org_name') ?? $supporter->org_name; ?>" />
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label class="form-label">Telefoonnummer</label>
                            <input name="phone" class="form-control" maxlength="15" value="<?= old('phone') ?? $supporter->phone; ?>" />
                        </div>

                        <div class="col-lg-5 mb-3">
                            <label class="form-label">E-mailadres</label>
                            <input name="email" type="email" id="email" class="form-control" maxlength="90" value="<?= old('email') ?? $supporter->email; ?>" />
                        </div>

                    </div><!--/row-email-phone-org-->

                    <div class="row">
                        <div class="col-lg-4 mb-3">
                            <label class="form-label">Straat</label>
                            <input name="address_street" class="form-control" maxlength="45" value="<?= old('address_street') ?? $supporter->address_street; ?>" />
                        </div>

                        <div class="col-lg-2 mb-3">
                            <label class="form-label">Huisnummer</label>
                            <input name="address_number" class="form-control" maxlength="5" pattern="[0-9]{1,4}" value="<?= old('address_number') ?? $supporter->address_number ?? ''; ?>" />
                        </div>

                        <div class="col-lg-2 mb-3">
                            <label class="form-label">Toevoeging</label>
                            <input name="address_addition" class="form-control" maxlength="10" value="<?= old('address_addition') ?? $supporter->address_addition; ?>" />
                        </div>
                    </div><!--/row-address1-->

                    <div class="row">
                        <div class="col-lg-2 mb-3">
                            <label class="form-label">Postcode</label>
                            <input name="address_postcode" class="form-control" maxlength="7" pattern="[0-9]{4} ?[A-Z]{2}" value="<?= old('address_postcode') ?? $supporter->address_postcode; ?>" />
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Plaats</label>
                            <input name="address_city" class="form-control" maxlength="64" value="<?= old('address_city') ?? $supporter->city; ?>" />
                        </div>
                    </div><!--/row-address2-->

                    <div class="row">
                        <div class="col-lg-5 mb-3">
                            <label class="form-label">Supporter sinds</label>
                            <input type="date" name="created_at" value="<?= old('created_at') ?? $supporter->created_at->toDateString(); ?>" class="form-control" max="<?= ymdToday(); ?>" />
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label class="form-label">Geboortedatum</label>
                            <input name="date_birth" type="date" class="form-control" value="<?= (old('date_birth') ?? $supporter->date_birth); ?>" max="<?= ymdToday(); ?>" />
                        </div>

                        <div class="col-lg-4 mb-3">
                            <label class="form-label">IBAN</label>
                            <input name="iban" class="form-control" value="<?= (old('iban') ?? $supporter->iban); ?>" />
                        </div>
                    </div><!--/row-dob-iban-->

                    <div class="row">
                        <div class="col-lg-8 mb-3">
                            <label class="form-label">Notitie</label>
                            <textarea name="note" rows="5" maxlength="1000" class="form-control w-100"><?= old('note') ?? $supporter->note; ?></textarea>
                        </div>
                    </div><!--/row--note-->

                    <div class="row">
                        <div class="col-auto ms-auto">
                            <button type="submit" class="btn btn-success">
                                <?= bi('save'); ?> Opslaan
                            </button>
                        </div><!--/col-->
                    </div><!--/row-->
                </div><!--/card-body-->
            </div><!--/card-->
        </div>
    </div><!--/row-->
</form>
<?php
$this->endSection();
$this->section('script');
?>
<script>
    $(function(){
        const validator = new window.JustValidate('#supporterForm', window.justValidateConfig);

        const firstNameInput = '#firstName'
        const infixInput = '#infix'
        const orgInput = '#orgName'
        const lastNameInput = '#lastName'


        /**
         * Last Name
         *
         */
        function requireLastName(){
            validator.addField('#lastName', [
                {
                    rule: 'required',
                    errorMessage: 'Achternaam vereist',
                },
            ])
        }
        requireLastName()

        function checkLastNameRequirement()
        {
            // Remove last name requirement if first name is given (and empty infix)
            if($(firstNameInput).val() != '' && $(infixInput).val() == ''){
                validator.removeField(lastNameInput)
                return
            }

            // Add last name requirement if infix is non-empty
            if($(infixInput).val() != ''){
                console.log('infix')
                requireLastName()
                return
            }

            // Remove last name requiement if infix is non-empty and first name or organization is non-empty
            if($(infixInput).val() == '' && ($(firstNameInput).val() != '' || $(orgInput) != '')){
                validator.removeField('#lastName')
                return
            }
        }

        $(firstNameInput).change(function(){ checkLastNameRequirement() })
        $(infixInput).change(function(){ checkLastNameRequirement() })
        $(orgInput).change(function(){ checkLastNameRequirement() })
    });
</script>
<?php $this->endSection(); ?>