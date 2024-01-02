<?php

class CustomOpportunitiesController extends SugarController
{
    /**
     * Allow call the function directly OpportunitiesUtils::action_opportunitiesReminder using a url like
     * <server_url>/index.php?module=Opportunities&action=opportunitiesReminder
     *
     * @return void
     */
    public function action_opportunitiesReminder() {
        require_once 'custom/modules/Opportunities/SticUtils.php';
        OpportunitiesUtils::opportunitiesReminder();
        SugarApplication::redirect('index.php?module=Opportunities&action=index');
    }
}
