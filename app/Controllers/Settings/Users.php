<?php

namespace App\Controllers\Settings;

use App\Controllers\BaseController;
use App\Entities\User;
use Throwable;

class Users extends BaseController
{
    protected $userModel;
    protected $users;
    protected $groups;
    protected $permissions;

    public function setup()
    {
        $this->user = auth()->user();

        nav()->addBreadcrumb('Instellngen', '/settings');
        nav()->addBreadcrumb('Gebruikers', '/settings/users');

        // Users
        $this->userModel = auth()->getProvider();
        $this->users     = $this->userModel->findAll();

        // Groups
        $this->groups = setting('AuthGroups.groups');

        // Permissions
        $this->permissions = setting('AuthGroups.permissions');
    }

    /**
     * GET /settings/users
     */
    public function index()
    {
        // Auth
        if (! havePermission('users.*')) {
            return accessDenied();
        }

        // View
        $data['users'] = $this->users;
        nav()->setPageTitle('Gebruikers');

        return view('settings/users/index', $data);
    }

    /**
     * GET /settings/users/new
     */
    public function new()
    {
        // Auth
        if (! havePermission('users.manage')) {
            return accessDenied();
        }

        // View
        nav()->setPageTitle('Nieuwe gebruiker');
        nav()->addBreadcrumb('Nieuwe gebruiker');

        return view('settings/users/new');
    }

    /**
     * POST /settings/users/new
     */
    public function insert()
    {
        // Auth
        if (! havePermission('users.manage')) {
            return accessDenied();
        }

        // New User
        $newUser = new User([
            'username'   => null,
            'email'      => $this->request->getPost('email'),
            'first_name' => $this->request->getPost('first_name'),
            'last_name'  => $this->request->getPost('last_name'),
            'password'   => $this->request->getPost('password'),
        ]);

        try {
            $this->userModel->save($newUser);
        } catch (Throwable $e) {
            return nav()->backFailed();
        }

        return nav()->toCustom('/settings/users', 'success');
    }

    /**
     * GET /settings/users/edit/$userId
     */
    public function edit(int $userId)
    {
        // Auth
        if (! havePermission('users.manage')) {
            return accessDenied();
        }

        // Selected user
        $data['user'] = $this->userModel->findById($userId);
        if ($data['user'] === null) {
            show404();
        }

        // View
        nav()->setPageTitle("Gebruiker: {$data['user']->email}");
        nav()->addBreadCrumb("Bewerken: {$data['user']->email}", '/settings/users/edit');

        return view('settings/users/edit', $data);
    }

    /**
     * POST /settings/users/edit/$userId
     */
    public function update(int $userId)
    {
        // Auth
        if (! havePermission('users.manage')) {
            return accessDenied();
        }

        try {
            // Selected user
            $user = $this->userModel->findById($userId);
            if ($user === null) {
                show404();
            }

            // Update fields
            $user->setEmail($this->request->getPost('email'));
            $user->first_name = $this->request->getPost('first_name');
            $user->last_name  = $this->request->getPost('last_name');

            if (! empty($this->request->getPost('password'))) {
                $user->setPassword($this->request->getPost('password'));
            }
            $this->userModel->save($user);
        } catch (Throwable $e) {
            d($e);

            return nav()->backFailed($e->getMessage());
        }

        return nav()->backSuccess();
    }

    /**
     * GET /settings/users/access
     */
    public function access()
    {
        // Auth
        if (! havePermission('users.permissions')) {
            return accessDenied();
        }

        // Selected user
        $data['user'] = null;
        $userId       = (int) $this->request->getGet('userId');
        if ($userId !== null && $userId !== 0) {
            $data['user'] = $this->userModel->findById($userId);
            if ($data['user'] === null) {
                show404();
            }
        }

        // View
        nav()->setPageTitle("Toegang: {$data['user']->email}");
        nav()->addBreadCrumb("Toegang: {$data['user']->email}");

        $data['users']       = $this->users;
        $data['groups']      = $this->groups;
        $data['permissions'] = $this->permissions;

        return view('settings/users/access', $data);
    }

    /**
     * POST /settings/users/access
     */
    public function updateAccess()
    {
        // Auth
        if (! havePermission('users.permissions')) {
            return accessDenied();
        }

        // User
        $userId = $this->request->getPost('userId');
        if ($userId === null) {
            show404();
        } else {
            $user = $this->userModel->findById($userId);
        }

        // Action
        switch ($this->request->getPost('formAction')) {
            case 'groups':
                return $this->updateGroups($user);
                break;

            case 'permissions':
                return $this->updatePermissions($user);
                break;

            default:
                show404();

                return;
                break;
        }
    }

    /**
     * Update groups
     *
     * @param mixed $user
     */
    private function updateGroups($user)
    {
        try {
            $userGroups = $this->request->getPost('groupIds') ?? [];

            // Prevent current user from removing itself from the owner group
            if (auth()->user()->inGroup('owner') && $user->id === auth()->user()->id && ! in_array('owner', $userGroups, true)) {
                return nav()->backFailed('Eigenaar kan zichzelf niet uit de groep verwijderen');
            }

            $user->syncGroups(...$userGroups);
        } catch (Throwable $e) {
            d($e);

            return;

            return nav()->backFailed();
        }

        return nav()->backSuccess();
    }

    /**
     * Update Permissions
     */
    public function updatePermissions($user)
    {
        try {
            $userPermissions = $this->request->getPost('permissionIds') ?? [];
            $user->syncPermissions(...$userPermissions);
        } catch (Throwable $e) {
            return nav()->backFailed();
        }

        return nav()->backSuccess();
    }

    /**
     * GET /settings/users/ban/$userId
     */
    public function ban(int $userId)
    {
        // Auth
        if (! havePermission('users.manage')) {
            return accessDenied();
        }

        $user = $this->userModel->findById($userId);
        if ($user === null) {
            show404();
        }

        // Prevent user from banning itself
        if ($user->id === auth()->user()->id) {
            return nav()->backFailed('Gebruiker kan zichzelf niet op non-actief zetten');
        }

        $user->ban();

        return nav()->backSuccess();
    }

    /**
     * GET /settings/users/unban/$userId
     */
    public function unban(int $userId)
    {
        // Auth
        if (! havePermission('users.manage')) {
            return accessDenied();
        }

        $user = $this->userModel->findById($userId);
        if ($user === null) {
            show404();
        }

        $user->unban();

        return nav()->backSuccess();
    }

    /**
     * GET /settings/users/delete/$userId
     */
    public function delete(int $userId)
    {
        // Auth
        if (! havePermission('users.manage')) {
            return accessDenied();
        }

        $user = $this->userModel->findById($userId);
        if ($user === null) {
            show404();
        }

        // Prevent user from deleting itself
        if ($user->id === auth()->user()->id) {
            return nav()->backFailed('Gebruiker kan zichzelf niet verwijderen');
        }

        try {
            $this->userModel->delete($user->id);
        } catch (Throwable $e) {
            return nav()->backFailed();
        }

        return nav()->toCustom('settings/users', 'success', "Gebruiker verwijderd: {$user->email}");
    }
}
