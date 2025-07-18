<?php

declare(strict_types=1);

/**
 * This file is part of CodeIgniter Shield.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    /**
     * --------------------------------------------------------------------
     * Default Group
     * --------------------------------------------------------------------
     * The group that a newly registered user is added to.
     */
    public string $defaultGroup = '';

    /**
     * --------------------------------------------------------------------
     * Groups
     * --------------------------------------------------------------------
     * An associative array of the available groups in the system, where the keys
     * are the group names and the values are arrays of the group info.
     *
     * Whatever value you assign as the key will be used to refer to the group
     * when using functions such as:
     *      $user->addGroup('superadmin');
     *
     * @var array<string, array<string, string>>
     *
     * @see https://codeigniter4.github.io/shield/quick_start_guide/using_authorization/#change-available-groups for more info
     */
    public array $groups = [
        'manager' => [
            'title'       => 'Beheerder',
            'description' => 'Beheerder van de Katini installatie',
        ],
        'member' => [
            'title'       => 'Teamlid',
            'description' => 'Lid van het thuisfrontteam',
        ],
        'guest' => [
            'title'       => 'Gast',
            'description' => 'Gast (alleen lezen)',
        ],
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions
     * --------------------------------------------------------------------
     * The available permissions in the system.
     *
     * If a permission is not listed here it cannot be used.
     */
    public array $permissions = [
        'supporter.read'  => 'Supporters (leesrechten)',
        'supporter.write' => 'Supporters (schrijfrechten)',

        'donation.read'  => 'Donaties (leesrechten)',
        'donation.write' => 'Donaties (schrijfrechten)',

        'settings.general'    => 'Algemene instellingen',
        'settings.optionList' => 'Keuzelijsten aanpassen',
        'users.manage'        => 'Gebruikers beheren',
        'users.permissions'   => 'Toegang van gebruikers beheen',

        'circle.manage' => 'Kringen beheren',
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions Matrix
     * --------------------------------------------------------------------
     * Maps permissions to groups.
     *
     * This defines group-level permissions.
     */
    public array $matrix = [
        'manager' => [
            'supporter.*', 'donation.*', 'settings.*', 'circle.*', 'users.*',
        ],
        'member' => [
            'supporter.*', 'donation.*', 'circle.*',
        ],
        'guest' => [
            'supporter.read', 'donation.read',
        ],
    ];
}
