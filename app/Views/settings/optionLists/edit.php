<?php
$this->extend('layout');
$this->section('main');
echo $this->include('breadcrumbs');
echo nav()->alertView([
    'success' => ['Success', 'De lijst is hersteld naar de standaard instellingen.', 'alert-success'],
    'failed'  => ['Fout', 'Kon de lijst niet herstellen naar de standaard instellingen.', 'alert-warning'],
])
?>
<div class="row mb-3">
    <div class="col-lg-6 m-auto">
        <form method="post" method="<?= current_url(); ?>" id="listForm">
            <input type="hidden" name="name" value="<?= $listName; ?>" />

            <div class="card">
                <div class="card-body">
                    <div class="form-check form-check-inline mt-3">
                        <input class="form-check-input" type="checkbox" name="sort" id="autoSortCheck" value="1" <?= ($list['sort'] ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="autoSortCheck">Sorteer deze lijst op alfabetische volgorde (krijgt effect na opslaan)</label>
                    </div>
                </div>
                <ul class="list-group list-group-flush" id="itemList">
                    <?php foreach ($list['items'] as $item): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <input type="hidden" name="items[]" value="<?= $item; ?>" />
                        <div>
                            <?= bi('grip'); ?> <?= $item; ?>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-item">
                            <?= bi('delete'); ?>
                        </button>
                    </li>
                    <?php endforeach; ?>
                </ul>

                <div class="card-footer">
                    <button type="button" id="addItemButton" class="btn btn-outline-primary w-100 mb-3">
                        <?= bi('add'); ?> Optie toevoegen
                    </button>

                    <button type="submit" class="btn btn-success w-100 mb-3">
                        <?= bi('save'); ?> Wijzigingen Opslaan
                    </button>

                    <a href="<?= base_urL("settings/optionlists/revert/{$listName}"); ?>" class="btn btn-outline-danger w-100 confirm-action" data-confirm-msgx="Weet u het zeker? Deze actie kan niet ongedaan gemaakt worden.">
                        <?= bi('refresh'); ?> Terug naar standaard instelling
                    </a>

                </div>
            </div><!--/card-->

        </form>
    </div><!--/col-->
</div><!--/row-->

<?php $this->endSection(); ?>
<?php $this->section('script'); ?>
<script>
    $(function(){
        $('#itemList').on('click', '.btn-remove-item', function(){
            $(this).parent().remove()
        })

        $('#itemList').sortable({
            handle: '.bi-grip-vertical'
        });

        const listContainer = $('#itemList')
        const newItemHTML = `<li class="list-group-item d-flex justify-content-between align-items-start">
                        <input type="text" name="items[]" class="form-control me-3" placeholder="Nieuwe optie" />
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-item"><i class="bi bi-x"></i></button>
                    </li>`


        $('#addItemButton').click(function(){
            listContainer.append(newItemHTML);
        })
    });
</script>
<?php $this->endSection(); ?>