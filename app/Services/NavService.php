<?php

/**
 * Katini
 * Navigation Service
 */

namespace App\Services;

use CodeIgniter\HTTP\RedirectResponse;
use Exception;

class NavService
{
    protected $config;
    protected array $pageTitle = [];
    protected RedirectResponse $redirectObj;
    public $resultKey        = 'actionResult';
    public $detailsKey       = 'actionDetails';
    public $successValue     = 'success';
    public $failedValue      = 'failed';
    public $alertView        = 'alerts';
    protected $defaultAlerts = [
        'success' => ['Success', 'De actie is succesvol uitgevoerd.', 'alert-success'],
        'failed'  => ['Fout', 'De actie kon niet uitgevoerd worden.', 'alert-danger'],
    ];
    protected $breadcrumbs = [];

    public function __construct()
    {
        $this->config      = config('NavService');
        $this->redirectObj = redirect();
    }

    // -------------------------------------------------------------------
    // Title
    // -------------------------------------------------------------------

    /**
     * Sets the title for the current page
     *
     * @param string $value page title segment(s)
     */
    public function setPageTitle(array|string $value): self
    {
        if (! is_array($value)) {
            $value = [(string) $value];
        }

        $this->pageTitle = $value;

        return $this;
    }

    /**
     * Returns the compiled and HTML safe page title
     */
    public function getPageTitle(): string
    {
        return strip_tags(implode($this->config->titleSeperator, $this->pageTitle));
    }

    /**
     * Returns the compiled and HTML safe site title
     */
    public function getSiteTitle(): string
    {
        if (count($this->pageTitle) === 0) {
            return htmlspecialchars($this->config->baseTitle);
        }

        return htmlspecialchars($this->getPageTitle() . $this->config->titleSeperator . $this->config->baseTitle);
    }

    // -------------------------------------------------------------------
    // Redirects and Results
    // -------------------------------------------------------------------

    /**
     * Return to specified uri with success result
     */
    public function toSuccess(string $uri, ?string $details = null): RedirectResponse
    {
        return $this->redirectObj
            ->to($uri)
            ->with($this->resultKey, $this->successValue)
            ->with($this->detailsKey, $details);
    }

    /**
     * Return to specified uri with failed result
     */
    public function toFailed(string $uri, ?string $details = null): RedirectResponse
    {
        return $this->redirectObj
            ->to($uri)
            ->withInput()
            ->with($this->resultKey, $this->failedValue)
            ->with($this->detailsKey, $details);
    }

    /**
     * Return to specified uri with custom result
     */
    public function toCustom(string $uri, string $result, ?string $details = null): RedirectResponse
    {
        $redirectObj = $this->redirectObj
            ->withInput()
            ->with($this->resultKey, $result)
            ->with($this->detailsKey, $details);

        if ($uri === '(:back)') {
            return $redirectObj->back();
        }

        return $redirectObj->to($uri);
    }

    /**
     * Redirect back with success result
     */
    public function backSuccess(?string $details = null): RedirectResponse
    {
        return $this->redirectObj
            ->back()
            ->with($this->resultKey, $this->successValue)
            ->with($this->detailsKey, $details);
    }

    /**
     * Redirect back with failed result
     */
    public function backFailed(?string $details = null): RedirectResponse
    {
        return $this->redirectObj
            ->back()
            ->withInput()
            ->with($this->resultKey, $this->failedValue)
            ->with($this->detailsKey, $details);
    }

    /**
     * Return the result value
     */
    public function getResult(): ?string
    {
        return session()->get($this->resultKey);
    }

    // -------------------------------------------------------------------
    // Alerts
    // -------------------------------------------------------------------

    public function alertView(?array $customAlerts = []): ?string
    {
        // Return null if no result is set
        if (! $result = session()->get($this->resultKey)) {
            return null;
        }

        // Create alerts array
        $defaultAlerts = [
            $this->successValue => $this->defaultAlerts['success'],
            $this->failedValue  => $this->defaultAlerts['failed'],
        ];

        $alerts = array_merge($defaultAlerts, $customAlerts);

        // Select alert
        if (! array_key_exists($result, $alerts)) {
            throw new Exception("[NavService] Result '{$result}' is not defined");
        }
        $alert   = $alerts[$result];
        $details = session()->get($this->detailsKey);

        // Return view
        return view($this->alertView, ['alert' => $alert, 'details' => $details]);
    }

    // -------------------------------------------------------------------
    // Breadcrumbs
    // -------------------------------------------------------------------

    /**
     * Add a breadcrumb
     *
     * @param mixed $prepUrl
     */
    public function addBreadcrumb(string $title, ?string $url = null, $prepUrl = true): self
    {
        if ($prepUrl) {
            $url = ($url !== null ? site_url($url) : null);
        }

        $this->breadcrumbs[] = [
            'title' => $title,
            'url'   => $url,
        ];

        return $this;
    }

    /**
     * Return array with breadcrumbs
     */
    public function getBreadcrumbs(): array
    {
        return $this->breadcrumbs;
    }
}
