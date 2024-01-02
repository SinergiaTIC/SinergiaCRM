<?php

/**
 * It runs the functionality for aggregated services payments. It will group the corresponding attendances into their matching
 * payments, calculating the total amount to be charged.
 *
 * Considering payments in the current month and attendances within the periodicity of the related payment commitments, it does:
 * 1. Search for warning attendances: they should be aggregated in a payment but they can't due to lack of status and payment_exception values.
 * 2. Search for includable attendances/payments: unpaid aggregated services payments and attendances with status "Yes" or "Partially" or with payment_exception "Include".
 * 3. Remove the previously related attendances that those payments may have
 * 4. Relate all the includable attendances to the proper payments
 * 5. Update the payment amount as the sum of the related attendances amounts.
 * 6. For those payments that are "complete" (no warning attendances), the field aggregated_services_complete is checked.
 * 7. Search for warning payments: payments that should be included but don't have any includable attendance.
 * 8. Send all the data to the custom aggregated view in order to be display a summary and a warnings table.
 *
 * STIC#498
 */

global $db, $current_user;
require_once 'SticInclude/Utils.php';

// Select all the payments that should be processed in the current month.
// Those not processed for any reason will be showed as warning payments.
$includedPaymentsQuery = "SELECT
    sp.id as 'payment_id',
    sp.name as 'payment_name',
    u.last_name as 'assigned_user_last_name',
    u.first_name as 'assigned_user_first_name',
    u.user_name as 'assigned_user_user_name',
    u.id as 'assigned_user_id'
FROM
    stic_payments sp
JOIN users u
ON
    sp.assigned_user_id = u.id
WHERE
    sp.deleted = 0
    AND u.deleted = 0
    AND MONTH(sp.payment_date) = MONTH(NOW())
    AND sp.payment_type = 'aggregated_services'
    AND sp.status != 'paid'
    ORDER BY sp.name ASC";

$res = $db->query($includedPaymentsQuery);

$includedPayments = 0;

// Prepare includable payments
while ($row = $db->fetchByAssoc($res)) {
    if (!$paymentId = $row['payment_id']) {
        continue;
    }
    $paymentBean = BeanFactory::getBean('stic_Payments', $paymentId);

    // Remove previous relationships between payments and attendances
    $linkedAttendances = $paymentBean->get_linked_beans('stic_payments_stic_attendances');
    foreach ($linkedAttendances as $linkedAttendance) {
        $paymentBean->stic_payments_stic_attendances->delete($paymentBean->id, $linkedAttendance->id);
    }
    // Reset aggregated_services_complete and amount fields
    if ($paymentBean->aggregated_services_complete != false || $paymentBean->amount != 0) {
        $paymentBean->aggregated_services_complete = false;
        $paymentBean->amount = 0;
        // Save the payment
        $paymentBean->save();
    }

    $includedPaymentsData[$paymentId] = $row;
    $includedPayments++;
}

// Select attendances that should be included in current payments
// but they can't due to lack of status and payment_exception values
$warningAttendanceQuery = "SELECT
    sp.id as 'payment_id',
    sa.name as 'attendance_name',
    sa.id as 'attendance_id',
    sa.assigned_user_id as 'attendance_assigned_user_id',
    u.last_name as 'assigned_user_last_name',
    u.first_name as 'assigned_user_first_name',
    u.user_name as 'assigned_user_user_name',
    u.id as 'assigned_user_id'
FROM
    stic_payments sp
JOIN stic_payments_stic_payment_commitments_c spspcc
ON
    sp.id = spspcc.stic_payments_stic_payment_commitmentsstic_payments_idb
JOIN stic_payment_commitments spc
ON
    spspcc.stic_paymebfe2itments_ida = spc.id
JOIN stic_payment_commitments_stic_registrations_c spcsrc
ON
    spc.id = spcsrc.stic_payme96d2itments_ida
JOIN stic_registrations sr
ON
    spcsrc.stic_paymee0afrations_idb = sr.id
JOIN stic_attendances_stic_registrations_c sasrc
ON
    sr.id = sasrc.stic_attendances_stic_registrationsstic_registrations_ida
JOIN stic_attendances sa
ON
    sasrc.stic_attendances_stic_registrationsstic_attendances_idb = sa.id
JOIN users u
ON
    sa.assigned_user_id = u.id
