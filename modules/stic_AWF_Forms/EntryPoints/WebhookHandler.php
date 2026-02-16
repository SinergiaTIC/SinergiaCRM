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
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once "modules/stic_Advanced_Web_Forms/core/includes.php";

/**
 * EntryPoint: WebhookHandler
 * It is responsible for receiving and process webhook responses.
 */
class WebhookHandler
{
    public function run(): void {
        // IEPA!!
        // TODO: Revisar lògica

        // 1. Determinar font (Redsys, Stripe...)
        $source = $_REQUEST['source'] ?? '';
        
        // 2. Obtenir dades crues
        $rawData = $_POST; // O file_get_contents('php://input') depenent del proveïdor

        // 3. Buscar el Ticket (Això és la part delicada, cada proveïdor envia l'ID diferent)
        // Caldria un "WebhookDiscovery" o que el propi Handler sàpiga extreure l'ID segons el 'source'.
        $externalId = $this->extractExternalId($source, $rawData);
        
        $ticket = $this->findTicket($externalId);
        if (!$ticket) {
            http_response_code(404);
            die("Ticket not found");
        }

        // 4. Reconstruir Context
        $context = $this->rebuildContext($ticket);

        // 5. Instanciar l'Acció
        $actionDefinition = $this->loadActionDefinition($ticket->handler_action_id);
        
        if ($actionDefinition instanceof IDeferredAction) {
            // 6. EXECUTAR PROCESSWEBHOOK
            $result = $actionDefinition->processWebhook($context, $rawData);

            if ($result->isOk()) {
                // Pagament correcte!
                $ticket->status = 'resolved';
                $ticket->save();
                
                // Opcional: Reprendre el flux principal del formulari (enviar emails, etc)
                // $executor->resumeFlow($ticket); 
                
                http_response_code(200);
                echo "OK"; // Resposta per al banc
            } elseif ($result->isWait()) {
                // CAS INTERMEDI (Stripe "Processing", o Firma "Viewed")
                // L'acció ens diu: "Tot ha anat bé, he actualitzat el que calia, 
                // però NO tanquis el tiquet encara, espera més events".
                
                // Potser l'estratègia ha actualitzat dades del context i volem guardar-les al tiquet
                if (!empty($result->getData())) {
                    $ticket->context_data = json_encode($result->getData());
                }
                $ticket->save(); // Guardem canvis però status segueix 'pending'

                // Al banc li diem OK, perquè hem rebut la info correctament
                http_response_code(200);
                echo "OK, Updated";
            } else {
                // Pagament fallit o error de validació
                http_response_code(400);
                echo "Error: " . $result->message;
            }
        }
    }
}

// Handler execution
$handler = new WebhookHandler();
$handler->run();