<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
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
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */
class stic_SignaturesController extends SugarController
{

    public function action_getRelationships()
    {
        require_once 'modules/stic_Signatures/Utils.php';
        var_dump(stic_SignaturesUtils::getModuleRelationships($_REQUEST['getmodule'], $_REQUEST['format'] ?? 'raw'));
        die();
    }


    public function action_getSignatureSigners()
    {
        require_once 'modules/stic_Signatures/Utils.php';
        

            $signatureId = '00000167-b5f5-f931-0432-6875fd393f8d';
            $mainModuleIds = ['7932e3c3-c5fc-8942-95f5-63106d62940f','176e0992-a61b-26cf-c58d-63106ba8c3b1','d9db7680-1a78-4b4a-70ba-63106d2771c2'];
            $signerPathList = stic_SignaturesUtils::getSignatureSigners($signatureId, $mainModuleIds);
            
        
        
        var_dump($signerPathList);
        die();
    }

        
        
        
      

}
