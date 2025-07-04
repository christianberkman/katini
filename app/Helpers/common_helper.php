<?php

use APp\Services\KatiniService;
use App\Services\NavService;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\I18n\Time;

/**
 * Services
 */
if (! function_exists('katini')) {
    function katini(): KatiniService
    {
        return service('katini');
    }
}

if (! function_exists('nav')) {
    function nav(): NavService
    {
        return service('nav');
    }
}

/**
 * =======================================================================
 * Convenience functions
 * =======================================================================
 */

/**
 * Return the user context for use in setting()
 */
if (! function_exists('userContext')) {
    function userContext(): string
    {
        return 'user:' . auth()->user()->id;
    }
}

/**
 * Make a HTML Safe String (alias for htmlspecialchars
 */
if (! function_exists('ss')) {
    function ss(?string $string): ?string
    {
        return htmlspecialchars($string);
    }
}

/**
 * Get supporter entity by id
 */
if (! function_exists('supporter')) {
    function supporter(int|string $supporterId, $withDeleted = false)
    {
        if (empty($supporterId)) {
            return null;
        }

        if ($withDeleted) {
            return model('SupporterModel')->withDeleted()->find((int) $supporterId);
        }

        return model('SupporterModel')->find($supporterId);
    }
}

/**
 * Get donation entity by id
 */
if (! function_exists('donation')) {
    function donation(int|string $donationId, $withDeleted = false)
    {
        if ($withDeleted) {
            return model('DonationModel')->withDeleted()->find((int) $donationId);
        }

        return model('DonationModel')->find($donationId);
    }
}

if (! function_exists('circles')) {
    /**
     * Return an array of circles
     */
    function circles(): array
    {
        return model('CirclesModel')->findAll() ?? [];
    }
}

/**
 * =======================================================================
 * Formatters
 * =======================================================================
 */
if (! function_exists('formatAmount')) {
    function formatAmount($amount)
    {
        return '€' . number_format($amount, 2, ',', '.');
    }
}

if (! function_exists('formatTime')) {
    function formatTime(?Time $timeObj, ?string $formatAlias = null): ?string
    {
        if ($timeObj === null) {
            return null;
        }

        $format = setting('Katini.timeFormats')[$formatAlias] ?? null;

        return $timeObj->toLocalizedString($format);
    }
}

if (! function_exists('ymdToday')) {
    function ymdToday(): string
    {
        return Time::today()->toDateString();
    }
}

/**
 * =======================================================================
 * Errors and Exceptions
 * =======================================================================
 */
if (! function_exists('show404')) {
    function show404()
    {
        throw PageNotFoundException::forPageNotFound();
    }
}

/**
 * =======================================================================
 * Auth
 * =======================================================================
 */
if (! function_exists('accessDenied')) {
    function accessDenied()
    {
        return view('access_denied');
    }
}

if (! function_exists('havePermission')) {
    function havePermission(string ...$permissions): bool
    {
        foreach ($permissions as $permission) {
            if (! auth()->user()->can($permission)) {
                session()->set('missing_permission', $permission);

                return false;
            }
        }

        return true;
    }
}
