<div class="table-responsive">
    <table class="table table-striped table-hover w-100" style="white-space:nowrap;">
        <thead>
            <tr>
                <th>Naam</th>
                <th>Plaats</th>
                <th class="d-none d-sm-block">Supporter sinds</th>
                <th>Kringen</th>
            </tr>
        </thead>
        <tobdy>
            <?php foreach ($supporters as $supporter): ?>
                <tr style="position: relative">
                    <td>
                        <a href="<?= site_url("/supporters/{$supporter->id}"); ?>" class="stretched-link">
                            <?= $supporter->display_name; ?>
                        </a>
                    </td>
                    <td><?= $supporter->address_city; ?></td>
                    <td class="d-none d-sm-block"><?= formatTime($supporter->created_at, 'longDate'); ?></td>
                    <td>
                        <?php foreach ($supporter->circles as $circle): ?>
                            <span class="badge bg-secondary mt-1 mb-1">
                                <?= $circle->circle_name; ?>
                            </span>
                        <?php endforeach; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tobdy>
    </table>
</div>