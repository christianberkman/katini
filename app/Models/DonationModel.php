<?php

namespace App\Models;

use App\Entities\Donation;

class DonationModel extends BaseModel
{
    protected $table            = 'donations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Donation::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'supporter_id',
        'amount',
        'method',
        'donation_date',
        'note',
        'created_at',
        'is_recurring',
        'interval',
        'next_date',
        'has_recurred',
    ];
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;
    protected array $casts            = [];
    protected array $castHandlers     = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'supporter_id' => 'permit_empty|integer',
        'amount'       => 'required|decimal',
        'method'       => 'permit_empty|string',
        'note'         => 'permit_empty|string',
        // 'is_recurring' => 'integer',
        'interval'     => 'permit_empty|integer',
        'next_date'    => 'permit_empty|valid_date',
        'has_rucurred' => 'permit_empty|boolean',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setUpdatedBy'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['setUpdatedBy'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = ['setUpdatedBy'];
    protected $afterDelete    = [];
}
