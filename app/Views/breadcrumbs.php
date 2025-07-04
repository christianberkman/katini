<div class="pagetitle">
    <h1><?= nav()->getPageTitle(); ?></h1>
    <?php
        $crumbs = nav()->getBreadcrumbs();
    $lastCrumb  = array_key_last($crumbs);
    if (count($crumbs) > 1):
        ?>
    <nav>
        <ol class="breadcrumb">
            <?php
                    foreach ($crumbs as $crumb):
                        ?>
            <li class="breadcrumb-item">
                <?php if ($crumb['url'] !== null && $crumb !== $crumbs[$lastCrumb]): ?>
                    <a href="<?= $crumb['url']; ?>"><?= $crumb['title']; ?></a>
                <?php else: ?>
                    <?= $crumb['title']; ?>
                <?php endif; ?>
            </li>
            <?php endforeach; ?>
        </ol>
    </nav>
    <?php endif; ?>
</div><!-- End Page Title -->