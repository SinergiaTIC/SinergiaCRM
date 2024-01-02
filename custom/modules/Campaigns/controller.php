<?php

require_once('modules/Campaigns/controller.php');
class CustomCampaignsController extends CampaignsController
{
    // STIC - 20210624  - We override the process function to recover the classic view to create or edit a campaign
    public function process()
    {
        SugarController::process();
    }
}
