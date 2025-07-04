<?php

namespace App\Controllers;

use App\Entities\Supporter;

class Supporters extends BaseController
{
    protected $supporter;
    protected $allowedKeys = [
        'first_name',
        'last_name',
        'infix',
        'title',
        'org_name',
        'phone',
        'email',
        'address_street',
        'address_number',
        'address_addition',
        'address_postcode',
        'address_city',
        'date_birth',
        'iban',
        'note',
        'created_at',
    ];
    protected $orderOptions = [
        'name'   => 'Naam (A-Z)',
        'city'   => 'Plaatsnaam (A-Z)',
        'oldest' => 'Oudste eerst',
        'newest' => 'Nieuwste eerst',
    ];

    public function setup()
    {
        nav()->addBreadcrumb('Supporters', '/supporters');
    }

    /**
     * GET /supporters/
     * Supporter dashboard
     */
    public function index()
    {
        // Auth
        if (! auth()->user()->can('supporter.read')) {
            return accessDenied();
        }

        $supporterModel = model('SupporterModel');

        // Stats
        $data['supporters_count'] = count($supporterModel->select('id')->findAll());

        // Most recent
        $data['supporters'] = $supporterModel->orderBy('created_at ASC')->findAll(10);

        // View
        nav()->setPageTitle('Supporters');

        return view('supporters/index', $data);
    }

    /**
     * GET /supporters/find
     * Find supporters
     */
    public function find()
    {
        // Auth
        if (! auth()->user()->can('supporter.read')) {
            return accessDenied();
        }

        // View
        nav()->setPageTitle('Supporter vinden');
        nav()->addBreadcrumb('Supporter vinden');

        return view('supporters/find');
    }

    /**
     * GET /supporters/all
     * Paginate all supporters
     */
    public function all()
    {
        // Auth
        if (! auth()->user()->can('supporter.read')) {
            return accessDenied();
        }

        $supporterModel = model('SupporterModel');

        // Circles
        $circleIds = $this->request->getGet('circleIds') ?? [];
        if (count($circleIds) > 0) {
            $circleSupporterIds = model('SupportersCirclesModel')
                ->whereIn('circle_id', $circleIds)
                ->findColumn('supporter_id');
            $supporterModel->whereIn('id', $circleSupporterIds);
        }

        // Order
        $order = $this->request->getGet('order');

        switch ($order) {
            case 'name':
            default:
                $supporterModel->orderBy('display_name');
                break;

            case 'city':
                $supporterModel->orderby('CASE WHEN address_city = \'\' THEN 2 ELSE 1 END, address_city');
                break;

            case 'newest':
                $supporterModel->orderBy('created_at DESC');
                break;

            case 'oldest':
                $supporterModel->orderBy('created_at ASC');
                break;
        }

        $supporters = $supporterModel
            ->paginate(30);
        $data = [
            'selectedCircleIds' => $circleIds,
            'orderOptions'      => $this->orderOptions,
            'order'             => $order,
            'supporters'        => $supporters,
            'pager'             => $supporterModel->pager,
        ];

        // View
        nav()->setPageTitle('Alle Supporters');
        nav()->addBreadcrumb('Alle Supporters');

        return view('supporters/all', $data);
    }

    /**
     * GET /supporters/$supporterId
     */
    public function view(int $supporterId)
    {
        // Auth
        if (! auth()->user()->can('supporter.read')) {
            return accessDenied();
        }

        // Get supporter
        $supporterModel  = model('SupporterModel');
        $this->supporter = $supporterModel->withDeleted()->find($supporterId);

        if (null === $this->supporter) {
            show404();
        }

        // View
        nav()->setPageTitle($this->supporter->compileDisplayName());
        nav()->addBreadcrumb($this->supporter->display_name, $this->supporter->url, false);
        $data = ['supporter' => $this->supporter];

        return view('supporters/view', $data);
    }

    /**
     * GET /supporters/new
     */
    public function new()
    {
        // Auth
        if (! havePermission('supporter.read', 'supporter.write')) {
            return accessDenied();
        }

        // Supporter
        $data['supporter'] = new Supporter();

        // View
        nav()->setPageTitle('Supporter toevoegen');
        nav()->addBreadcrumb('Supporter toevoegen');

        return view('supporters/form', $data);
    }

    /**
     * POST /supporters/new
     */
    public function insert()
    {
        // Auth
        if (! havePermission('supporter.read', 'supporter.write')) {
            return accessDenied();
        }

        // Create supporter entity
        $this->supporter = new Supporter();

        foreach ($this->allowedKeys as $key) {
            $this->supporter->{$key} = $this->request->getPost($key);
        }

        // Check for at least one name
        if (! $this->supporter->hasName()) {
            return nav()->backFailed('Supporter moet tenminste één naam (voornaam, achternaam, bedrijfsnaam) hebben.');
        }

        // Insert
        $supporterModel = model('SupporterModel');
        $insert         = $supporterModel->insert($this->supporter);

        // Success
        if ($insert) {
            $this->supporter->id = $insert;

            return nav()->toCustom($this->supporter->url, 'created');
        }

        // Error
        dd($supporterModel->errors());
    }

