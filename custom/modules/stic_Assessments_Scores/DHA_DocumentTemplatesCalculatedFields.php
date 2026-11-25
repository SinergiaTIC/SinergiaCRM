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

require_once('modules/DHA_PlantillasDocumentos/DHA_DocumentTemplatesCalculatedFields_base.php');

/**
 * Mail Merge Reports calculated fields for stic_Assessments_Scores.
 *
 * Exposes the questions/answers stored in the "submission_data" JSON field
 * (filled by the Advanced Web Forms) as merge variables ready to use in the
 * document templates:
 *
 *  - cf_perfil_nom / cf_perfil_expedient / cf_perfil_data_incorporacio /
 *    cf_perfil_data_elaboracio / cf_perfil_experta: header data captured by
 *    the form (available in "main" score records).
 *  - cf_perfil_respuestas: formatted multi-line text with the questions and
 *    answers ("Sección · Pregunta: Respuesta").
 *
 * Template variables are prefixed with "cf_": [a.cf_perfil_respuestas] for the
 * main record and [a_subN.cf_perfil_respuestas] inside the sub-block of the
 * self-relationship link (children_link) for the partial scores.
 */
class stic_Assessments_Scores_DocumentTemplatesCalculatedFields extends DHA_DocumentTemplatesCalculatedFields
{
    /** Field names of the form header (first data block) exposed as merge variables */
    private const HEADER_FIELDS = [
        'nom' => 'perfil_nom',
        'expedient' => 'perfil_expedient',
        'data_incorporacio_taller' => 'perfil_data_incorporacio',
        'data_elaboracio' => 'perfil_data_elaboracio',
        'experta' => 'perfil_experta',
    ];

    /**
     * Defines the calculated fields offered to the template variable selector.
     * @return void
     */
    protected function SetCalcFieldsDefs()
    {
        $this->CalcFieldsDefs['perfil_respuestas'] = array(
            'name' => 'perfil_respuestas',
            'vname' => 'Perfil de competències: preguntas y respuestas del JSON',
            'type' => 'text',
            'help' => 'Preguntas y respuestas del envío del formulario, formateadas como lista ("Sección · Pregunta: Respuesta").',
        );
        foreach (self::HEADER_FIELDS as $variable) {
            $this->CalcFieldsDefs[$variable] = array(
                'name' => $variable,
                'vname' => 'Perfil de competències: ' . $variable,
                'type' => 'varchar',
                'help' => 'Dato de cabecera del formulario (vació si el envío no lo incluye).',
            );
        }
    }

    /**
     * Computes the calculated fields from the "submission_data" JSON of the record.
     * @return void
     */
    public function CalcFields()
    {
        // The base CalcFields() needs the Generate_Document instance (currency,
        // dates); it is always present in the real merge flow
        if ($this->Generate_Document_Instance !== null) {
            parent::CalcFields();
        }

        $qa = $this->getQuestionsAndAnswers();

        // Multi-line formatted questions and answers ("Sección · Pregunta: Respuesta")
        $lines = [];
        foreach ($qa as $row) {
            $prefix = ($row['section'] !== '') ? $row['section'] . ' · ' : '';
            $lines[] = '• ' . $prefix . $row['question'] . ': ' . $row['answer'];
        }
        $this->SetCalcValue('perfil_respuestas', implode("\n", $lines));

        // Header data captured by the form
        $header = $this->getHeaderData();
        foreach (self::HEADER_FIELDS as $jsonName => $variable) {
            $this->SetCalcValue($variable, $header[$jsonName] ?? '');
        }
    }

    /**
     * Enables the conversion of new lines in merged values into line breaks
     * in the generated Word documents.
     * @return void
     */
    public function BeforeMergeBlock()
    {
        $tbs = $this->Generate_Document_Instance->TBS ?? null;
        if ($tbs !== null) {
            $tbs->OtbsConvBr = '<w:br/>';
        }

        // Expose the questions/answers of each partial score as a nested array
        // ([a_sub1_sub1...] sub-block of the children_link sub-block), so the
        // template can render a colored table row per question.
        $datos = &$this->Generate_Document_Instance->datos;
        if (!is_array($datos)) {
            return;
        }
        foreach ($datos as &$row) {
            if (!is_array($row) || empty($row['children_link']) || !is_array($row['children_link'])) {
                continue;
            }
            foreach ($row['children_link'] as &$child) {
                if (!is_array($child) || empty($child['submission_data'])) {
                    continue;
                }
                $decoded = json_decode(html_entity_decode((string)$child['submission_data'], ENT_QUOTES, 'UTF-8'), true);
                if (!is_array($decoded)) {
                    continue;
                }
                $block = $decoded['block'] ?? null;
                if (!is_array($block)) {
                    continue;
                }
                $preguntas = [];
                $partialTotal = 0.0;
                $partialHasScores = false;
                foreach ($block['fields'] ?? [] as $field) {
                    if (!is_array($field)) {
                        continue;
                    }
                    $type = (string)($field['type'] ?? '');
                    if ($type === '' || $type === 'relate' || $type === 'textarea') {
                        continue; // System/FK fields and the section's own observations
                    }
                    $answer = isset($field['text']) && $field['text'] !== null && $field['text'] !== ''
                        ? (string)$field['text']
                        : ($field['value'] ?? null);
                    if ($answer === null || $answer === '' || is_array($answer)) {
                        continue; // Unanswered items
                    }
                    $score = $field['score'] ?? null;
                    $hasScore = ($score !== null && $score !== '');
                    if ($hasScore) {
                        $partialTotal += (float)$score;
                        $partialHasScores = true;
                    }
                    $preguntas[] = [
                        'pregunta' => rtrim(trim((string)($field['label'] ?? '')), " :\t"),
                        'respuesta' => (string)$answer . ($hasScore ? ' (puntuació: ' . $score . ')' : ''),
                        'puntuacio' => $hasScore ? (string)$score : '',
                    ];
                }
                $child['cf_preguntas'] = $preguntas;
                // Pre-formatted partial score line (empty when the form items have no scores)
                $child['cf_puntuacio_parcial'] = $partialHasScores
                    ? 'Puntuació parcial: ' . (($partialTotal == (int)$partialTotal) ? (string)(int)$partialTotal : (string)round($partialTotal, 2))
                    : '';
            }
        }
    }

