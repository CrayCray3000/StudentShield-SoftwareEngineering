<?php

namespace App\Models;

use CodeIgniter\Model;

class SMSLogModel extends Model
{
    protected $table      = 'sms_logs';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';

    // Using CodeIgniter to automatically handle timestamp
    protected $useTimestamps = true;
    protected $createdField  = 'timestamp';
    protected $updatedField  = '';

    protected $allowedFields = ['student_id', 'parent_number', 'alert_type', 'message_content', 'sent_status'];
}
