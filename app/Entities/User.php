<?php

namespace App\Entities;

use CodeIgniter\Shield\Entities\User as ShieldUser;

class User extends ShieldUser
{
    public function setFirstName($value)
    {
        $this->attributes['first_name'] = htmlspecialchars($value);

        return $this;
    }

    public function setLastName($value)
    {
        $this->attributes['last_name'] = htmlspecialchars($value);

        return $this;
    }

    public function getFullName(): string
    {
        return "{$this->attributes['first_name']} {$this->attributes['last_name']}";
    }

    public function getTimeZone(): string
    {
        return setting()->get('Katini.userTimeZone', "user:{$this->attributes['id']}");
    }

    public function can(string ...$permissions): bool
    {
        // Check scope.action style permissions
        $parentCheck = parent::can(...$permissions);
        if ($parentCheck === true) {
            return true;
        }

        // Deep wildcard search (scope.action.more.more...)
        $matrix = setting('AuthGroups.matrix');

        foreach ($permissions as $permission) {
            // Skip if permission only has scope.action
            if (substr_count($permission, '.') === 1) {
                continue;
            }

            $permission = strtolower($permission);

            // Walk down permission tree
            foreach ($this->groupCache as $group) {
                $walker = $permission;

                while (substr_count($walker, '.') >= 1) {
                    $check = $walker . '.*';

                    if (isset($matrix[$group]) && in_array($check, $matrix[$group], true)) {
                        return true;
                    }

                    // Walk down one more step
                    $walker = substr($walker, 0, strrpos($walker, '.'));
                }
            }
        }

        return false;
    }
}
