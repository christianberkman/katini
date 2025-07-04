<?php

namespace App\Controllers\Settings;

use App\Controllers\BaseController;
use Exception;

class OptionLists extends BaseController
{
    public function setup()
    {
        nav()->addBreadcrumb('Instellingen', '/settings');
        nav()->addBreadcrumb('Keuzelijsten', '/settings/optionlists');
    }

    /**
     * GET settings/optionLists
     */
    public function showLists()
    {
        // Auth
        if (! havePermission('settings.*')) {
            return accessDenied();
        }

        $lists = katini()->getLists();

        nav()->setPageTitle('Keuzelijsten');

        return view('settings/optionLists/index', ['lists' => $lists]);
    }

    /**
     * GET settings/optionLists/bewerk/$listName
     */
    public function editList(string $listName)
    {
        // Auth
        if (! havePermission('settings.*')) {
            return accessDenied();
        }

        $list = katini()->getList($listName);

        nav()->setPageTitle("Keuzelijst: {$list['title']}");

        nav()->addBreadcrumb($list['title']);

        return view('settings/optionLists/edit', ['listName' => $listName, 'list' => $list]);
    }

    /**
     * POST settings/optionLists/bewerk/$listName
     */
    public function updateList(string $listName)
    {
        // Auth
        if (! havePermission('settings.*')) {
            return accessDenied();
        }

        $listName = $this->request->getPost('name');

        try {
            katini()->setListItems($listName, $this->request->getPost('items'));
            katini()->setListSort($listName, (bool) $this->request->getPost('sort'));
        } catch (Exception $e) {
            return nav()->backFailed($e->getMessage());
        }

        return nav()->toSuccess('settings/optionlists');
    }

    /**
     * GET settings/optionLists/herstel/$listName
     * Revert list back to config file settings
     */
    public function revertList(string $listName)
    {
        // Auth
        if (! havePermission('settings.*')) {
            return accessDenied();
        }

        try {
            katini()->revertList($listName);
        } catch (Exception $e) {
            return nav()->backFailed($e->getMessage());
        }

        return nav()->backSuccess();
    }
}