    /**
     * Decodes the "submission_data" JSON of the record. Tolerates the two
     * supported structures: the whole submission ("blocks" array, filled in
     * main scores) and the per-block structure ("block" key, filled in
     * partial scores).
     * @return array The decoded JSON (empty array when it is not valid)
     */
    private function getDecodedSubmissionData(): array
    {
        $decoded = json_decode(html_entity_decode((string)($this->bean->submission_data ?? ''), ENT_QUOTES, 'UTF-8'), true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Extracts the questions/answers pairs from the decoded submission JSON:
     * one row per field with a value, including the section (block) it belongs to.
     * @param array $decoded The decoded submission JSON
     * @return array[] List of rows: ['section', 'question', 'answer']
     */
    private function extractQuestionsAndAnswers(array $decoded): array
    {
        $rows = [];

        // Normalize both structures to a flat list of blocks: [text, fields]
        $blocks = [];
        if (isset($decoded['block']) && is_array($decoded['block'])) {
            $blocks[] = $decoded['block'];
        } elseif (isset($decoded['blocks']) && is_array($decoded['blocks'])) {
            foreach ($decoded['blocks'] as $block) {
                if (is_array($block)) {
                    $blocks[] = $block;
                }
            }
        }

        foreach ($blocks as $block) {
            $section = (string)($block['text'] ?? '');
            $fieldGroups = [];
            if (!empty($block['fields']) && is_array($block['fields'])) {
                $fieldGroups[] = $block['fields'];
            }
            if (!empty($block['instances']) && is_array($block['instances'])) {
                foreach ($block['instances'] as $instanceFields) {
                    if (is_array($instanceFields)) {
                        $fieldGroups[] = $instanceFields;
                    }
                }
            }

            foreach ($fieldGroups as $fields) {
                foreach ($fields as $field) {
                    if (!is_array($field)) {
                        continue;
                    }
                    // Skip structural/system fields (fixed server fields, FKs)
                    $type = (string)($field['type'] ?? '');
                    if ($type === '' || $type === 'relate') {
                        continue;
                    }
                    $answer = isset($field['text']) && $field['text'] !== null && $field['text'] !== ''
                        ? (string)$field['text']
                        : ($field['value'] ?? null);
                    if ($answer === null || $answer === '' || is_array($answer)) {
                        continue; // Skip unanswered items and complex values
                    }
                    $rows[] = [
                        'section' => $section,
                        'question' => rtrim(trim((string)($field['label'] ?? '')), " :\t"),
                        'answer' => (string)$answer,
                    ];
                }
            }
        }

        return $rows;
    }

    /**
     * Gets the questions/answers of the record, from the JSON or, when the
     * JSON is empty, from the record fields themselves.
     * @return array[] List of rows: ['section', 'question', 'answer']
     */
    private function getQuestionsAndAnswers(): array
    {
        $rows = $this->extractQuestionsAndAnswers($this->getDecodedSubmissionData());
        if (!empty($rows)) {
            return $rows;
        }

        // Fallback for manually created records: use the name and description
        if (!empty($this->bean->name) || !empty($this->bean->description)) {
            $rows[] = [
                'section' => (string)($this->bean->name ?? ''),
                'question' => 'Descripción',
                'answer' => (string)($this->bean->description ?? ''),
            ];
        }

        return $rows;
    }

    /**
     * Gets the header data of the form (first block of the whole submission),
     * taken from the record JSON or, in partial records, from the JSON of the
     * related main score.
     * @return array Header data indexed by JSON field name (nom, expedient, ...)
     */
    private function getHeaderData(): array
    {
        $decoded = $this->getDecodedSubmissionData();

        // Per-block JSON (partial): the header data is in the related main record
        $mainBean = null;
        if (!isset($decoded['blocks']) && !empty($this->bean->parent_id)) {
            $mainBean = BeanFactory::getBean('stic_Assessments_Scores', $this->bean->parent_id);
        }
        if ($mainBean !== null && !empty($mainBean->id)) {
            $decoded = json_decode(html_entity_decode((string)($mainBean->submission_data ?? ''), ENT_QUOTES, 'UTF-8'), true);
            if (!is_array($decoded)) {
                return [];
            }
        }

        // Find the header fields in the first block that has them
        $header = [];
        $blocks = [];
        if (isset($decoded['blocks']) && is_array($decoded['blocks'])) {
            $blocks = $decoded['blocks'];
        } elseif (isset($decoded['block']) && is_array($decoded['block'])) {
            $blocks = [$decoded['block']];
        }

        foreach ($blocks as $block) {
            $fields = $block['fields'] ?? [];
            foreach (self::HEADER_FIELDS as $jsonName => $variable) {
                if (isset($header[$jsonName]) && $header[$jsonName] !== '') {
                    continue;
                }
                if (isset($fields[$jsonName])) {
                    $value = $fields[$jsonName]['value'] ?? '';
                    $header[$jsonName] = is_array($value) ? implode(', ', $value) : (string)$value;
                }
            }
        }

        return $header;
    }
}
