<?php
require 'public/index.php';
$db = \Config\Database::connect();
print_r($db->table('virtual_meetings')->get()->getResultArray());
print_r($db->table('virtual_meetings vm')->select('vm.id, vm.student_id, vm.created_at as requested_at, u.name as student_name, s.student_id as student_number, s.section')
            ->join('users u', 'u.id = vm.student_id', 'left')
            ->join('students s', 's.user_id = vm.student_id', 'left')
            ->where('vm.status', 'requested')
            ->orderBy('vm.created_at', 'DESC')
            ->get()->getResultArray());
