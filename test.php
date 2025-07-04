<?php

setlocale(LC_TIME, 'nl_NL');
$time = new DateTime();
echo $time->format('D');
