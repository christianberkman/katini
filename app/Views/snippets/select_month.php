<?php $selected ??= null; ?>
<select name="<?= $name ?? 'month'; ?>" id="<?= $id ?? $name ?? 'month'; ?>" class="form-select">
    <option value="" <?= $selected === null ? 'selected' : ''; ?>>onbekend</option>
    <option value="1" <?= $selected === 1 ? 'selected' : ''; ?>>januari</option>
    <option value="2" <?= $selected === 2 ? 'selected' : ''; ?>>februari</option>
    <option value="3" <?= $selected === 3 ? 'selected' : ''; ?>>maart</option>
    <option value="4" <?= $selected === 4 ? 'selected' : ''; ?>>april</option>
    <option value="5" <?= $selected === 5 ? 'selected' : ''; ?>>mei</option>
    <option value="6" <?= $selected === 6 ? 'selected' : ''; ?>>juni</option>
    <option value="7" <?= $selected === 7 ? 'selected' : ''; ?>>juli</option>
    <option value="8" <?= $selected === 8 ? 'selected' : ''; ?>>augustus</option>
    <option value="9" <?= $selected === 9 ? 'selected' : ''; ?>>september</option>
    <option value="10" <?= $selected === 10 ? 'selected' : ''; ?>>oktober</option>
    <option value="11" <?= $selected === 11 ? 'selected' : ''; ?>>november</option>
    <option value="12" <?= $selected === 12 ? 'selected' : ''; ?>>december</option>
</select>