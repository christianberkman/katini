<?php

namespace App\Models;

use App\Entities\Supporter;

class SupporterModel extends BaseModel
{
    protected $table            = 'supporters';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Supporter::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'first_name',
        'infix',
        'last_name',
        'org_name',
        'display_name',
        'title',
        'email',
        'phone',
        'address_street',
        'address_number',
        'address_addition',
        'address_postcode',
        'address_city',
        'date_birth',
        'iban',
        'note',
        'created_at',
        'updated_by',
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
        'phone'            => 'permit_empty',
        'email'            => 'permit_empty|valid_email',
        'address_number'   => 'permit_empty|integer',
        'address_postcode' => 'permit_empty|regex_match[/^(?:[1-9]\d{3} ?(?:[A-EGHJ-NPRTVWXZ][A-EGHJ-NPRSTVWXZ]|S[BCEGHJ-NPRTVWXZ]))$/]|permit_empty',
        'date_birth'       => 'permit_empty|valid_date',
        'iban'             => 'permit_empty|string',
        'updated_by'       => 'integer|permit_empty',
    ];
    protected $validationMessages = [
        'address_postcode' => [
            'regex_match' => 'Ongeldige Nederlandse postcode',
        ],
    ];
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