WHERE
    spc.deleted = 0
    AND sasrc.deleted = 0
    AND sr.deleted = 0
    AND spcsrc.deleted = 0
    AND spspcc.deleted = 0
    AND sp.deleted = 0
    AND sa.deleted = 0
    AND MONTH(sp.payment_date) = MONTH(NOW())
    AND sa.start_date < DATE_FORMAT(curdate(),'%Y-%m-01')
    AND CASE
            WHEN spc.periodicity = 'monthly' THEN sa.start_date >= subdate(DATE_FORMAT(curdate(),'%Y-%m-01'), INTERVAL 1 MONTH)
            WHEN spc.periodicity = 'bimonthly' THEN sa.start_date >= subdate(DATE_FORMAT(curdate(),'%Y-%m-01'), INTERVAL 2 MONTH)
            WHEN spc.periodicity = 'quarterly' THEN sa.start_date >= subdate(DATE_FORMAT(curdate(),'%Y-%m-01'), INTERVAL 3 MONTH)
            WHEN spc.periodicity = 'four_monthly' THEN sa.start_date >= subdate(DATE_FORMAT(curdate(),'%Y-%m-01'), INTERVAL 4 MONTH)
            WHEN spc.periodicity = 'half_yearly' THEN sa.start_date >= subdate(DATE_FORMAT(curdate(),'%Y-%m-01'), INTERVAL 6 MONTH)
            WHEN spc.periodicity = 'yearly' THEN sa.start_date >= subdate(DATE_FORMAT(curdate(),'%Y-%m-01'), INTERVAL 12 MONTH)
        END
    AND sp.payment_type = 'aggregated_services'
    AND sp.status != 'paid'
    AND (sa.payment_exception IS NULL OR sa.payment_exception = '')
    AND (sa.status IS NULL OR sa.status = '')
    AND sa.id NOT IN (
        SELECT
            spsac.stic_payments_stic_attendancesstic_attendances_idb
        FROM
            (
            SELECT
                id
            FROM
                stic_payments
            WHERE
                deleted = 0
                AND status = 'paid') AS spx
        JOIN stic_payments_stic_attendances_c spsac
            ON
            spx.id = spsac.stic_payments_stic_attendancesstic_payments_ida
        WHERE
            spsac.deleted = 0)
        ORDER BY sa.name ASC";

$res = $db->query($warningAttendanceQuery);

$warningAttendancesData = array();
$uncompletePaymentsId = array();
$warningAttendances = 0;
$uncompletePayments = 0;

// Build an array with warning attendances data (including assigned users for later notification)
while ($row = $db->fetchByAssoc($res)) {
    if (!$attendanceId = $row['attendance_id']) {
        continue;
    }

    $warningAttendancesData[$warningAttendances]['name'] = SticUtils::createLinkToDetailView('stic_Attendances', $row['attendance_id'], $row['attendance_name']);
    $warningAttendancesData[$warningAttendances]['attendance_id'] = $attendanceId;
    $warningAttendancesData[$warningAttendances]['assigned_user_id'] = $row['assigned_user_id'];
    $warningAttendancesData[$warningAttendances]['assigned_user'] = SticUtils::createLinkToDetailView('Users', $row['assigned_user_id'], $row['assigned_user_first_name'] . ' ' . $row['assigned_user_last_name'] . ' (' . $row['assigned_user_user_name'] . ')');
    $warningAttendances++;

    // Build an array of uncomplete payments (because of warning attendances)
    if (!in_array($row['payment_id'], $uncompletePaymentsId)) {
        $uncompletePaymentsId[] = $row['payment_id'];
        $uncompletePayments++;
    }
}

// Calculate payments amount
$aggregatedQuery = "SELECT
    sp.id as 'payment_id',
    GROUP_CONCAT(sa.id) as 'attendances_id',
    SUM(sa.amount) as attendances_amount
FROM
    stic_payments sp
JOIN stic_payments_stic_payment_commitments_c spspcc
ON
    sp.id = spspcc.stic_payments_stic_payment_commitmentsstic_payments_idb
JOIN stic_payment_commitments spc
ON
    spspcc.stic_paymebfe2itments_ida = spc.id
JOIN stic_payment_commitments_stic_registrations_c spcsrc
ON
    spc.id = spcsrc.stic_payme96d2itments_ida
JOIN stic_registrations sr
ON
    spcsrc.stic_paymee0afrations_idb = sr.id
JOIN stic_attendances_stic_registrations_c sasrc
ON
    sr.id = sasrc.stic_attendances_stic_registrationsstic_registrations_ida
JOIN stic_attendances sa
ON
    sasrc.stic_attendances_stic_registrationsstic_attendances_idb = sa.id
