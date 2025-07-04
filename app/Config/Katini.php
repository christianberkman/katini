<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Katini extends BaseConfig
{
    /**
     * App Name
     * Default: 'Katini'
     */
    public string $appName = 'Katini';

    /**
     * Team name
     * example: "TFT Familie Berkman"
     */
    public string $teamName = 'Team';

    /**
     * Timezones
     * home: Home Country
     * work: Work Country
     */
    public array $timeZones = [
        'home'    => 'Europe/Amsterdam',
        'mission' => 'Africa/Kampala',
    ];

    /**
     * Time formats
     * See: https://unicode-org.github.io/icu-docs/apidoc/released/icu4c/classSimpleDateFormat.html#details
     */
    public array $timeFormats = [
        'time'        => 'HH:mm',
        'weekdayTime' => 'E HH:mm',
        'longDate'    => 'd MMMM YYYY',
        'mediumDate'  => 'EEEE d MMMM',
        'shortDate'   => 'd MMMM',
        'monthYear'   => 'MMMM YYYY',
    ];

    public string $userTimeZone = 'home';

    /**
     * Option Lists (Keuzelijsten)
     * Option Lists store default options for fields. Every list must
     * have the following keys:
     *  title (for display)
     *  sort NULL or true, SORT_DESC, etc.
     *  items (array of strings)
     */
    public array $optionLists = ['titles', 'paymentMethods', 'countries', 'designations'];

    public array $titles = [
        'title' => 'Aanhef',
        'sort'  => true,
        'items' => [
            'Dhr.',
            'Mevr.',
            'Dhr./Mevr.',
            'Fam.',
        ],
    ];
    public array $paymentMethods = [
        'title' => 'Betaalmethodes',
        'sort'  => false,
        'items' => [
            'Online',
            'Machtiging',
            'Zelf overschrijven',
            'Contant',
        ],
    ];
    public array $countries = [
        'title' => 'Landen',
        'sort'  => false,
        'items' => [
            'Nederland',
            'België',
            'Duitsland',
        ],
    ];
    public array $designations = [
        'title' => 'Doelen',
        'sort'  => true,
        'items' => [
            'Algemeen',
            'Levensonderhoud',
            'Project',
        ],
    ];
}
