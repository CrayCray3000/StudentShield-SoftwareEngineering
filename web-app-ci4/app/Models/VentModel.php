<?php

namespace App\Models;

use CodeIgniter\Model;

class VentModel extends Model
{
    protected $table      = 'vent_logs';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    
    // Using CodeIgniter to automatically handle timestamp
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    
    // Feature: Soft Deletes
    protected $useSoftDeletes = true;
    protected $deletedField  = 'deleted_at';

    protected $allowedFields = ['user_id', 'raw_text', 'emotions', 'risk_percent', 'risk_level', 'status', 'deleted_at'];
}
