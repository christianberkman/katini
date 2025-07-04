<?php
$this->extend('layout');
$this->section('main');
echo $this->include('breadcrumbs');
echo nav()->alertView([
    'delete-success' => ['Success', 'De donatie is verwijderd: ', 'alert-success'],
]);
?>
<div class="row justify-content-end">
    <div class="col-auto">
        <a href="<?= site_url('donations/new'); ?>" class="btn btn-success">
            <?= bi('add'); ?> Donatie toevoegen
        </a>
    </div><!--/col-->
</div><!--/row-->

<?= $pager->links(); ?>

<form action="<?= current_url(); ?>" method="get" id="filterForm">
    <div class="row justify-content-end">
        <div class="col-auto mb-3">
            <div class="input-group">
                <span class="input-group-text">Interval</span>
                <?= custom_select(
                    [
                        'all'       => 'Alle',
                        'recurring' => 'Alleen periodiek',
                        'onetime'   => 'Alleen eenmalig',
                    ],
                    $frequency,
                    [
                        'name' => 'frequency',
                        'id'   => 'frequencySelect',
                    ],
                ); ?>
            </div>
        </div><!--/col-->

        <div class="col-auto mb-3">
            <div class="input-group">
                <span class="input-group-text">Periode</span>
                <?= custom_select(
                    [
                        'all'     => 'Alles',
                        'month'   => 'Afgelopen maand',
                        'quarter' => 'Afgelopen 3 maanden',
                        'year'    => 'Afgelopen jaar',
                    ],
                    $period,
                    [
                        'name' => 'period',
                        'id'   => 'periodSelect',
                    ],
                ); ?>
            </div>
        </div><!--/col-->

        <div class="col-auto mb-3">
            <div class="input-group">
                <span class="input-group-text">Sorteren</span>
                <?= custom_select(
                    [
                        'date_desc'     => 'Datum (nieuwste eerst)',
                        'date_asc'      => 'Datum (oudste eerst)',
                        'amount_desc'   => 'Bedrag (hoogste eerst)',
                        'amount_asc'    => 'Bedrag (laagste eerst)',
                        'supporter_asc' => 'Naam (A-Z)',
                    ],
                    $order,
                    [
                        'name' => 'order',
                        'id'   => 'orderSelect',
                    ],
                ); ?>
            </div>
</form>
</div>
</div><!--/row-->
</form>

<div class="row">
    <div class="col mb-3">
        <?= $this->include('donations/table'); ?>
    </div><!--/col-->
</div><!--/row-->
<?= $pager->links(); ?>
<?php
$this->endSection();
$this->section('script'); ?>
<script>
    $(function () {
        $('#filterForm').change(function () {
            $(this).submit()
        })
    })
</script>
<?php $this->endSection(); ?>