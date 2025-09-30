<?php
/**
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo, "Supercharged by SuiteCRM" logo and “Nonprofitized by SinergiaCRM” logo. 
 * If the display of the logos is not reasonably feasible for technical reasons, 
 * the Appropriate Legal Notices must display the words "Powered by SugarCRM", 
 * "Supercharged by SuiteCRM" and “Nonprofitized by SinergiaCRM”. 
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
include 'suitecrm_version.php';
global $sugar_config, $mod_strings;

?>
<div class="about" id="about_header">
    <p><b>SinergiaCRM: <?php echo $mod_strings['LBL_VERSION'] . ' ' . $sugar_config['sinergiacrm_version']; ?></b></p>
    <p>SuiteCRM: <?php echo $mod_strings['LBL_VERSION'] . ' ' . $suitecrm_version;
    if (is_file('custom_version.php')) {
        include 'custom_version.php';
        echo '&nbsp;&nbsp;&nbsp;' . $custom_version;
    } ?>
    </p>
    <p>SugarCRM: <?php echo $mod_strings['LBL_VERSION'] . ' ' . $sugar_version . ' (' . $mod_strings['LBL_BUILD'] . ' ' . $sugar_build . ')'; ?></p>
    <br>
    
    <h1><img src="SticInclude/images/SinergiaCRMLogo.png" alt="SinergiaCRM"></h1>
    <h1><?php echo $mod_strings['LBL_ABOUT_SINERGIACRM_TITLE']; ?></h1>
    <table id="about_table" class="contentBox">
        <tr>
            <td valign="top" style="padding: 15px 10px 15px 10px;">
                <div class="about_suite">
                    <h3><?php echo $mod_strings['LBL_ABOUT_SINERGIACRM']; ?></h3>
                    <ul id="about_menu">
                        <li><?php echo $mod_strings['LBL_ABOUT_SINERGIACRM_1']; ?></li>
                        <li><?php echo $mod_strings['LBL_ABOUT_SINERGIACRM_2']; ?></li>
                    </ul>
                </div>
                <div class="about_suite">
                    <h3><?php echo $mod_strings['LBL_ABOUT_SINERGIACRM_CONTRIBUTORS']; ?></h3>
                    <ul id="about_menu">
                        <li><?php echo $mod_strings['LBL_ABOUT_SINERGIACRM_KREPORTER']; ?> (<a href="mailto:info@kreporter.com">info@kreporter.com</a>)
                        </li>
                        <li><?php echo $mod_strings['LBL_ABOUT_SINERGIACRM_MAIL_MERGE']; ?> (<a href="mailto:info@izertis.com">info@izertis.com</a>)
                        </li>
                        <li><?php echo $mod_strings['LBL_ABOUT_SINERGIACRM_OTHER']; ?> 
                        </li>
                    </ul>
                </div>
        </tr>
    </table>

    <h1><img src="include/images/suite_logo.png" alt="SuiteCRM"></h1>
    <h1><?php echo $mod_strings['LBL_CONTRIBUTOR_SUITECRM']; ?></h1>
    <table id="about_table" class="contentBox">
        <tr>
            <td valign="top" style="padding: 15px 10px 15px 10px;">
                <div class="about_suite">
                    <br>
                    <h3><?php echo $mod_strings['LBL_ABOUT_SUITE']; ?></h3>
                    <ul id="about_menu">
                        <li><?php echo $mod_strings['LBL_ABOUT_SUITE_2']; ?></li>
                        <li><?php echo $mod_strings['LBL_ABOUT_SUITE_4']; ?></li>
                        <li><?php echo $mod_strings['LBL_ABOUT_SUITE_5']; ?></li>
                    </ul>
                </div>
                <div class="about_suite">
                    <br>
                    <h3><?php echo $mod_strings['LBL_CONTRIBUTORS']; ?></h3>
                    <ul id="about_menu">
                        <li><?php echo $mod_strings['LBL_FEATURING']; ?>(<a href="http://www.salesagility.com"
                                                                            target="_blank">http://www.salesagility.com</a>)
                        </li>
                        <li><?php echo $mod_strings['LBL_CONTRIBUTOR_SECURITY_SUITE']; ?> (<a
                                    href="http://www.sugaroutfitters.com"
                                    target="_blank">http://www.sugaroutfitters.com</a>)
                        </li>
                        <li><?php echo $mod_strings['LBL_CONTRIBUTOR_JJW_GMAPS']; ?> (<a href="http://www.jjwdesign.com"
                                                                                         target="_blank">http://www.jjwdesign.com</a>)
                        </li>
                        <li><?php echo $mod_strings['LBL_CONTRIBUTOR_CONSCIOUS']; ?> (<a
                                    href="http://www.conscious.co.uk" target="_blank">http://www.conscious.co.uk</a>)
                        </li>
                        <li><?php echo $mod_strings['LBL_CONTRIBUTOR_RESPONSETAP']; ?> (<a
                                    href="https://www.responsetap.com">https://www.responsetap.com</a>)
                        </li>
                        <li><?php echo $mod_strings['LBL_SOURCE_SUGAR']; ?> (<a href="http://www.sugarcrm.com"
                                                                                target="_blank">http://www.sugarcrm.com</a>)
                        </li>
                        <li><?php echo $mod_strings['LBL_CONTRIBUTOR_GMBH']; ?> (<a href="http://www.dtbc.eu/"
                                                                                    target="_blank">http://www.dtbc.eu/</a>)
                        </li>
                    </ul>
                </div>

               <div class="about_suite">
                    <br>
                    <h3><?php echo $mod_strings['LBL_LANGUAGE_ABOUT']; ?></h3>
                    <ul id="about_menu">
                        <li><?php echo $mod_strings['LBL_LANGUAGE_COMMUNITY_ABOUT']; ?>
                        </li>
                        <li><?php echo $mod_strings['LBL_LANGUAGE_COMMUNITY_PACKS']; ?> (<a
                                href="https://crowdin.com/project/suitecrmtranslations" target="_blank">https://crowdin.com/project/suitecrmtranslations</a>)
                        </li>
                    </ul>
                </div>

                <div class="about_suite">
                    <br>
                    <h3><?php echo $mod_strings['LBL_PARTNERS']; ?></h3>
                    <ul id="about_menu">
                        <li><?php echo $mod_strings['LBL_SUITE_PARTNERS']; ?> (<a
                                    href="https://suitecrm.com/about/about-us/partners">http://suitecrm.com</a>)
                        </li>
                    </ul>
                </div>
        </tr>
    </table>
</div>
