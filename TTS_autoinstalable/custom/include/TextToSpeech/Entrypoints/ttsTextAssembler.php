<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class TtsTextAssembler
{
    public function buildFromBean($bean, $fields, $language = null)
    {
        $parts = array();
        $moduleName = $bean->module_dir;
        $modStrings = $this->getModStrings($moduleName, $language);

        foreach ($fields as $field) {
            $label = $this->getFieldLabel($bean, $field, $modStrings);
            $value = $this->getFieldValue($bean, $field);

            if ($value === null || $value === '' || $value === false) {
                continue;
            }

            if (is_array($value)) {
                $value = implode(', ', $value);
            }

            $value = strip_tags($value);
            $value = trim($value);

            if (empty($value)) {
                continue;
            }

            $parts[] = $label . ': ' . $value;
        }

        return implode('. ', $parts);
    }

    public function buildFromText($text)
    {
        return $text;
    }

    private function getFieldLabel($bean, $field, $modStrings)
    {
        if (isset($bean->field_defs[$field]['vname'])) {
            $vname = $bean->field_defs[$field]['vname'];
            if (isset($modStrings[$vname])) {
                return $modStrings[$vname];
            }
            global $app_strings;
            if (isset($app_strings[$vname])) {
                return $app_strings[$vname];
            }
        }
        return $field;
    }

    private function getFieldValue($bean, $field)
    {
        if (!isset($bean->$field)) {
            return null;
        }
        $value = $bean->$field;

        if ($this->isDateField($bean, $field)) {
            return $this->formatDateValue($value);
        }
        if ($this->isBoolField($bean, $field)) {
            return $value ? 'Yes' : 'No';
        }
        if ($this->isEnumField($bean, $field)) {
            return $this->getEnumDisplayValue($bean, $field, $value);
        }

        return $value;
    }

    private function isDateField($bean, $field)
    {
        $type = $bean->field_defs[$field]['type'] ?? '';
        return in_array($type, array('date', 'datetime', 'datetimecombo'));
    }

    private function isBoolField($bean, $field)
    {
        $type = $bean->field_defs[$field]['type'] ?? '';
        return in_array($type, array('bool', 'boolean'));
    }

    private function isEnumField($bean, $field)
    {
        $type = $bean->field_defs[$field]['type'] ?? '';
        return in_array($type, array('enum', 'multienum', 'radioenum'));
    }

    private function getEnumDisplayValue($bean, $field, $value)
    {
        if (empty($value)) {
            return $value;
        }
        $appListStrings = return_app_list_strings_language($GLOBALS['current_language'] ?? 'en_us');
        $optionsKey = $bean->field_defs[$field]['options'] ?? '';
        if (!empty($optionsKey) && isset($appListStrings[$optionsKey])) {
            $options = $appListStrings[$optionsKey];
            if (isset($options[$value])) {
                return $options[$value];
            }
        }
        return $value;
    }

    private function formatDateValue($value)
    {
        if (empty($value) || $value === '0000-00-00') {
            return '';
        }
        $timestamp = strtotime($value);
        if ($timestamp === false) {
            return $value;
        }
        global $timedate;
        if ($timedate) {
            return $timedate->to_display_date_time($value);
        }
        return date('Y-m-d', $timestamp);
    }

    private function getModStrings($moduleName, $language = null)
    {
        if ($language === null) {
            $language = $GLOBALS['current_language'] ?? 'en_us';
        }
        $langMap = array(
            'ca' => 'ca_ES',
            'es' => 'es_ES',
            'en' => 'en_us',
            'eu' => 'eu_ES',
            'gl' => 'gl_ES',
            'pt' => 'pt_PT',
            'fr' => 'fr_FR',
            'de' => 'de_DE',
            'it' => 'it_IT',
        );
        $suiteLang = isset($langMap[$language]) ? $langMap[$language] : $language;
        $modStrings = return_module_language($suiteLang, $moduleName);
        return $modStrings ?: array();
    }
}
