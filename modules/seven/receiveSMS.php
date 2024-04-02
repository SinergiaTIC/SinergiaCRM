<?php
require_once 'seven_util.php';

$data = $_POST['data'] ?? null;

if ('sms_mo' !== $_POST['webhook_event'] ?? null || !$data) return;

/** @var seven_sms_inbound $inbound */
$inbound = BeanFactory::newBean('seven_sms_inbound');
$inbound->inbound_number = $data['system'] ?? null;
$inbound->msg_id = $data['id'] ?? null;
$inbound->msg_text = $data['text'] ?? null;
$inbound->msg_time = $data['time'] ?? null;
$inbound->sender = $data['sender'] ?? null;

setBeanID($inbound);

$inbound->save();

function setBeanID(seven_sms_inbound $inbound): void {
    $phone = seven_util::stripPhone($inbound->sender);
    $contact = seven_util::getBeanByPhone('Contacts', $phone);

    if ($contact) {
        /** @var Contact $contact */
        $inbound->contact_id = $contact->id;
    } else {
        $lead = seven_util::getBeanByPhone('Leads', $phone);

        if ($lead) {
            /** @var Lead $lead */
            $inbound->lead_id = $lead->id;
        } else {
            $account = seven_util::getBeanByPhone('Accounts', $phone);

            if ($account) {
                /** @var Account $account */
                $inbound->account_id = $account->id;
            } else {
                $employee = seven_util::getBeanByPhone('Employees', $phone);

                if ($employee) {
                    /** @var Employee $employee */
                    $inbound->employee_id = $employee->id;
                }
            }
        }
    }
}
