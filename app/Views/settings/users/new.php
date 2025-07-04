<?php
$this->extend('layout');
$this->section('main');
echo $this->include('breadcrumbs');
?>
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
                            <div class="form-floating">
                                <input type="email" class="form-control" name="email" id="email"
                                    value="<?= old('email'); ?>" required />
                                <label class="form-label" for="email">E-mailadres</label>
                            </div>
                        </div>
                    </div><!--/row-->

                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="first_name" id="firstName"
                                    vallue="<?= old('first_name'); ?>" required />
                                <label class="form-label" for="firstName">Voornaam</label>
                            </div>
                        </div><!--/col-->

                        <div class="col-lg-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="last_name" id="lastName"
                                    vallue="<?= old('last_name'); ?>" required />
                                <label class="form-label" for="lastName">Achternaam</label>
                            </div>
                        </div><!--/col-->
                    </div><!--/row-->

                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <div class="form-floating">
                                <input type="password" class="form-control" name="password" id="password" vallue=""
                                    minlength="8" required />
                                <label class="form-label" for="password">Wachtwoord</label>
                            </div>
                        </div><!--/col-->

                        <div class="col-lg-6 mb-3">
                            <div class="form-floating">
                                <input type="password" class="form-control" name="password_confirm" id="passwordConfirm"
                                    vallue="" minlength="8" required />
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
            <button type="submit" class="btn btn-success w-100">
                <?= bi('check'); ?> Nieuwe gebruiker aanmaken
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
            return false
        })
    })
</script>
<?php $this->endSection(); ?>