<?php
$optionList = katini()->getList($listName);
?>
<select name="<?= $name ?? $listName; ?>" id="<?= $id ?? $listName; ?>" class="form-select">
    <?php if (! ($required ?? false)): ?><option value=''>(geen)</option><?php endif; ?>
    <?php foreach ($optionList['items'] as $item): ?>
    <option value="<?= $item; ?>"<?= ($item === ($selected ?? null) ? ' selected' : ''); ?>><?= $item; ?></option>
    <?php endforeach; ?>
    <?php if (! in_array($selected, $optionList['items'], true)): ?>
    <option value="<?= $selected; ?>" selected><?= $selected; ?></option>
    <?php endif; ?>
</select>
