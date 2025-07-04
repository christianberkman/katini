<?php
$this->extend('layout');
$this->section('main');
echo $this->include('breadcrumbs');
echo nav()->alertView([
    'success' => ['Success', 'Alle wijzigingen zijn opgeslagen', 'alert-success'],
    'failed'  => ['Fout', 'Kon de wijzigingen niet opslaan', 'alert-danger'],
]);
?>
<div class="row">
    <div class="col-lg-6 m-auto">
        <div class="card">
            <form action="<?= current_url(); ?>" method="post">
                <div class="card-body">
                    <ul class="list-group list-group-flush mb-3">
                    <?php foreach ($circles as $circle): ?>
                    <li class="list-group-item">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="circle_ids[]" id="<?= "check{$circle->id}"; ?>" value="<?= $circle->id; ?>" <?= $circle->checked ? 'checked' : ''; ?> />
                            <label class="form-check-label" for="<?= "check{$circle->id}"; ?>">
                                <?= $circle->circle_name; ?>
                            </label>
                        </div>
                    </li>
                    <?php endforeach; ?>
                    </ul>

                    <button type="submit" class="btn btn-success w-100">
                        <?= bi('check'); ?> Wijzigingen opslaan
                    </button>
                </div>
            </form>
        </div><!--/card-->
    </div>
</div><!--/row--->
<?php $this->endSection(); ?>