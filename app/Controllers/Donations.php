<?php

namespace App\Controllers;

use App\Entities\Donation;
use App\Entities\Supporter;
use CodeIgniter\I18n\Time;

class Donations extends BaseController
{
    /**
     * Allowed keys from user input
     */
    protected $allowedKeys = [
        'id',
        'created_at',
        'amount',
        'method',
        'note',
        'created_at',
        'is_recurring',
        'interval',
    ];

    protected $model;

    public function setup()
    {
        nav()->addBreadcrumb('Donaties', '/donations');

        $this->model = model('DonationModel');
    }

    /**
     * GET /donations
     * Donations dashboard
     */
    public function index()
    {
        // Auth
        if (! havePermission('donation.read')) {
            return accessDenied();
        }

        // Stats
        $data['donationStats'] = $this->getDonationStats(90);

        // Last 10 donations
        $data['donations'] = $this->model->orderBy('created_at DESC')->findAll(10);

        // View
        nav()->setPageTitle('Donaties');

        return view('donations/index', $data);
    }

    public static function getDonationStats(int $periodLength = 90): array
    {
        $periodDate = (new Time())->subDays($periodLength);

        $row = (model('DonationModel'))
            ->select('count(id) as `count`')
            ->select('sum(amount) as `sum`')
            ->where('created_at >= ', $periodDate)
            ->asArray()
            ->find()[0];

        return [
            'period' => $periodLength,
            'count'  => $row['count'],
            'sum'    => $row['sum'],
        ];
    }

    /**
     * GET /donations/all
     * List all donations
     */
    public function all()
    {
        // Auth
        if (! havePermission('donation.read')) {
            return accessDenied();
        }

        // Find donations
        $donationModel = model('DonationModel');

        // Frequency
        $frequency = $this->request->getGet('frequency');

        switch ($frequency) {
            case 'all':
            default:
                break;

            case 'recurring':
                $donationModel->where('is_recurring', 1);
                break;

            case 'onetime':
                $donationModel->where('is_recurring', 0);
                break;
        }

        // Period
        $period = $this->request->getGet('period');

        switch ($period) {
            case 'all':
            default:
                break;

            case 'month':
                $oneMonthAgo = (Time::today())->subMonths(1);
                $donationModel->where('created_at >=', $oneMonthAgo->toDateString());
                break;

            case 'quarter':
                $threeMonthsAgo = (Time::today())->subMonths(3);
                $donationModel->where('created_at >=', $threeMonthsAgo->toDateString());
                break;

            case 'year':
                $oneYearAgo = (Time::today())->subYears(1);
                $donationModel->where('created_at >=', $oneYearAgo->toDateString());
                break;
        }

        // Order
        $order = $this->request->getGet('order');

        switch ($order) {
            case 'date_desc':
            default:
                $donationModel->orderBy('created_at DESC');
                break;

            case 'amount_desc':
                $donationModel->orderBy('amount DESC');
                break;

            case 'supporter_asc':
                $donationModel->join('supporters', 'supporters.id = donations.id');
                $donationModel->orderBy('display_name ASC');
        }

        $donations = $donationModel->paginate(30);

        // Sum
        $sum = 0;

        foreach ($donations as $donation) {
            $sum += $donation->amount;
        }

        $data = [
            'donations' => $donations,
            'pager'     => $donationModel->pager,
            'frequency' => $frequency,
            'period'    => $period,
            'order'     => $order,
            'sum'       => $sum,
        ];

        // View
        nav()->setPageTitle('Alle Donaties');

        return view('donations/all', $data);
    }

    /**
     * GET /donations/$donationId
     */
    public function view(int $donationId)
    {
        // Auth
        if (! havePermission('donation.read')) {
            return accessDenied();
        }

        // Find donation
        $donation = donation($donationId);
        if ($donation === null) {
            show404();
        }

        // View
        nav()->setPageTitle($donation->title);

        return view('donations/view', ['donation' => $donation]);
    }

