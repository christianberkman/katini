<?php
$this->extend('layout');
$this->section('main');
echo $this->include('breadcrumbs');
echo nav()->alertView();
?>

<div class="row justify-content-end">
    <div class="col-auto mb-3">
        <a href="<?= site_url('/settings/users/new'); ?>" class="btn btn-success">
            <?= bi('add'); ?> Nieuwe gebruiker
        </a>
    </div>
</div>

<div class="row">
    <div class="col mb-3">
        <table class="table tble-sriped mb-3">
            <thead>
                <tr>
                    <td>ID</td>
                    <td>Email</td>
                    <td>Naam</td>
                    <td>Groepen</td>
                    <td>Acties</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr class="<?= $user->isBanned() ? 'text-danger' : 'x'; ?>">
                    <td><?= $user->id; ?></td>
                    <td><?= $user->email; ?></td>
                    <td><?= $user->full_name; ?></td>
                    <td><?= implode(', ', $user->getGroups()); ?></td>
                    <td>
                        <a href="<?= site_url("/settings/users/access?userId={$user->id}"); ?>" class="btn btn-secondary btn-sm me-2">
                        <?= bi('key'); ?>
                        </a>
                        <a href="<?= site_url("/settings/users/edit/{$user->id}"); ?>" class="btn btn-secondary btn-sm me-2">
                            <?= bi('edit'); ?>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div><!--/col-->
</div><!--/row-->

<?php $this->endSection(); ?>