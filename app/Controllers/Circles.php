<?php

namespace App\Controllers;

class Circles extends BaseController
{
    public function setup()
    {
        nav()->addBreadcrumb('Kringen', '/circles');
    }

    /**
     * GET /circles
     */
    public function index()
    {
        $data['circles_with']    = model('CirclesModel')->withCount()->having('supporters_count >', 0)->findAll();
        $data['circles_without'] = model('CirclesModel')->withCount()->having('supporters_count =', 0)->findAll();

        nav()->setPageTitle('Kringen');

        return view('circles/index', $data);
    }

    /**
     * GET /circles/$circleId
     */
    public function view(int $circleId)
    {
        // Circle
        $data['circle'] = model('CirclesModel')->withCount()->find($circleId);
        if ($data['circle'] === null) {
            show404();
        }

        // Supporters
        $data['supporters'] = model('SupportersCirclesModel')
            ->where('id', $circleId)
            ->orderBy('display_name')
            ->asSupporters();

        // View
        nav()->setPageTitle($data['circle']->circle_name);
        nav()->addBreadcrumb($data['circle']->circle_name);

        return view('circles/view', $data);
    }

    /**
     * GET /circles/new
     */
    public function new()
    {
        // Auth
        if (! havePermission('circle.manage')) {
            return accessDenied();
        }

        // View
        nav()->setPageTitle('Nieuwe kring');
        nav()->addBreadcrumb('Nieuwe kring');

        return view('circles/form');
    }

    /**
     * POST /circles/new
     */
    public function insert()
    {
        // Auth
        if (! havePermission('circle.manage')) {
            return accessDenied();
        }

        $circle['circle_name'] = htmlspecialchars($this->request->getPost('circle_name'));
        $circle['note']        = htmlspecialchars($this->request->getPost('note'));

        $insert = model('CirclesModel')->insert($circle);
        if (! $insert) {
            return nav()->toFailed('/circles');
        }

        return nav()->toSuccess("/circles/{$insert}");
    }

    /**
     * GET /circles/$circleId/edit
     */
    public function edit(int $circleId)
    {
        // Auth
        if (! havePermission('circle.manage')) {
            return accessDenied();
        }

        $data['circle'] = model('CirclesModel')->withCount()->find($circleId);
        if ($data['circle'] === null) {
            show404();
        }

        // View
        nav()->setPageTitle($data['circle']->circle_name);
        nav()->addBreadCrumb($data['circle']->circle_name, "circles/{$data['circle']->id}");
        nav()->addBreadCrumb('Bewerken');

        return view('circles/form', $data);
    }

    /**
     * POST /circles/$circleId/edit
     */
    public function update(int $circleId)
    {
        // Auth
        if (! havePermission('circle.manage')) {
            return accessDenied();
        }

        $circlesModel = model('CirclesModel');

        $circle = $circlesModel->find($circleId);
        if ($circle === null) {
            show404();
        }

        // Update
        $circle->circle_name = htmlspecialchars($this->request->getPost('circle_name'));
        $circle->note        = htmlspecialchars($this->request->getPost('note'));

        $update = $circlesModel->update($circle->id, $circle);
        if (! $update) {
            return nav()->toFailed("/circles/{$circle->id}");
        }

        return nav()->toSuccess("/circles/{$circle->id}");
    }

    /**
     * GET /circles/$circleId/delete
     */
    public function delete(int $circleId)
    {
        // Auth
        if (! havePermission('circle.manage')) {
            return accessDenied();
        }

        $circlesModel = model('CirclesModel');

        $circle = $circlesModel->withCount()->find($circleId);
        if ($circle === null) {
            show404();
        }

        if ($circle->supporters_count > 0) {
            return nav()->toCustom('(:back)', 'not-empty');
        }

        // Delete
        $delete = $circlesModel->delete($circle->id);
        if (! $delete) {
            return nav()->toFailed('/circles');
        }

        return nav()->toSuccess('/circles');
    }
}
