<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use CodeIgniter\I18n\Time;
use Exception;

class Donation extends Entity
{
    protected $dates = [
        'donation_date',
        'created_at',
        'updated_at',
        'deleted_at',
        'next_date',
    ];
    protected $casts = [
        'interval' => 'int',
    ];
    protected ?Supporter $supporter = null;

    /**
     * ==========================
     * Getter functions
     * ==========================
     */
    public function getId(): int
    {
        return $this->attributes['id'];
    }

    public function getCreatedAt()
    {
        if (! isset($this->attributes['created_at'])) {
            return new Time();
        }
        if ($this->attributes['created_at'] === null) {
            return new Time();
        }

        return $this->attributes['created_at'];
    }

    public function getSupporter(): ?Supporter
    {
        if ($this->supporter !== null) {
            return $this->supporter;
        }

        if ($this->attributes['supporter_id'] !== null) {
            $this->supporter = supporter($this->attributes['supporter_id']);
        }

        return $this->supporter;
    }

    /**
     * Return formatted donation title
     */
    public function getTitle(): string
    {
        if ($this->is_recurring) {
            if (! empty($this->attributes['supporter_id'])) {
                return $this->getIntervalPrefix() . ' van ' . $this->getSupporter()->compileDisplayName() . ' van ' . $this->getFormattedAmount();
            }

            return $this->getIntervalPrefix() . ' van ' . $this->getFormattedAmount();
        }
        if (! empty($this->attributes['supporter_id'])) {
            return 'Donatie van ' . $this->getSupporter()->compileDisplayName() . ' van ' . $this->getFormattedAmount();
        }

        return 'Donatie van ' . $this->getFormattedAmount();
    }

    /**
     * Return formatted amount
     * e.g. €12,50
     */
    public function getFormattedAmount(): string
    {
        return formatAmount($this->amount);
    }

    /**
     * Return donation view url
     */
    public function getUrl(): string
    {
        return url_to('donations-view', $this->attributes['id']);
    }

    /**
     * ===================================
     * Set functions
     * ===================================
     *
     * @param mixed $value
     */
    public function setIsRecurring($value)
    {
        $this->attributes['is_recurring'] = (int) $value;
    }

    /**
     * Magic Setter
     *
     * @param mixed|null $value
     */
    public function _set(string $key, $value = null)
    {
        return parent::__set($key, strip_tags($value));
    }

    /**
     * ===================================
     * Recurring donations
     * ===================================
     */
    public function getIntervalPrefix(bool $ucfirst = true): ?string
    {
        if ($this->interval < 1) {
            return null;
        }
        if ($this->interval === 1) {
            $prefix = 'maandelijkse donatie';
        } elseif ($this->interval === 3) {
            $prefix = 'donatie per kwartaal';
        } elseif ($this->interval === 6) {
            $prefix = 'halfjaarlijkse donatie';
        } elseif ($this->interval === 12) {
            $prefix = 'jaarljkse donatie';
        } else {
            $prefix = "donatie eens per {$this->interval} maanden";
        }

        if ($ucfirst) {
            return ucfirst($prefix);
        }

        return $prefix;
    }

    public function getFrequency(bool $ucfirst = true): ?string
    {
        if ($this->interval < 1) {
            return null;
        }

        if ($this->interval === 1) {
            $frequency = 'maandelijks';
        } elseif ($this->interval === 3) {
            $frequency = 'elk kwartaal';
        } elseif ($this->interval === 6) {
            $frequency = 'halfjaarlijks';
        } elseif ($this->interval === 12) {
            $frequency = 'jaarlijks';
        } else {
            $frequency = "elke {$this->interval} maanden";
        }

        if ($ucfirst) {
            return ucfirst($frequency);
        }

        return $frequency;
    }

    /**
     * Update recurring
     */
    public function updateRecurring()
    {
        if ($this->attributes['is_recurring'] === 1) {
            if ($this->interval < 1) {
                throw new Exception('Recurring donation with interval less than 1');
            }

            // Set other fields to correct value
            // line below to be replaced with $this->created_at->addCalendarMonths($this->interval) in CodeIgniter 4.7
            $this->attributes['next_date']    = $this->calculateNextDate();
            $this->attributes['has_recurred'] = 0;
        } else {
            // Clear other fields
            $this->attributes['interval']     = null;
            $this->attributes['next_date']    = null;
            $this->attributes['has_recurred'] = 0;
        }
    }

    public function calculateNextDate()
    {
        $time   = $this->created_at;
        $months = $this->interval;

        $year  = (int) $time->getYear();
        $month = (int) $time->getMonth();
        $day   = (int) $time->getDay();

        // Adjust total months since year 0
        $totalMonths = ($year * 12 + $month - 1) + $months;

        // Recalculate year and month
        $newYear  = intdiv($totalMonths, 12);
        $newMonth = $totalMonths % 12 + 1;

        // Get last day of new month
        $lastDayOfMonth = cal_days_in_month(CAL_GREGORIAN, $newMonth, $newYear);
        $correctedDay   = min($day, $lastDayOfMonth);

        return new Time("{$newYear}-{$newMonth}-{$correctedDay}");
    }
}
