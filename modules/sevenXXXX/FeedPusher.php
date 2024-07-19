<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once 'modules/SugarFeed/feedLogicBase.php';
require_once 'modules/seven/seven_util.php';

/** @noinspection PhpUnused */

class FeedPusher extends FeedLogicBase {
    public $module = 'Contacts';

    protected function init($bean): array {
        global $sms;

        $relation = $bean;
        if ('Contacts' === $this->module) {
            $templateActive = $sms->getContactActive();
            $template = $sms->getContactBody();
        } elseif ('Leads' === $this->module) {
            $templateActive = $sms->getLeadActive();
            $template = $sms->getLeadBody();
        } elseif ('Accounts' === $this->module) {
            $templateActive = $sms->getLeadActive();
            $template = $sms->getLeadBody();
        } else {
            $templateActive = false;
            $template = '';
            $relation = null;
        }

        $sms->setRelation($relation);

        return [$template, $templateActive];
    }

    /** @noinspection PhpUnused */
    public function pushFeed($bean, $event, $arguments) {
        global $locale, $sms;

        require_once 'modules/seven/sendSMS.php'; // initializes $sms

        $this->module = $_REQUEST['module'] ?? $this->module;

        if (!empty($bean->fetched_row)) return;

        $officePhone = $bean->phone_work;
        $mobilePhone = $bean->phone_mobile;
        $fullName = $locale->getLocaleFormattedName($bean->first_name, $bean->last_name);
        $fullName = trim($fullName);
        [$firstName, $lastName] = explode(' ', $fullName);
        [$template, $templateActive] = $this->init($bean);
        if (!$templateActive) return;

        $numbers = seven_util::getValidPhoneNumbers([$mobilePhone, $officePhone]);
        if (!count($numbers)) return;

        switch ($this->module) {
            case 'Accounts':
                $placeholders = [
                    '{account_type}' => $bean->account_type,
                    '{annual_revenue}' => $bean->annual_revenue,
                    '{assigned_user_id}' => $bean->assigned_user_id,
                    '{billing_address_city}' => $bean->billing_address_city,
                    '{billing_address_country}' => $bean->billing_address_country,
                    '{billing_address_postalcode}' => $bean->billing_address_postalcode,
                    '{billing_address_state}' => $bean->billing_address_state,
                    '{billing_address_street}' => $bean->billing_address_street,
                    '{campaign_id}' => $bean->campaign_id,
                    '{created_by}' => $bean->created_by,
                    '{date_entered}' => $bean->date_entered,
                    '{date_modified}' => $bean->date_modified,
                    '{deleted}' => $bean->deleted,
                    '{description}' => $bean->description,
                    '{employees}' => $bean->employees,
                    '{id}' => $bean->id,
                    '{industry}' => $bean->industry,
                    '{modified_user_id}' => $bean->modified_user_id,
                    '{name}' => $bean->name,
                    '{ownership}' => $bean->ownership,
                    '{parent_id}' => $bean->parent_id,
                    '{phone_alternate}' => $bean->phone_alternate,
                    '{phone_fax}' => $bean->phone_fax,
                    '{phone_office}' => $bean->phone_office,
                    '{rating}' => $bean->rating,
                    '{shipping_address_city}' => $bean->shipping_address_city,
                    '{shipping_address_country}' => $bean->shipping_address_country,
                    '{shipping_address_postalcode}' => $bean->shipping_address_postalcode,
                    '{shipping_address_state}' => $bean->shipping_address_state,
                    '{shipping_address_street}' => $bean->shipping_address_street,
                    '{sic_code}' => $bean->sic_code,
                    '{ticker_symbol}' => $bean->ticker_symbol,
                    '{website}' => $bean->website,
                ];
                $search = array_keys($placeholders);
                $replace = array_values($placeholders);
                break;
            case 'Contacts':
            case 'Leads':
                $search = [
                    '{first-name}',
                    '{last-name}',
                    '{full-name}',
                    '{office-phone}',
                    '{mobile-number}',
                    '{job-title}',
                    '{department}',
                    '{account-name}',
                    '{email}',
                    '{primary-address}',
                    '{primary-city}',
                    '{primary-state}',
                    '{primary-postalcode}',
                    '{primary-country}',
                    '{alter-address}',
                    '{alter-city}',
                    '{alter-state}',
                    '{alter-postalcode}',
                    '{alter-country}',
                    '{description}',
                    '{date}',
                ];
                $replace = [
                    $firstName,
                    $lastName,
                    $bean->first_name . ' ' . $bean->last_name,
                    $officePhone,
                    $mobilePhone,
                    $bean->title,
                    $bean->department,
                    $bean->account_name,
                    $bean->email1,
                    $bean->primary_address_street,
                    $bean->primary_address_city,
                    $bean->primary_address_state,
                    $bean->primary_address_postalcode,
                    $bean->primary_address_country,
                    $bean->alt_address_street,
                    $bean->alt_address_city,
                    $bean->alt_address_state,
                    $bean->alt_address_postalcode,
                    $bean->alt_address_country,
                    $bean->description,
                    date('d/m/Y'),
                ];
                break;
            case 'Employees':
                $placeholders = [
                    '{address_city}' => $bean->address_city,
                    '{address_country}' => $bean->address_country,
                    '{address_postalcode}' => $bean->address_postalcode,
                    '{address_state}' => $bean->address_state,
                    '{address_street}' => $bean->address_street,
                    '{authenticate_id}' => $bean->authenticate_id,
                    '{created_by}' => $bean->created_by,
                    '{date_entered}' => $bean->date_entered,
                    '{date_modified}' => $bean->date_modified,
                    '{deleted}' => $bean->deleted,
                    '{department}' => $bean->department,
                    '{description}' => $bean->description,
                    '{employee_status}' => $bean->employee_status,
                    '{external_auth_only}' => $bean->external_auth_only,
                    '{factor_auth_interface}' => $bean->factor_auth_interface,
                    '{factor_auth}' => $bean->factor_auth,
                    '{first_name}' => $bean->first_name,
                    '{id}' => $bean->id,
                    '{is_admin}' => $bean->is_admin,
                    '{is_group}' => $bean->is_group,
                    '{last_name}' => $bean->last_name,
                    '{messenger_id}' => $bean->messenger_id,
                    '{messenger_type}' => $bean->messenger_type,
                    '{modified_user_id}' => $bean->modified_user_id,
                    '{phone_fax}' => $bean->phone_fax,
                    '{phone_home}' => $bean->phone_home,
                    '{phone_mobile}' => $bean->phone_mobile,
                    '{phone_other}' => $bean->phone_other,
                    '{phone_work}' => $bean->phone_work,
                    '{photo}' => $bean->photo,
                    '{portal_only}' => $bean->portal_only,
                    '{pwd_last_changed}' => $bean->pwd_last_changed,
                    '{receive_notifications}' => $bean->receive_notifications,
                    '{reports_to_id}' => $bean->reports_to_id,
                    '{show_on_employees}' => $bean->show_on_employees,
                    '{status}' => $bean->status,
                    '{sugar_login}' => $bean->sugar_login,
                    '{system_generated_password}' => $bean->system_generated_password,
                    '{title}' => $bean->title,
                    '{user_hash}' => $bean->user_hash,
                    '{user_name}' => $bean->user_name,
                ];
                $search = array_keys($placeholders);
                $replace = array_values($placeholders);
                break;
            default:
                return;
        }

        $sms->apiCall(
            $sms->getSender(),
            str_replace($search, $replace, trim($template)),
            implode(',', $numbers)
        );

        SugarFeed::pushFeed2('{SugarFeed.CREATED_' . substr(strtoupper($this->module), 0, -1)
            . '} [' . $bean->module_dir . ':' . $bean->id . ':' . $fullName . ']', $bean);
    }
}
