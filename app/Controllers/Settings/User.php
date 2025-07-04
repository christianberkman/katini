<?php

namespace App\Controllers\Settings;

use App\Controllers\BaseController;

class User extends BaseController
{
    protected $user;

    public function setup()
    {
        $this->user = auth()->user();

        nav()->addBreadcrumb('Instellngen', '/settings');
        nav()->addBreadcrumb('Mijn voorkeuren', '/settings/user');
    }

    /**
     * GET /settings/user
     */
    public function preferences()
    {
        $preferences['userTimeZone'] = setting()->get('Katini.userTimeZone', userContext());
        $data['preferences']         = $preferences;

        $data['permissions'] = setting('AuthGroups.permissions');
        $groups              = setting('AuthGroups.groups');
        $this->userGroups    = $this->user->getGroups();
        $data['userGroups']  = array_intersect_key($groups, array_flip($this->userGroups));

        $data['user'] = $this->user;

        nav()->setPageTitle('Mijn voorkeuren');

        return view('settings/user', $data);
    }

    /**
     * POST /settings/user
     */
    public function updatePreferences()
    {
        try {
            $postData = $this->request->getPost();

            $this->user->first_name = $postData['first_name'];
            $this->user->last_name  = $postData['last_name'];

            $this->userModel = model('UserModel');
            $this->userModel->update($this->user->id, $this->user);

            // Timezone
            setting()->set('Katini.userTimeZone', $postData['userTimeZone'], userContext());
        } catch (Throwable $e) {
            // Failed
            return nav()->backFailed($e->getMessage());
        }

        // Success
        return nav()->backSuccess();
    }
}
