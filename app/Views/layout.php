<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>
        <?= nav()->getSiteTitle(); ?>
    </title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.5/dist/flatly/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="<?= base_url('assets/Katini/style.css'); ?>" rel="stylesheet">
</head>

<body>
    <nav class="container-fluid bg-primary text-light">
        <?= view('header'); ?>
    </nav>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebar">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Katini</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0" id="sidebarContent">
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <!-- Static Sidebar for lg+ -->
            <nav class="col-lg-3 col-xl-2 d-none d-lg-block vh-100 p-0" id="staticSidebar">
                <!--time-->
                <div class="sidebar-main d-sm-none">
                    <span class="badge bg-success katini-time" data-katini-time-zone="home" data-katini-time-format="weekdayTime">
                        <?= bi('home'); ?>
                        <?= katini()->getFormattedTime('home', 'weekdayTime'); ?>
                    </span>
                    <span class="badge bg-secondary katini-time" data-katini-time-zone="mission" data-katini-time-format="weekdayTime">
                        <?= bi('mission'); ?>
                        <?= katini()->getFormattedTime('mission', 'weekdayTime'); ?>
                    </span>
                </div>

                <!-- user -->
                <div class="sidebar-main d-sm-none">
                    <a href="<?= site_url('/settings/user'); ?>" class="link-underline link-underline-opacity-0">
                        <?= bi('user'); ?> <?= auth()->user()->first_name; ?>
                    </a>
                </div>
                <hr class="d-sm-none" />

                <?= view('sidebar'); ?>
            </nav>

            <!-- Main Content -->
            <main class="col-12 col-lg-9 col-xl-10 p-4" style="background-color: #ddd" ;>
                <?= $this->renderSection('main'); ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.14.0/jquery-ui.min.js" integrity="sha256-Fb0zP4jE3JHqu+IBB9YktLcSjI1Zc6J2b6gTjB0LpoM=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.7.8/handlebars.min.js" integrity="sha512-E1dSFxg+wsfJ4HKjutk/WaCzK7S2wv1POn1RRPGh8ZK+ag9l244Vqxji3r6wgz9YBf6+vhQEYJZpSjqWFPg9gg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js"></script>
    <script type="module">
        import {
            KatiniModule
        } from '<?= base_url('assets/Katini/katini.js'); ?>';

        window.katini = new KatiniModule({
            baseUrl: '<?= base_url(); ?>'
        })
    </script>
    <script>
        window.katiniSiteUrl = '<?= site_url(); ?>';
        window.justValidateConfig = {
            errorFieldCssClass: 'is-invalid',
            errorLabelCssClass: 'invalid-feedback',
            successFieldCssClass: 'is-valid',
            submitFormAutomatically: true,
        }
    </script>

    <?= $this->renderSection('script'); ?>

</body>

</html>