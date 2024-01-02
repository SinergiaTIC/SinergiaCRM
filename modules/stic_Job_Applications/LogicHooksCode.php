<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class stic_Job_ApplicationsLogicHooks {

    public function before_save(&$bean, $event, $arguments)
    {
        // Create name if empty
        if (empty($bean->name)) {
            if ($bean->load_relationship('stic_job_applications_contacts')) {
                $related_beans = $bean->stic_job_applications_contacts->getBeans();
                $related_bean = array_pop($related_beans);
                $contact_name = $related_bean->first_name .' '.$related_bean->last_name;
            }
            if ($bean->load_relationship('stic_job_applications_stic_job_offers')) {
                $related_beans = $bean->stic_job_applications_stic_job_offers->getBeans();
                $related_bean = array_pop($related_beans);
                $offer_name = $related_bean->name;
            }
            $bean->name = $contact_name .' - '.$offer_name;
        }
    }
}