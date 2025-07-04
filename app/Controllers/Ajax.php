<?php

namespace App\Controllers;

class Ajax extends BaseController
{
    /**
     * GET /ajax/huidige-tijd
     * Return the current time for all time zones and formats
     */
    public function currentTime()
    {
        $output = [];

        $timeZones   = setting('Katini.timeZones');
        $timeFormats = setting('Katini.timeFormats');

        foreach ($timeZones as $timeZoneAlias => $timeZone) {
            foreach ($timeFormats as $timeFormatAlias => $timeFormat) {
                $output[$timeZoneAlias][$timeFormatAlias] = katini()->getFormattedTime($timeZoneAlias, $timeFormatAlias);
            }
        }

        $this->response->setContentType('application/json');

        return json_encode($output);
    }
}
