<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Datum</th>
            <th>Supporter</th>
            <th>Bedrag</th>
            <th>Methode</th>
            <th>Periodiek</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($donations as $donation): ?>
            <tr class="position-relative">
                <td>
                    <a href="<?= url_to('donations-view', $donation->id); ?>" class="stretched-link">
                        <?= formatTime($donation->created_at, 'longDate'); ?>
                    </a>
                </td>
                <td>
                    <?= ($donation->supporter !== null ? $donation->supporter->compileDisplayName(false) : 'Anoniem'); ?>
                </td>
                <td>
                    <?= $donation->formattedAmount; ?>
                </td>
                <td>
                    <?= $donation->method; ?>
                </td>
                <td>
                    <?= $donation->is_recurring === 1 ? $donation->getFrequency(false) : ''; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <?php if (isset($sum)): ?>
        <tfoot>
            <tr>
                <td colspan="6" class="text-center">
                    <em>Totaal <?= formatAmount($sum); ?></em>
                </td>
            </tr>
        </tfoot>
    <?php endif; ?>
</table>