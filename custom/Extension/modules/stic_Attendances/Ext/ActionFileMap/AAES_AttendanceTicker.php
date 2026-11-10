<?php
// STIC-Custom AAES - 20261109 - Attendance ticker action registration (IMPL-AAES-001, Fase B)
// Maps the custom stic_Attendances action 'AAESAttendanceTicker' to the ticker
// view handler. Requested as: index.php?module=stic_Attendances&action=AAESAttendanceTicker
// (key must be lowercase: the core applies strtolower() when looking up).
// END STIC-Custom AAES

$action_file_map['aaesattendanceticker'] = 'custom/include/AAES/AttendanceTicker/AAES_AttendanceTickerView.php';
