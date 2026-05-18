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

interface stic_MessagesHelper {
    /**
     * Returns the helper type identifier (e.g., 'sms', 'whatsapp').
     * Used to determine template type and message capabilities.
     */
    public function getHelperType(): string;

    /**
     * Returns the template type used by this helper (e.g., 'sms', 'whatsapp').
     * Used to filter available templates in UI.
     */
    public function getTemplateType(): string;

    /**
     * Returns true if this helper passes template body to the external provider
     * along with template SID and resolved variables. Used for providers like
     * Twilio WhatsApp that handle template substitution server-side.
     * Returns false for helpers that send pre-resolved text directly.
     */
    public function passesTemplateBodyToProvider(): bool;

    public function sendMessage(?string $from, string $text, string $to): array;
}