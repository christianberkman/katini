<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use CodeIgniter\I18n\Time;

class Supporter extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [
        'id'     => 'integer',
        'first_name'       => 'string',
        'infix'            => 'string',
        'last_name'        => 'string',
        'org_name'         => 'string',
        'title'            => 'string',
        'email'            => 'string',
        'phone'            => 'string',
        'address_street'   => 'string',
        'address_number'   => 'int',
        'address_addition' => 'string',
        'address_postcode' => 'string',
        'address_city'     => 'string',
        'address_nl'       => 'boolean',
        'date_birth'       => 'string',
        'iban'             => 'string',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
        'updated_by'       => 'integer',
    ];
    protected $circlesCache;
    protected $circleIdsCache;

    public function __construct()
    {
        parent::__construct();
        helper('url');
    }

    public function getId(): string
    {
        return $this->attributes['id'];
    }

    public function getCreatedAt(): Time
    {
        if ($this->attributes['created_at'] ?? null === null) {
            return new Time();
        }

        return $this->attributes['created_at'];
    }

    public function getStartYear(): ?int
    {
        $value = $this->attributes['start_year'] ?? null;

        return null === $value ? null : (int) $value;
    }

    public function getStartMonth(): ?int
    {
        $value = $this->attributes['start_month'] ?? null;

        return null === $value ? null : (int) $value;
    }

    /**
     * Return display name (full name)
     * Examples:
     *  Peter de Vries
     *  Dhr. Peter de Vries
     */
    public function compileDisplayName(?bool $includeTitle = false, ?bool $includeOrgName = true): string
    {
        // Return org name all first_name and last_name are empty
        if (! empty($this->org_name) && empty($this->first_name) && empty($this->last_name)) {
            return $this->org_name;
        }

        $displayName = implode(' ', [
            $this->first_name,
            $this->infix,
            $this->last_name,
        ]);

        $displayName = trim(str_replace('  ', ' ', $displayName));

        if (empty($displayName) || $displayName === $this->infix && empty($this->org_name)) {
            return '(naamloos)';
        }

        // Include title
        $title = $this->title ?? null;
        if ($includeTitle && ! empty($title)) {
            $displayName = "{$displayName}, {$title}";
        }

        // Include Org Name
        if ($includeOrgName && ! empty($this->org_name)) {
            return $displayName = "{$displayName} ({$this->org_name})";
        }

        return $displayName;
    }

    /**
     * Return sortable name
     * Example:
     *  Vries, Peter De
     *  Vries, Dhr. Peter de,
     */
    public function getSortableName(?bool $includeTitle = false, ?bool $includeOrgName = false): string
    {
        $firstName = $this->first_name;
        $infix     = $this->infix;
        $lastName  = $this->last_name;
        $title     = $this->title;
        $orgName   = $this->org_name;

        // Return org name if other names are empty
        if (empty($firstName) && empty($lastName) && ! empty($orgName)) {
            return $orgName;
        }

        if ($includeTitle && ! empty($title)) {
            $titleStr = " , {$title}";
        } else {
            $titleStr = null;
        }

        // Compile name
        if (! empty($lastName) && ! empty($firstName) && ! empty($infix)) {
            // Vries, Peter de (Dhr.)
            $sortableName = "{$lastName}, {$firstName} {$infix}{$titleStr}";
        } elseif (! empty($lastName) && ! empty($firstName) && empty($infix)) {
            // Vries, Peter (Dhr.)
            $sortableName = "{$lastName}, {$firstName}{$titleStr}";
        } elseif (! empty($lastName) && empty($firstName) && ! empty($infix)) {
            // Vries, de (Dhr.)
            $sortableName = "{$lastName}, {$infix}{$titleStr}";
        } elseif (! empty($firstName)) {
            // Peter (Dhr.)
            $sortableName = $firstName . $titleStr;
        } elseif (! empty($lastName)) {
            // Vries (Dhr.)
            $sortableName = $lastName . $titleStr;
        } else {
            $sortableName = '(naamloos)';
        }

        // Include Org Name
        if ($includeOrgName && ! empty($orgName)) {
            return "{$sortableName} ({$orgName})";
        }

        return $sortableName;
    }

    /**
     * Returns if the supporter has at least one name
     */
    public function hasName(): bool
    {
        return (! empty($this->attributes['first_name']) || ! empty($this->attributes['last_name'])) || ! empty($this->attributes['org_name']);
    }

    /**
     * Return formatted  address
     * Example
     *  Rijksweg 123 A, 123AA  Dorpsstad
     *
     * @param string $break Break between first and second part.
     *                      Single line: ', '
     *                      Multi line: '<br />', '\n', etc.
     */
    public function getAddress(?string $break = ', '): string
    {
        $line1 = "{$this->address_street} " . ($this->address_number ?? '');
        if (! empty($this->address_addition)) {
            $line1 .= ' ' . $this->address_addition;
        }

        $line2 = "{$this->address_postcode}  " . strtoupper($this->address_city); // double space between postcode and city as per specification of PostNL

        return ss($line1) . $break . ss($line2);
    }

    /**
     * Return a phone url (tel://+3112345678)
     * Assume +31 if no country code is given
     */
    public function getPhoneUrl()
    {
        if ($this->phone === null) {
            return null;
        }

        // First character
        $firstChar = substr($this->phone, 0, 1);

        switch ($firstChar) {
            case '+':
                return $this->phone;
                break;

            case '0':
                return '+31' . substr($this->phone, 1);
                break;
        }
    }

    public function getDateOfBirthString(): ?string
    {
        if ($this->attributes['date_birth'] === null) {
            return null;
        }

        return (new Time($this->attributes['date_birth']))->toLocalizedString(setting('Katini.timeFormats')['longDate']);
    }

    /**
     * Return supporter view url
     */
    public function getUrl()
    {
        return url_to('supporters-view', $this->attributes['id']);
    }

    /**
     * Magic setter
     * Apply filter functions (e.g. strip_tags) to defined fields
     *
     * @param mixed|null $value
     */
    public function __set(string $key, $value = null)
    {
        parent::__set($key, strip_tags($value));

        // Update display_name if any name field is set
        if (in_array($key, ['first_name', 'infix', 'last_name', 'org_name'], true)) {
            $this->attributes['display_name'] = $this->compileDisplayName(false, true);
        }
    }

    /**
     * Format first_name
     * Examples:
     *  'Jan marie' -> 'Jan Marie'
     *  'John f' -> 'John F.'
     *  'A F K' -> 'A. F. K.'
     *
     * @param mixed $value
     */
    public function setFirstName($value)
    {
        $string                         = ucwords($value, ' \t-');
        $pattern                        = '/\\b([A-Za-z])(?!\.)\\b/';
        $replace                        = '$1.';
        $this->attributes['first_name'] = preg_replace($pattern, $replace, $string);

        return $this;
    }

    /**
     * Cast address_number to integer
     *
     * @param mixed $value
     */
    public function setAddressNumber($value)
    {
        $int                                = (int) $value;
        $this->attributes['address_number'] = ($int === 0 ? null : $int);

        return $this;
    }

    /**
     * Only allow + and numerical characters in phone number
     */
    public function setPhone(string $value)
    {
        $pattern                   = '/[^+0-9]/';
        $this->attributes['phone'] = preg_replace($pattern, '', $value);

        return $this;
    }

    /**
     * Format postcode as 1234 AB
     */
    public function setAddressPostcode(string $value): self
    {
        $pattern                              = '/([0-9]{4}) ?([A-Z]{2})/';
        $this->attributes['address_postcode'] = preg_replace($pattern, '$1 $2', $value);

        return $this;
    }

    public function setDateBirth(string $value): self
    {
        $this->attributes['date_birth'] = (empty($value) ? null : $value);

        return $this;
    }

    /**
     * Get donations linked to supporter
     */
    public function getDonations(?int $limit = null): array
    {
        $donationModel = model('DonationModel');

        return $donationModel->where('id', $this->attributes['id'])->findAll($limit);
    }

    /**
     * Get circles linked to supporter
     */
    public function getCircles(): array
    {
        if ($this->circlesCache !== null) {
            return $this->circlesCache;
        }

        $scModel = model('SupportersCirclesModel');

        return $scModel->where('supporter_id', $this->id)->asCircles();
    }

    /**
     * Return array of circle ids
     */
    public function getCircleIds(): array
    {
        if ($this->circleIdsCache !== null) {
            return $this->circleIdsCache;
        }

        $scModel = model('SupportersCirclesModel');

        return $scModel->where('supporter_id', $this->id)->findColumn('circle_id') ?? [];
    }
}
