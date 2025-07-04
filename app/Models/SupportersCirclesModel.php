<?php

namespace App\Models;

use App\Entities\Supporter;
use CodeIgniter\Model;
use Throwable;

class SupportersCirclesModel extends Model
{
    protected $table                  = 'supporters_circles';
    protected $primaryKey             = 'rid';
    protected $useAutoIncrement       = true;
    protected $returnType             = 'object';
    protected $useSoftDeletes         = false;
    protected $protectFields          = true;
    protected $allowedFields          = ['supporter_id', 'circle_id'];
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;
    protected array $casts            = [];
    protected array $castHandlers     = [];

    // Dates
    protected $useTimestamps = false;

    // Validation
    protected $validationRules = [
        'supporter_id' => 'integer',
        'circle_id'    => 'required|integer',
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

    // Custom properties
    protected $circlesCache;

    /**
     * Return array of supporter entities
     */
    public function asSupporters(): array
    {
        $this->join('supporters', 'supporters_circles.supporter_id = supporters.id');
        $this->select('supporters.*');
        $data = $this->asArray()->findAll();

        $supporters = [];

        foreach ($data as $row) {
            $supporter = new Supporter();
            $supporter->fill($row);
            $supporters[] = $supporter;
        }

        return $supporters;
    }

    /**
     * Return array of circle entities
     */
    public function asCircles(): array
    {
        $this->join('circles', 'supporters_circles.circle_id = circles.id');
        $this->orderBy('circle_name');
        $this->select('circles.*');

        return $this->findAll();
    }

    /**
     * Synchronize supporter circles
     */
    public function sync(int $supporterId, array $circleIds): bool
    {
        try {
            $this->transStart();

            // Delete all entries
            $this->where('supporter_id', $supporterId)->delete();

            // Add new entries
            $data = [];

            foreach ($circleIds as $circleId) {
                $data[] = [
                    'supporter_id' => $supporterId,
                    'circle_id'    => $circleId,
                ];
            }

            $insert = $this->insertBatch($data);
            if (! $insert) {
                return false;
            }

            $this->transComplete();
        } catch (Throwable $e) {
            return false;
        }

        return true;
    }
}
