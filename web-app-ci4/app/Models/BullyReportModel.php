<?php

namespace App\Models;

use CodeIgniter\Model;

class BullyReportModel extends Model
{
    protected $table            = 'bully_reports';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'reporter_student_id',
        'reporter_type',
        'victim_student_id',
        'bully_student_id',
        'incident_details',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
