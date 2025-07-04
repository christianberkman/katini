<?php

declare(strict_types=1);

namespace App\Controllers\Shield;

use App\Entities\User;
use CodeIgniter\Shield\Controllers\RegisterController as ShieldController;

class RegisterController extends ShieldController
{
    /**
     * Returns the Entity class that should be used
     */
    protected function getUserEntity(): User
    {
        return new User();
    }
}
