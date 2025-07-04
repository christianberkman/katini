<?php
$this->extend('layout');
$this->section('main');
echo $this->include('breadcrumbs');
?>
<pre><?= $output; ?></pre>
<?php $this->endSection(); ?>