    /**
     * GET /donations/new
     */
    public function new()
    {
        // Auth
        if (! havePermission('donation.read', 'donation.write')) {
            return accessDenied();
        }

        $data = [
            'donation' => new Donation(),
        ];

        // Supporter
        $supporterId = $this->request->getGet('supporterId');
        if (! empty($supporterId)) {
            $data['supporter'] = supporter($supporterId);
        }

        $data['donation'] = new Donation();
        $data['donation'] = new Donation();

        // View
        nav()->setPageTitle('Donatie toevoegen');

        return view('donations/form', $data);
    }

    /**
     * POST /donations/new
     */
    public function insert()
    {
        // Auth
        if (! havePermission('donation.read', 'donation.write')) {
            return accessDenied();
        }

        // Create new donation entity
        $donation = new Donation();

        foreach ($this->allowedKeys as $key) {
            $donation->{$key} = $this->request->getPost($key);
        }

        $donation->updateRecurring();

        // Insert
        $donationModel = model('DonationModel');
        $insert        = $donationModel->insert($donation);

        // Error
        if (! $insert) {
            $errors = $donationModel->validation->getErrors();

            return nav()->backFailed()->with('errors', $errors);
        }

        // Success
        return nav()->toCustom("donations/{$insert}", 'success');
    }

    /**
     * GET /donations/edit/$donationId
     */
    public function edit(int $donationId)
    {
        // Auth
        if (! havePermission('donation.read', 'donation.wrtie')) {
            return accessDenied();
        }

        // Find donation
        $donation = donation($donationId);
        if ($donation === null) {
            show404();
        }

        // View
        nav()->setPageTitle(['Donatie bewerken', $donation->title]);
        nav()->addBreadcrumb($donation->title, $donation->url, false);

        return view('donations/form', ['donation' => $donation]);
    }

    /**
     * POST /donations/edit/$donationId
     */
    public function update(int $donationId)
    {
        // Auth
        if (! havePermission('donation.read', 'donation.write')) {
            return accessDenied();
        }

        // Find donation
        $donation = donation($donationId);
        if ($donation === null) {
            show404();
        }

        // Update database
        foreach ($this->allowedKeys as $key) {
            $donation->{$key} = $this->request->getPost($key);
        }

        $donation->updateRecurring();

        if ($donation->hasChanged()) {
            $update = model('DonationModel')->update($donation->id, $donation);
            if ($update) {
                return nav()->toSuccess("/donations/{$donation->id}");
            }

            return nav()->backFailed();
        }

        return nav()->toCustom("/donations/{$donation->id}", 'no-change');
    }

    /**
     * GET /donations/delete/$donationId
     */
    public function delete(int $donationId)
    {
        // Auth
        if (! havePermission('donation.read', 'donation.write')) {
            return accessDenied();
        }

        // Find donation
        $donation = donation($donationId);
        if ($donation === null) {
            show404();
        }

        // Delete
        $delete = model('DonationModel')->delete($donation->id);

        // Redirect
        if ($delete) {
            return nav()->toCustom('/donations', 'delete-success', $donation->title);
        }

        return nav()->toCustom('(:back)', 'delete-failed');
    }

    /**
     * GET /donations/supporter/$supporterId
     */
    public function supporter(int $supporterId)
    {
        // Auth
        if (! havePermission('donation.read')) {
            return accessDenied();
        }

        // Find supporter
        $supporter = supporter($supporterId);
        if ($supporter === null) {
            show404();
        }

        // Find donations
        $donations = model('DonationModel')
            ->where('id', $supporterId)
            ->findAll();

        // View
        nav()->setPageTitle(['Donaties', $supporter->compileDisplayName()]);
        nav()->addBreadCrumb($supporter->compileDisplayName(), $supporter->url);

        return view('donations/supporter', ['supporter' => $supporter, 'donations' => $donations]);
    }

    /**
     * GET /donations/proccess
     * Proccess recurring donations
     */
    public function proccess()
    {
        // Auth
        if (! havePermission('donation.write')) {
            return noAccess();
        }

        // View
        nav()->setPageTitle('Periodieke donaties verwerken');
        $data['output'] = command('katini:recurring');

        return view('donations/proccess', $data);
    }
}
