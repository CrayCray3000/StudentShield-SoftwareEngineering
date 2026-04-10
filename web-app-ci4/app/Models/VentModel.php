<?php

namespace App\Models;

use CodeIgniter\Model;

class VentModel extends Model
{
    protected $table      = 'journal_entries';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    
    // Using CodeIgniter to automatically handle timestamp
    protected $useTimestamps = true;
    protected $createdField  = 'timestamp';
    protected $updatedField  = '';
    
    // Feature: Soft Deletes
    protected $useSoftDeletes = true;
    protected $deletedField  = 'deleted_at';

    protected $allowedFields = ['student_id', 'raw_text', 'ml_sentiment_score', 'ml_risk_level', 'status', 'deleted_at'];
}
