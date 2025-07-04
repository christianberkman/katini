<?php

/**
 * Katini
 * Katini Service Service
 */

namespace App\Services;

use CodeIgniter\I18n\Time;
use Exception;

class KatiniService
{
    protected array $settingsCache = [];

    // -------------------------------------------------------------------
    // Misc
    // -------------------------------------------------------------------
    public function __construct()
    {
        helper('setting');

        $this->populateSettingsCache();
    }

    private function populateSettingsCache()
    {
        if (! empty($this->settingsCache)) {
            return;
        }

        $settings = ['timeZones', 'timeFormats'];

        foreach ($settings as $setting) {
            $this->settingsCache[$setting] = setting("Katini.{$setting}");
        }
    }

    // -------------------------------------------------------------------
    // Time zones
    // -------------------------------------------------------------------

    /**
     * Return a formatted time/date string
     *
     * @param string $timeZone Katini.timeZones
     * @param string $format   Katini.timeFormats
     */
    public function getFormattedTime(string $timeZone, string $format): string
    {
        if (! array_key_exists($timeZone, $this->settingsCache['timeZones'])) {
            throw new Exception("Invalid Time Zone Alias: {$timeZone}");
        }
        if (! array_key_exists($format, $this->settingsCache['timeFormats'])) {
            throw new Exception("Invalid Time Format Alias: {$format}");
        }

        return ucfirst(
            (Time::now($this->settingsCache['timeZones'][$timeZone], 'nl_NL'))
                ->toLocalizedString($this->settingsCache['timeFormats'][$format]),
        );
    }

    /**
     * Return the time difference in hours. Compares 'home' time zone
     * to 'mission time zone. Negative integer means 'mission' time is
     * ahead, positive integer means 'mission' time is behind.
     */
    public function getTimeDiff(string $baseZone, string $compareZone): float|int
    {
        $homeTime    = Time::today($this->settingsCache['timeZones'][$baseZone]);
        $missionTime = Time::today($this->settingsCache['timeZones'][$compareZone]);

        $difference = $homeTime->difference($missionTime);

        $minutes = $difference->getMinutes();
        if (($minutes % 60) === 0) {
            return (int) $difference->getHours();
        }

        return (float) ($minutes / 60);
    }

    /**
     * Return a string comparing home time to mission time
     *
     * @return int
     */
    public function getTimeDiffStr(string $baseZone, string $compareZone): string
    {
        $hours    = $this->getTimeDiff($baseZone, $compareZone);
        $absHours = abs($hours);

        if ($hours === 0) {
            return 'geen tijdsverschil';
        }
        if ($hours < 0) {
            return "{$absHours} uur eerder";
        }
        if ($hours > 0) {
            return "{$absHours} uur later";
        }
    }

    public function getHomeLongDate()
    {
        $this->populateTimeSettings();

        return ucfirst(
            (Time::now($this->timeSettings['homeTimeZone'], 'nl_NL'))
                ->toLocalizedString($this->timeSettings['dateLongFormat']),
        );
    }

    public function getWorkShortDate()
    {
        $this->populateTimeSettings();

        return ucfirst(
            (Time::now($this->timeSettings['workTimeZone'], 'nl_NL'))
                ->toLocalizedString($this->timeSettings['dateShortFormat']),
        );
    }

    public function getWorkLongDate()
    {
        $this->populateTimeSettings();

        return ucfirst(
            (Time::now($this->timeSettings['workTimeZone'], 'nl_NL'))
                ->toLocalizedString($this->timeSettings['dateLongFormat']),
        );
    }

    // -------------------------------------------------------------------
    // Lists
    // -------------------------------------------------------------------

    /**
     * Return a sorted array of all lists with the list name
     * as array keys
     */
    public function getLists(): array
    {
        $lists     = [];
        $listNames = setting('Katini.optionLists');

        foreach ($listNames as $listName) {
            $lists[$listName] = $this->getList($listName);
        }

        asort($lists);

        return $lists;
    }

    /**
     * Return all lists and ordered by title OR
     * return a single list. Itmes will be sorted according
     * to $list['sort'] key.
     */
    public function getList(string $listName): array
    {
        $this->isList($listName);

        $list = setting("Katini.{$listName}");
        if ($list['sort']) {
            asort($list['items'], SORT_ASC);
        }

        return $list;
    }

    /**
     * Set items in the list
     */
    public function setListItems(string $listName, array $items): self
    {
        $list = $this->getList($listName);

        // Sanitize items
        foreach ($items as $key => &$value) {
            $value = htmlspecialchars($value);
            if (empty($value)) {
                unset($items[$key]);
            }
        }

        $list['items'] = $items;

        setting("Katini.{$listName}", $list);

        return $this;
    }

    /**
     * Set list sorting option
     */
    public function setListSort(string $listName, bool $sort): self
    {
        $list = $this->getList($listName);

        $list['sort'] = $sort;

        setting("Katini.{$listName}", $list);

        return $this;
    }

    public function revertList(string $listName): self
    {
        $this->isList($listName);

        setting()->forget("Katini.{$listName}");

        return $this;
    }

    /**
     * Return true if the list exits
     *
     * @param mixed $throwException
     */
    public function isList(string $listName, $throwException = true): bool
    {
        $lists = setting('Katini.optionLists');

        $isList = in_array($listName, $lists, true);

        if (! $isList && $throwException) {
            throw new Exception('[Katini Service] List does not exist');
        }

        return $isList;
    }

    // -------------------------------------------------------------------
    // Other
    // -------------------------------------------------------------------

    /**
     * Return user's name by ID
     */
    public function getUsernameById(int $userId): string
    {
        $users = auth()->getProvider();
        $user  = $users->findById($userId);
        if (! $user) {
            return 'onbekend';
        }

        return $user->getFullName();
    }
}
