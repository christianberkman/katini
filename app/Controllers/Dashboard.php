<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    /**
     * GET /
     * Dashboard
     */
    public function index()
    {
        // Data
        $data['supporters_count'] = count((model('SupporterModel'))->findColumn('id'));
        $data['donationStats']    = Donations::getDonationStats(90);

        // View
        nav()->setPageTitle('Dashboard');

        return view('dashboard/index', $data);
    }
}
