<?php /** @noinspection PhpUnused */

require_once __DIR__ . '/../../../../modules/AOW_Actions/actions/actionBase.php';

class actionSevenSms extends actionBase {
    public function __construct($id = '') {
        parent::__construct($id);
    }

    public function loadJS() {
        return [];
    }

    public function edit_display($line, SugarBean $bean = null, $params = []) {
        $foreignId = $params['foreign_id'] ?? '';
        $label = $params['label'] ?? '';
        $flash = isset($params['flash']) ? 'checked=checked' : '';

        return "<label for='seven_from'>" . translate('LBL_SEVENSMS_FROM', 'AOW_Actions') . "</label>
                <input maxlength='16' id='seven_from' name='aow_actions_param[" . $line . "][from]' placeholder='"
            . translate('LBL_SEVENSMS_FROM', 'AOW_Actions') . "' value=' " . $params['from']
            . "'><br/>"
            . "<label for='seven_label'>" . translate('LBL_SEVENSMS_LABEL', 'AOW_Actions') . "</label>
                <input maxlength='100' id='seven_label' name='aow_actions_param[" . $line . "][label]' placeholder='"
            . translate('LBL_SEVENSMS_LABEL', 'AOW_Actions') . "' value=' " . $label
            . "'><br/>"
            . "<label for='seven_foreign_id'>" . translate('LBL_SEVENSMS_FOREIGN_ID', 'AOW_Actions') . "</label>
                <input maxlength='64' id='seven_foreign_id' name='aow_actions_param[" . $line . "][foreign_id]' placeholder='"
            . translate('LBL_SEVENSMS_FOREIGN_ID', 'AOW_Actions') . "' value=' " . $foreignId
            . "'><br/>"
            . "<label for='seven_flash'>" . translate('LBL_SEVENSMS_FLASH', 'AOW_Actions') . "</label>
                <input type='checkbox' id='seven_flash' name='aow_actions_param[" . $line . "][flash]' " . $flash
            . '><br/>'
            . "<label for='seven_text'>" . translate('LBL_SEVENSMS_TEXT', 'AOW_Actions') . "<span class='required'>*</span></label>"
            . "<textarea cols='110' id='seven_text' name='aow_actions_param[" . $line . "][text]' placeholder='"
            . translate('LBL_SEVENSMS_TEXT', 'AOW_Actions') . "' rows='5'>" . $params['text']
            . '</textarea>';
    }

    protected function getPhoneFromParams(SugarBean $bean, array $params): ?string {
        switch ($bean->module_name) {
            case 'Accounts':
                /** @var Account $bean */

                return $bean->phone_alternate;
            case 'Contacts':
                /** @var Contact $bean */

                return $bean->phone_mobile;
            case 'Employees':
                /** @var Employee $bean */

                return $bean->phone_mobile;
            case 'Leads':
                /** @var Lead $bean */

                return $bean->phone_mobile;
            case 'Users':
                /** @var User $bean */

                return $bean->phone_mobile;
            default:
                return null;
        }
    }

    /**
     * Return true on success otherwise false.
     * Use actionSevenSms::getLastMessagesSuccess() and actionSevenSms::getLastMessagesFailed()
     * methods to get last SMS sending status
     * @param SugarBean $bean
     * @param array $params
     * @param bool $in_save
     * @return boolean
     */
    public function run_action(SugarBean $bean, $params = [], $in_save = false) {
        global $sugar_config;

        $to = $this->getPhoneFromParams($bean, $params);
        if (!$to) return false;

        $apiKey = $sugar_config['seven_api_key'];
        if (!$apiKey) return false;

        $text = $params['text'];
        $from = $params['from'];
        $label = $params['label'];
        $foreign_id = $params['foreign_id'];
        $flash = 'yes' === $params['flash'];
        $json = compact('flash', 'foreign_id', 'from', 'label', 'text', 'to');

        $ch = curl_init('https://gateway.seven.io/api/sms');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-type: application/json',
            'X-Api-Key: ' . $apiKey,
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $result = json_decode($result);
        curl_close($ch);

        return 100 === $result->success;
    }
}
