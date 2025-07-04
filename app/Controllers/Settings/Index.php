<?php

namespace App\Controllers\Settings;

use App\Controllers\BaseController;

class Index extends BaseController
{
    /**
     * GET instellingen
     */
    public function index()
    {
        nav()->setPageTitle('Instellingen');

        return view('settings/index');
    }
}
