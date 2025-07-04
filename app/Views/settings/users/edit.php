ed<?php
$this->extend('layout');
$this->section('main');
echo $this->include('breadcrumbs');
echo nav()->alertView();
?>
<?php if ($user->isBanned()): ?>
<div class="alert alert-warning">
    Deze gebruiker is non-actief
</div>
<?php endif; ?>

<div class="row justify-content-end">
    <div class="col-auto mb-3">
        <?php if ($user->isBanned()): ?>
        <a href="<?= site_url("/settings/users/unban/{$user->id}"); ?>" class="btn btn-success">
            <?= bi('ban'); ?> Actief maken
        </a>
        <?php else: ?>
        <a href="<?= site_url("/settings/users/ban/{$user->id}"); ?>" class="btn btn-warning">
            <?= bi('ban'); ?> Non-actief maken
        </a>
        <?php endif; ?>
    </div><!--/col-->
    <div class="col-auto mb-3">
        <a href="<?= site_url("/settings/users/delete/{$user->id}"); ?>" class="btn btn-danger confirm-action" data-confirm-msg="Weet u zeker dat u gebruiker [<?= $user->email; ?>] wilt verwijderen?">
            <?= bi('delete'); ?> Gebruiker verwijderen
        </a>
    </div><!--/col-->
</div><!--/row-->


<form action="<?= current_url(); ?>" method="post" id="newUserForm">
    <div class="row">
        <div class="col mb-3">
            <div class="card">
                <div class="card-header">
                    <h3>Details</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <div>
                                <input type="email" class="form-control" name="email" id="email" value="<?= old('email') ?? $user->email; ?>" required />
                                <label class="form-label" for="email">E-mailadres</label>
                            </div>
                        </div>
                    </div><!--/row-->

                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="first_name" id="firstName" value="<?= old('first_name') ?? $user->first_name; ?>" required />
                                <label class="form-label" for="firstName">Voornaam</label>
                            </div>
                        </div><!--/col-->

                        <div class="col-lg-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="last_name" id="lastName" value="<?= old('last_name') ?? $user->last_name; ?>" required />
                                <label class="form-label" for="lastName">Achternaam</label>
                            </div>
                        </div><!--/col-->
                    </div><!--/row-->

                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <div class="form-floating">
                                <input type="password" class="form-control" name="password" id="password" value="" minlength="8" />
                                <label class="form-label" for="password">Wachtwoord</label>
                            </div>
                        </div><!--/col-->

                        <div class="col-lg-6 mb-3">
                            <div class="form-floating">
                                <input type="password" class="form-control" name="password_confirm" id="passwordConfirm" value="" minlength="8" />
                                <label class="form-label" for="passwordConfirm">Wachtwoord bevestigen</label>
                            </div>
                        </div><!--/col-->
                    </div><!--/row-->
                </div><!--/card-body-->
            </div><!--/card-->

        </div><!--/col-->
    </div><!--/row-->

    <div class="row">
        <div class="col">
            <button type="submit" class="btn btn-primary w-100">
                <?= bi('check'); ?> Wijzigingen opslaan
            </button>
        </div><!--/col-->
    </div><!--/row-->
</form>
<?php
$this->endSection();
$this->section('script');
?>
<script>
    $(function () {
        $('#newUserForm').submit(function (e) {

            let password = $('#password').val()
            let passwordConfirm = $('#passwordConfirm').val()

            if (password == passwordConfirm) return true


            e.preventDefault()
            alert('Wachtwoorden komen niet overeen');
            return false
        })
    })
</script>
<?php $this->endSection(); ?>