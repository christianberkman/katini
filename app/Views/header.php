<div class="d-flex flex-row align-items-center p-1">
  <div class="col-auto d me-3 d-block d-lg-none">
    <button type="button" class="btn btn-outline-light" data-bs-toggle="offcanvas" data-bs-target="#sidebar">
      <i class="bi bi-list"></i>
    </button>
  </div>

  <div class="col-auto me-3">
    <span class="fs-3"><?= setting('Katini.appName'); ?></span>
  </div>

  <div class="col-auto d-none d-sm-block ms-3 me-auto">
    <a href="<?= site_url('/settings/user'); ?>" class="link-underline link-underline-opacity-0">
      <?= bi('user'); ?> <?= auth()->user()->first_name; ?>
    </a>
  </div>

  <div class="time-display col-auto d-none d-sm-block flex-grow-1 text-end">
    <span class="badge bg-success katini-time" data-katini-time-zone="home" data-katini-time-format="weekdayTime">
      <?= bi('home'); ?>
      <?= katini()->getFormattedTime('home', 'weekdayTime'); ?>
    </span>
    <span class="badge bg-secondary katini-time" data-katini-time-zone="mission" data-katini-time-format="weekdayTime">
      <?= bi('mission'); ?>
      <?= katini()->getFormattedTime('mission', 'weekdayTime'); ?>
    </span>
  </div>

</div><!--/flex-row-->