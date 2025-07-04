<div class="row mb-3">
    <div class="alert <?= $alert[2] ?? ''; ?> alert-dismissible fade show" role="alert">
    <strong><?= $alert[0] ?? 'Geen titel ingegeven'; ?></strong> <?= $alert[1] ?? 'Geen bericht ingegeven'; ?>
    <?php if ($details !== null): ?>
    <em><?= $details; ?></em>
    <?php endif; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>