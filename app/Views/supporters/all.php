<?php
$this->extend('layout');
$this->section('main');
echo $this->include('breadcrumbs');
echo nav()->alertView([
    'delete-success' => ['Success', 'De supporter is verwijderd: ', 'alert-success'],
]);

?>

<div class="row justify-content-end">
    <div class="col-auto mb-3">
        <a href="<?= site_url('supporters/new'); ?>" class="btn btn-success">
            <?= bi('add'); ?> Supporter toevoegen
        </a>
    </div>
</div>

<?= $pager->links(); ?>

<form action="<?= current_url(); ?>" method="get" id="filterForm">
    <div class="row justify-content-end g-1">
        <div class="col col-md-3 col-sm-4 mb-3">
            <button class="btn bg-white w-100 d-flex justify-content-between" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                <span><?= count($selectedCircleIds) > 0 ? count($selectedCircleIds) : 'Alle'; ?> Kringen</span>
                <span class="ms-2"><?= bi('chevron-down'); ?></span>
            </button>

            <div class="dropdown-menu p-2">
                <?php foreach (circles() as $circle): ?>
                <div class="form-check mb-3">
                    <input class="form-check-input check-circle" type="checkbox" name="circleIds[]"
                        id="circleId<?= $circle->id; ?>" value="<?= $circle->id; ?>"
                        <?= in_array($circle->id, $selectedCircleIds, true) ? 'checked' : ''; ?> />
                    <label class="form-check-label w-100" for="circleId<?= $circle->id; ?>">
                        <?= $circle->circle_name; ?>
                    </label>
                </div>
                <?php endforeach; ?>
                <button type="button" class="btn btn-sm w-100 bg-primary-subtle mb-1" id="btnResetCircles">
                    <?= bi('reset'); ?> Wissen
                </button>
                <button type="submit" class="btn btn-sm w-100 bg-primary-subtle">
                    <?= bi('check-lg'); ?> Toepassen
                </button>
            </div>
        </div><!--/col-->

        <div class="col col-md-3 col-sm-4 mb-3">
        <?= custom_select(
            $orderOptions,
            $order,
            [
                'name' => 'order',
                'id'   => 'orderSelect',
            ],
        ); ?>
        </div><!--/col-->
    </div><!--/row-->
</form>

<div class="row">
    <div class="col mb-3">
        <?= $this->include('supporters/table'); ?>
    </div><!--/col-->
</div><!--/row-->
<?= $pager->links(); ?>
<?php
$this->endSection();
$this->section('script');
?>
<script>
    $(function () {
        $('#btnResetCircles').click(function(){
            $('.check-circle').prop('checked', false)
            $('#filterForm').submit()
        })

        $('#orderSelect').change(function (e) {
            $('#filterForm').submit()
        })
    })
</script>
<?php $this->endSection(); ?>