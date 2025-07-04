<?php
$this->extend('layout');
$this->section('main');
?>
<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
    <div class="col mb-3">
        <div class="card h-100">
            <div class="card-header">
                <h3>Tijd en Datum</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5>
                            <?= bi('home'); ?> Thuisland
                            <?= (auth()->user()->timeZone === 'home' ? bi('user', true) : ''); ?>
                        </h5>
                        <span class="katini-time" data-katini-time-zone="home" data-katini-time-format="time"><strong>
                                <?= katini()->getFormattedTime('home', 'time'); ?>
                            </strong></span><br />
                        <span class="katini-time" data-katini-time-zone="home" data-katini-time-format="mediumDate">
                            <?= katini()->getformattedTime('home', 'mediumDate'); ?>
                        </span>
                        <?php if (auth()->user()->timeZone === 'home'): ?>
                        <br /><em>
                            <?= katini()->getTimeDiffStr('home', 'mission'); ?>
                        </em>
                        <?php endif; ?>
                    </div>
                    <div class="col">
                        <h5>
                            <?= bi('mission'); ?> Werkland
                            <?= (auth()->user()->timeZone === 'mission' ? bi('user', true) : ''); ?>
                        </h5>
                        <span class="katini-time" data-katini-time-zone="mission"
                            data-katini-time-format="time"><strong>
                                <?= katini()->getFormattedTime('mission', 'time'); ?>
                            </strong></span><br />
                        <span class="katini-time" data-katini-time-zone="mission" data-katini-time-format="mediumDate">
                            <?= katini()->getformattedTime('mission', 'mediumDate'); ?>
                        </span>
                        <?php if (auth()->user()->timeZone === 'mission'): ?>
                        <br /><em>
                            <?= katini()->getTimeDiffStr('mission', 'home'); ?>
                        </em>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div><!--/card-->
    </div><!--/col-->

    <div class="col mb-3">
        <div class="card h-100">
            <div class="card-header">
                <h3>Supporters</h3>
            </div>
            <div class="card-body align-item-center d-flex align-items-center justify-content-center">
                <div class="fs-1 text-center">
                    <a href="<?= site_url('supporters'); ?>" class="stretched-link"></a>
                    <?= $supporters_count ?? 0; ?>
                </div>
            </div>
        </div><!--/card-->
    </div><!--/col-->

    <div class="col mb-3">
        <div class="card h-100">
            <div class="card-header">
                <h3>Donaties</h3>
            </div>
            <div class="card-body align-item-center d-flex align-items-center justify-content-center">
                <div class="text-center">
                    <span class="fs-2">
                        <?= $donationStats['count']; ?>
                        <?= bi('dot'); ?>
                        <?= formatAmount($donationStats['sum']); ?>
                    </span><br />
                    <em>Afgelopen
                        <?= $donationStats['period']; ?> dagen
                    </em>
                    <a href="<?= site_url('donations'); ?>" class="stretched-link"></a>
                </div>
            </div>
        </div><!--/card-->
    </div>
</div><!--/row-->


<?php $this->endSection(); ?>