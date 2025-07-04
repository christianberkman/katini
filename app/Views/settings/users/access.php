<?php
$this->extend('layout');
$this->section('main');
echo $this->include('breadcrumbs');
echo nav()->alertView();
?>
<?php if ($user !== null): ?>
    <div class="row">
        <div class="col mb-3">
            <div class="card">
                <div class="card-header">
                    <h4>Groepen</h4>
                </div>
                <div class="card-body">
                    <form id="groupForm" method="post" action="<?= current_url(); ?>">
                        <input type="hidden" name="userId" value="<?= $user->id; ?>" />
                        <input type="hidden" name="formAction" value="groups" />
                        <ul class="list-group mb-3">
                            <?php foreach ($groups as $groupId => $group): ?>
                                <li class="list-group-item">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="groupIds[]" id="groupCheck-<?= $groupId; ?>" value="<?= $groupId; ?>" <?= $user->inGroup($groupId) ? 'checked' : ''; ?> />
                                        <label class="form-check-label" for="groupCheck-<?= $groupId; ?>">
                                            <?= $group['title']; ?> <em>&mdash; <?= $group['description']; ?></em>
                                        </label>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="row justify-content-end">
                            <div class="col-auto mb-3">
                                <button type="submit" class="btn btn-primary">
                                    <?= bi('check'); ?> Opslaan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div><!--/row-->

    <div class="row">
        <div class="col mb-3">
            <div class="card">
                <div class="card-header">
                    <h4>Rechten</h4>
                </div>
                <div class="card-body">
                    <form id="permissionsForm" method="post" action="<?= current_url(); ?>">
                        <input type="hidden" name="userId" value="<?= $user->id; ?>" />
                        <input type="hidden" name="formAction" value="permissions" />
                        <p>
                            Rechten toegewezen door de groep kunnen niet gewijzigd worden.
                        </p>
                        <ul class="list-group mb-3">
                            <?php foreach ($permissions as $permissionId => $description): ?>
                                <li class="list-group-item">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissionIds[]"
                                            id="permissionCheck-<?= $permissionId; ?>" value="<?= $permissionId; ?>" <?= $user->hasPermission($permissionId) || $user->can($permissionId) ?
                                                                                                                        'checked' : ''; ?> <?= $user->can($permissionId) && ! $user->hasPermission($permissionId) ? 'disabled' : ''; ?> />
                                        <label class="form-check-label" for="permissionCheck-<?= $permissionId; ?>">
                                            <?= $permissionId; ?> <em>&mdash;
                                                <?= $description; ?>
                                                <?= $user->can($permissionId) && ! $user->hasPermission($permissionId) ? '(toegewezen via groep)' : ''; ?>
                                            </em>
                                        </label>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="row justify-content-end">
                            <div class="col-auto mb-3">
                                <button type="submit" class="btn btn-primary">
                                    <?= bi('check'); ?> Opslaan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div><!--/row-->
    <?php d($user); ?>
<?php endif; ?>
<?php
$this->endSection();
$this->section('script');
?>
<script>
    $(function() {
        $('#userIdSelect').change(function() {
            $('#userIdForm').submit()
        })
    })
</script>
<?php $this->endSection(); ?>