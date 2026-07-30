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
        $modStrings = $this->getModStrings($moduleName);

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
        $options = array();
        if (!empty($optionsKey) && isset($appListStrings[$optionsKey])) {
            $options = $appListStrings[$optionsKey];
        }

        $type = $bean->field_defs[$field]['type'] ?? '';
        if ($type === 'multienum') {
            if (is_array($value)) {
                $values = $value;
            } else {
                $values = explode('^,^', trim($value, '^'));
            }
            $display = array();
            foreach ($values as $v) {
                $v = trim($v);
                if (isset($options[$v])) {
                    $display[] = $options[$v];
                } else {
                    $display[] = $v;
                }
            }
            return implode(', ', $display);
        }

        return isset($options[$value]) ? $options[$value] : $value;
    }

    private function formatDateValue($value)
    {
        if (empty($value) || $value === '0000-00-00' || $value === '0000-00-00 00:00:00') {
            return '';
        }
        return $value;
    }

    private function getModStrings($moduleName)
    {
        $language = $GLOBALS['current_language'] ?? 'en_us';
        $modStrings = return_module_language($language, $moduleName);
        return $modStrings ?: array();
    }
}
