<?php

abstract class seven_util {
    public static function isValidPhoneNumber(string $phone): bool {
        return !empty($phone) && strlen($phone) >= 9;
    }

    public static function getValidPhoneNumbers(array $phones): array {
        $numbers = [];

        foreach ($phones as $phone)
            if (self::isValidPhoneNumber($phone)) $numbers[] = $phone;

        return $numbers;
    }

    public static function getSMS(string $module, SugarBean $contact): array {
        $beans = [];

        $list = (BeanFactory::newBean($module))->get_full_list();

        if ($list) foreach ($list as $item) {
            /** @var seven_sms|seven_sms_inbound $item */
            if ($item->contact_id === $contact->id) {
                $beans[] = $item;
            }
        }

        return $beans;
    }

    public static function stripPhone(string $phone): string {
        return preg_replace('~\D~', '', $phone);
    }

    public static function getBeanByPhone(string $module, string $phone): ?SugarBean {
        $bean = null;
        $beans = BeanFactory::newBean($module)->get_full_list();

        foreach ($beans as $item) {
            /** @var Lead|Contact|Account|Employee $item */
            $key = 'Accounts' === $module ? 'phone_office' : 'phone_mobile';
            if (self::stripPhone($item->$key) === $phone) {
                $bean = $item;
                break;
            }
        }

        return $bean;
    }
}
