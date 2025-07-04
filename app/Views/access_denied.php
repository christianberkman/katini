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

    <body class="vh-100">
        <div class="container h-100 d-flex justify-content-center">
            <div class="col-lg-5 m-auto">
                <div class="card">
                    <div class="card-header bg-danger-subtle">
                        <h4 class="card-title">Toegang geweigerd</h4>
                    </div>
                    <div class="card-body">
                        U heeft onvoldoende rechten om deze pagina te bekijken of om deze actie uit te voeren.<br />
                        <br />
                        <i>Onbrekend toegangsrecht: <?= session('missing_permission') ?? 'onbekend'; ?></i>
                    </div>
                </div><!--/card-->
            </div><!--/col-->
        </div><!--/container-->
    </body>

</html>