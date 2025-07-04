<?php
$this->extend('layout');
$this->section('main');
echo $this->include('breadcrumbs');
echo nav()->alertView([
    'success' => ['Success', 'Je voorkeuren zijn opgeslagen.', 'alert-success'],
    'failed'  => ['Mislukt', 'Niet alle voorkeuren konden opgeslagen worden. Controleer alle velden.', 'alert-warning'],
]);
?>
<form action="<?= site_url('instellingen/mijn-voorkeuren'); ?>" method="post" id="userPrefsForm">
    <div class="row">
        <div class="col mb-3">
            <div class="card">
                <div class="card-header">
                    <h3>Voorkeuren</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="firstNameInput" name="first_name"
                                    inputmode="text" value="<?= old('first_name') ?? auth()->user()->first_name; ?>"
                                    required>
                                <label for="firstNameINput">Voornaam</label>
                            </div>
                        </div><!--/col-->
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="lastNaeInput" name="last_name"
                                    inputmode="text" value="<?= old('last_name') ?? auth()->user()->last_name; ?>"
                                    required>
                                <label for="lastNameInput">Achternaam</label>
                            </div>
                        </div><!--/col-->
                    </div><!--/row-->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="emailInput" name="email" inputmode="text"
                                    value="<?= auth()->user()->email; ?>" disabled>
                                <label for="emailInput">E-mailadres</label>
                            </div>
                        </div>
                    </div><!--/row-->

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <select id="timeZoneSelect" name="userTimeZone" class="form-select">
                                    <option value="home" <?= (old('userTimeZone') ?? $preferences['userTimeZone']) === 'home'
                                        ? 'selected' : ''; ?>>Thuisland
                                        (
                                        <?= katini()->getFormattedTime('home', 'time'); ?>)
                                    </option>
                                    <option value="mission" <?= (old('userTimeZone') ?? $preferences['userTimeZone']) === 'mission'
                                        ? 'selected' : ''; ?>
                                        >Werkland (
                                        <?= katini()->getFormattedTime('mission', 'time'); ?>)
                                    </option>
                                </select>
                                <label for="timeZoneSelect">Tijdzone</label>
                            </div>
                        </div><!--/col-->
                    </div><!--/row-->

                    <div class="row justify-content-end">
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary w-100">
                                <?= bi('save'); ?> Voorkeuren opslaan
                            </button>
                        </div>
                    </div><!--/row-->
                </div><!--/card-body-->
            </div><!--/card-->
        </div><!--/col-->
    </div><!--/row-->
</form>

<div class="row">
    <div class="col mb-3">
        <div class="card">
            <div class="card-header">
                <h3>Rechten</h3>
            </div>


            <div class="card-body">
                <div class="row">
                    <div class="col mb-3">
                        <h4>Rollen</h4>
                        <ul class="list-group mb-3">
                            <?php foreach ($userGroups as $group): ?>
                            <li class="list-group-item"><?= $group['title']; ?> (<?= $group['description']; ?>)</li>
                            <?php endforeach; ?>
                        </ul>
                    </div><!--/col-->

                    <div class="col mb-3">
                        <h4>Rechten</h4>
                        <table class="table table-striped">
                            <tbody>
                                <?php foreach ($permissions as $permission => $description): ?>
                                <tr>
                                    <td><?= $description; ?></td>
                                    <td class="text-center <?= $user->can($permission) ? 'bg-success-subtle' : 'bg-danger-subtle'; ?>"><?= $user->can($permission) ? bi('check') : bi('x'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div><!--/col-->
                </div><!--/row-->
            </div>
        </div><!--/card-->
    </div><!--/col-->
</div>
<?php $this->endSection(); ?>
