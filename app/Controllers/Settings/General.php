<?php

namespace App\Controllers\Settings;

use App\Controllers\BaseController;
use DateTimeZone;
use Throwable;

class General extends BaseController
{
    public function setup()
    {
        $this->user = auth()->user();

        nav()->addBreadcrumb('Instellngen', '/settings');
        nav()->addBreadcrumb('Algemeen', '/settings/general');
    }

    /**
     * GET /settings/general
     */
    public function form()
    {
        // Auth
        if (! havePermission('settings.general')) {
            return accessDenied();
        }

        // Settings
        $data['settings'] = [
            'appName'   => setting('Katini.appName'),
            'teamName'  => setting('Katini.teamName'),
            'timeZones' => setting('Katini.timeZones'),
        ];

        // Timezones
        $timeZones         = DateTimeZone::listIdentifiers();
        $data['timeZones'] = array_combine($timeZones, $timeZones);

        // View
        nav()->setPageTitle('Algemeen');

        return view('settings/general', $data);
    }

    /**
     * POST /settings/general
     */
    public function update()
    {
        // Auth
        if (! havePermission('settings.general')) {
            return accessDenied();
        }

        try {
            setting('Katini.appName', htmlspecialchars($this->request->getPost('appName')));
            setting('Katini.teamName', htmlspecialchars($this->request->getPost('teamName')));
            $timeZones = [
                'mission' => $this->request->getPost('missionTimeZone'),
                'home'    => $this->request->getPost('homeTimeZone'),
            ];
            setting('Katini.timeZones', $timeZones);
        } catch (Throwable $e) {
            return nav()->backFailed();
        }

        return nav()->backSuccess();
    }
}
