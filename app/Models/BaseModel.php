<?php

namespace App\Models;

use CodeIgniter\Model;

class BaseModel extends Model
{
    protected $updatedByField = 'updated_by';

    /**
     * Set the updated_by field
     *
     * @param mixed $data
     */
    public function setUpdatedBy($data): array
    {
        $data['data'][$this->updatedByField] = auth()->user()->id ?? null;

        return $data;
    }
}