    /**
     * GET /supporters/basic/edit/$supporterId
     */
    public function edit(int $supporterId)
    {
        // Auth
        if (! havePermission('supporter.read', 'supporter.write')) {
            return accessDenied();
        }

        // Get supporter
        $supporterModel  = model('SupporterModel');
        $this->supporter = $supporterModel->withDeleted()->find($supporterId);

        if (null === $this->supporter) {
            show404();
        }

        // View
        nav()->setPageTitle([$this->supporter->compileDisplayName(), 'Bewerken']);
        nav()->addBreadcrumb($this->supporter->display_name, $this->supporter->url, false);
        nav()->addBreadcrumb('Bewerken');
        $data = ['supporter' => $this->supporter];

        return view('supporters/form', $data);
    }

    /**
     * POST /supporters/basic/edit/$supporterId
     *
     * @param mixed $supporterId
     */
    public function update($supporterId)
    {
        // Auth
        if (! havePermission('supporter.read', 'supporter.write')) {
            return accessDenied();
        }

        // Get supporter
        $supporterModel  = model('SupporterModel');
        $this->supporter = $supporterModel->withDeleted()->find($supporterId);

        if (null === $this->supporter) {
            show404();
        }

        // Update entity values
        foreach ($this->allowedKeys as $key) {
            $this->supporter->{$key} = $this->request->getPost($key);
        }

        // Check for at least one name
        if (! $this->supporter->hasName()) {
            return nav()->backFailed('Supporter moet tenminste één naam (voornaam, achternaam, bedrijfsnaam) hebben.');
        }

        // Go back if entity did not change
        if (! $this->supporter->hasChanged()) {
            return nav()->toCustom($this->supporter->url, 'no_change');
        }

        // Update database
        $update = $supporterModel->update($this->supporter->id, $this->supporter);

        // Back to form if validation error
        if (! $update) {
            return nav()->backFailed()->with('errors', $supporterModel->getValidationMessages());
        }

        // Success
        return nav()->toSuccess($this->supporter->url, '');
    }

    /**
     * GET /supporters/$supporterId/circles
     */
    public function circles(int $supporterId)
    {
        // Auth
        if (! havePermission('supporter.read', 'supporter.write')) {
            return accessDenied();
        }

        // Get supporterall_list
        $data['supporter'] = supporter($supporterId, true);

        if (null === $data['supporter']) {
            show404();
        }

        // Get circles, mark supporter circles
        $data['circles']    = model('CirclesModel')->findAll();
        $supporterCircleIds = $data['supporter']->getCircleIds();

        foreach ($data['circles'] as $circle) {
            $circle->checked = false;

            if (in_array($circle->id, $supporterCircleIds, true)) {
                $circle->checked = true;
            }
        }

        // View
        nav()->setPageTitle([$data['supporter']->compileDisplayName(), 'Kringen']);
        nav()->addBreadCrumb($data['supporter']->display_name, $data['supporter']->url, false);
        nav()->addBreadcrumb('Kringen');

        return view('supporters/circles', $data);
    }

    /**
     * POST /supporters/$supporterId/circles
     */
    public function updateCircles(int $supporterId)
    {
        // Auth
        if (! havePermission('supporter.read', 'supporter.write')) {
            return accessDenied();
        }

        $supporter = supporter($supporterId, true);
        if ($supporter === null) {
            show404();
        }

        // Sync
        $sync = model('SupportersCirclesModel')->sync($supporter->id, $this->request->getPost('circle_ids') ?? []);

        if ($sync) {
            return nav()->toSuccess("/supporters/{$supporterId}");
        }

        return nav()->backFailed();
    }

    /**
     * GET /supporters/$supporterId/delete
     */
    public function delete(int $supporterId)
    {
        // Auth
        if (! havePermission('supporter.read', 'supporter.write')) {
            return accessDenied();
        }

        // Get supporter
        $supporterModel  = model('SupporterModel');
        $this->supporter = $supporterModel->withDeleted()->find($supporterId);

        if (null === $this->supporter) {
            show404();
        }

        $supporterModel = model('SupporterModel');
        $delete         = $supporterModel->delete($this->supporter->id);

        if ($delete) {
            return nav()->toCustom('/supporters/all', 'delete-success', $this->supporter->compileDisplayName());
        }
    }

    /**
     * GET /find.json?q=$query
     */
    public function findJson()
    {
        // Auth
        if (! havePermission('supporter.read')) {
            return accessDenied();
        }

        $query = $this->request->getGet('q') ?? '';
        $limit = (int) ($this->request->getGet('limit') ?? 25);

        $words = explode(' ', $query);

        $supporterModel = model('SupporterModel');

        foreach ($words as $word) {
            $supporterModel->like('display_name', $word);
        }
        $supporters = $supporterModel->orderBy('display_name')->findAll();

        $supportersArray = [];

        foreach ($supporters as $supporter) {
            $supportersArray[] = [
                'id' => $supporter->id,
                'display_name' => $supporter->compileDisplayName(false),
                'address_city' => $supporter->address_city,
                'url'          => $supporter->url,
            ];
        }

        $supportersArray = array_slice($supportersArray, 0, $limit);

        $response = [
            'resultsCount' => count($supporters),
            'resultsShown' => count($supportersArray),
            'results'      => $supportersArray,
        ];

        // $this->response->setContentType('application/json');

        return json_encode($response, JSON_PRETTY_PRINT);
    }
}
