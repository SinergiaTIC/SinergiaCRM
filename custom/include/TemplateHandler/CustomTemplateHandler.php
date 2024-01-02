<?php

/**
 * This file is used in the SinergiaCRM hotfix #150 that allows properly viewing HTML fields without setting DeveloperMode to true.
 * 
 * CustomTemplateHandler overrides the checkTemplate generic function in order to invalidate the cache of a certain View.
 * When the cache is invalidated the view will be rebuilt on every access so HTML fields will display their right values.
 * 
 */

require_once('include/TemplateHandler/TemplateHandler.php');

class CustomTemplateHandler extends TemplateHandler
{
    public $disableCheckTemplate = false;

    /**
     * Override checkTemplate
     * @see TemplateHandler::checkTemplate()
     */
    function checkTemplate($module, $view, $checkFormName = false, $formName = '')
    {
        if ($this->disableCheckTemplate === true){
            return false;
        }
        
        return parent::checkTemplate($module, $view, $checkFormName, $formName);
    }
}
