<?php
$this->extend('layout');
$this->section('main');
echo $this->include('breadcrumbs');
echo nav()->alertView();
?>
<form action="<?= current_url(); ?>" method="post">
    <div class="row">
        <div class="col mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="appName" class="form-label">Appnaam</label>
                            <input type="text" name="appName" id="appName" value="<?= $settings['appName']; ?>" class="form-control" required />
                        </div>
                    </div><!--/row-->

                    <div class="row">
                        <div class="col mb-3">
                            <label for="teamName" class="form-label">Teamnaam</label>
                            <input type="text" name="teamName" id="teamName" value="<?= $settings['teamName']; ?>" class="form-control" required />
                        </div>
                    </div><!--/row-->

                    <div class="row">
                        <div class="col mb-3">
                            <label for="missionTimeZone" class="form-label">Tijdzone werkland</label>
                            <?= custom_select(
                                $timeZones,
                                $settings['timeZones']['mission'],
                                [
                                    'name' => 'missionTimeZone',
                                ],
                            );
?>

                        </div>

                        <div class="col mb-3">
                            <label for="homeTimeZone" class="form-label">Tijdzone thuisland</label>
                            <?= custom_select(
                                $timeZones,
                                $settings['timeZones']['home'],
                                [
                                    'name' => 'homeTimeZone',
                                ],
                            );
?>
                        </div>
                    </div><!--/row-->
                </div>
            </div><!--/card-->
        </div><!--/col-->
    </div><!--/row-->

    <div class="row">
        <div class="col mb-3">
            <button type="submit" class="btn btn-primary w-100">
                <?= bi('check'); ?> Opslaan
            </button>
        </div>
    </div>
</form>

<?php $this->endSection(); ?>