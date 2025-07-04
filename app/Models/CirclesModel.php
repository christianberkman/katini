<?php

namespace App\Models;

use CodeIgniter\Model;

class CirclesModel extends Model
{
    protected $table                  = 'circles';
    protected $primaryKey             = 'id';
    protected $useAutoIncrement       = true;
    protected $returnType             = 'object';
    protected $useSoftDeletes         = false;
    protected $protectFields          = true;
    protected $allowedFields          = ['circle_name', 'note'];
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;
    protected array $casts            = [];
    protected array $castHandlers     = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'circle_name' => 'required|string|is_unique[circles.circle_name]',
        'note'        => 'permit_empty',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function initialize()
    {
        $this->orderBy('circle_name');
    }

    public function withCount()
    {
        $this->join('supporters_circles', 'supporters_circles.circle_id = circles.id', 'LEFT');
        $this->select('circles.*');
        $this->select('COUNT(supporter_id) as `supporters_count`');
        $this->groupBy('circles.id');

        return $this;
    }
}