WHERE
    spc.deleted = 0
    AND sasrc.deleted = 0
    AND sr.deleted = 0
    AND spcsrc.deleted = 0
    AND spspcc.deleted = 0
    AND sp.deleted = 0
    AND sa.deleted = 0
    AND MONTH(sp.payment_date) = MONTH(NOW())
    AND sa.start_date < subdate(curdate(), (day(curdate())-1))
    AND CASE
            WHEN spc.periodicity = 'monthly' THEN sa.start_date >= subdate(subdate(curdate(), (day(curdate())-1)), INTERVAL 1 MONTH)
            WHEN spc.periodicity = 'bimonthly' THEN sa.start_date >= subdate(subdate(curdate(), (day(curdate())-1)), INTERVAL 2 MONTH)
            WHEN spc.periodicity = 'quarterly' THEN sa.start_date >= subdate(subdate(curdate(), (day(curdate())-1)), INTERVAL 3 MONTH)
            WHEN spc.periodicity = 'four_monthly' THEN sa.start_date >= subdate(subdate(curdate(), (day(curdate())-1)), INTERVAL 4 MONTH)
            WHEN spc.periodicity = 'half_yearly' THEN sa.start_date >= subdate(subdate(curdate(), (day(curdate())-1)), INTERVAL 6 MONTH)
            WHEN spc.periodicity = 'yearly' THEN sa.start_date >= subdate(subdate(curdate(), (day(curdate())-1)), INTERVAL 12 MONTH)
        END
    AND sp.payment_type = 'aggregated_services'
    AND sp.status != 'paid'
    AND (sa.payment_exception IS NULL
        OR sa.payment_exception != 'exclude')
    AND (sa.status = 'yes'
        OR sa.status = 'partial'
        OR ((sa.status = 'no_unjustified'
            OR sa.status = 'no_justified'
            OR sa.status IS NULL
            OR sa.status = '')
        AND sa.payment_exception = 'include'))
    AND sa.id NOT IN (
        SELECT
            spsac.stic_payments_stic_attendancesstic_attendances_idb
        FROM
            (
            SELECT
                id
            FROM
                stic_payments
            WHERE
                deleted = 0
                AND status = 'paid') AS spx
        JOIN stic_payments_stic_attendances_c spsac
            ON
            spx.id = spsac.stic_payments_stic_attendancesstic_payments_ida
        WHERE
            spsac.deleted=0)
GROUP BY sp.id";

$res = $db->query($aggregatedQuery);

$completePayments = 0;
$processedAttendances = 0;
$completePaymentsId = array();

// Process included payments
while ($row = $db->fetchByAssoc($res)) {
    if (!$paymentId = $row['payment_id']) {
        continue;
    }
    if (!$attendancesId = explode(',', $row['attendances_id'])) {
        continue;
    }

    $paymentBean = BeanFactory::getBean('stic_Payments', $paymentId);

    // Create relationships between payments and attendances
    $paymentBean->load_relationship('stic_payments_stic_attendances');
    foreach ($attendancesId as $attendanceId) {
        $paymentBean->stic_payments_stic_attendances->add($attendanceId);
        $processedAttendances++;
    }

    // Set the payment amount
    $paymentBean->amount = str_replace('.', $current_user->getPreference('dec_sep'), $row['attendances_amount']);

    // Build an array of complete payments
    if (!in_array($paymentId, $uncompletePaymentsId)) {
        $paymentBean->aggregated_services_complete = true;
        $completePaymentsId[] = $paymentId;
        $completePayments++;
    }

    // Save the payment
    $paymentBean->save();
}

// Build an array with those payments nor completed neither uncompleted (ie, warning payments)
$warningPaymentsData = array();
$warningPayments = 0;
foreach ($includedPaymentsData as $includedPaymentId => $includedPaymentData) {
    if (!in_array($includedPaymentId, $uncompletePaymentsId) && !in_array($includedPaymentId, $completePaymentsId)) {
        $warningPaymentsData[$warningPayments]['name'] = SticUtils::createLinkToDetailView('stic_Payments', $includedPaymentData['payment_id'], $includedPaymentData['payment_name']);
        $warningPaymentsData[$warningPayments]['assigned_user_id'] = $includedPaymentData['assigned_user_id'];
        $warningPaymentsData[$warningPayments]['assigned_user'] = SticUtils::createLinkToDetailView('Users', $includedPaymentData['assigned_user_id'], $includedPaymentData['assigned_user_first_name'] . ' ' . $includedPaymentData['assigned_user_last_name'] . ' (' . $includedPaymentData['assigned_user_user_name'] . ')');
        $warningPayments++;
    }
}

// Prepare the summary view
$this->view_object_map['WARNING_LOG_ATTENDANCES'] = $warningAttendancesData;
$this->view_object_map['WARNING_LOG_PAYMENTS'] = $warningPaymentsData;
$this->view_object_map['SUMMARY'] = array(
    'INCLUDED_PAYMENTS' => $includedPayments,
    'COMPLETE_PAYMENTS' => $completePayments,
    'UNCOMPLETE_PAYMENTS' => $uncompletePayments,
    'WARNING_PAYMENTS' => $warningPayments,
    'INCLUDED_ATTENDANCES' => $processedAttendances + $warningAttendances,
    'PROCESSED_ATTENDANCES' => $processedAttendances,
    'WARNING_ATTENDANCES' => $warningAttendances,
);
