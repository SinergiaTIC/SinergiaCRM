<?php
require_once 'custom/modules/SurveyQuestionResponses/SticUtils.php';
global $app_list_strings, $current_user, $timedate;

$gmtTz = 'UTC';
$userTz = $timedate->userTimezone($current_user);

$mainQuery = 'SELECT
CASE
    WHEN sq.type = "Radio" THEN sqo.name
    WHEN sq.type = "Dropdown" THEN sqo.name
    WHEN sq.type = "Multiselect" THEN sqo.name
    WHEN sq.type = "Matrix" THEN CONCAT(sqo.name, " - ", CASE
                                WHEN sqr.answer = 0 THEN "'.$app_list_strings['surveys_matrix_options'][0].'"
                                WHEN sqr.answer = 1 THEN "'.$app_list_strings['surveys_matrix_options'][1].'"
                                WHEN sqr.answer = 2 THEN "'.$app_list_strings['surveys_matrix_options'][2].'"
                            END)
    WHEN sq.type = "Date" THEN DATE_FORMAT(CONVERT_TZ(sqr.answer_datetime, "'.$gmtTz.'", "'.$userTz.'"), "'.SurveyQuestionResponsesUtils::getDateFormat().'")
    WHEN sq.type = "DateTime" THEN DATE_FORMAT(CONVERT_TZ(sqr.answer_datetime, "'.$gmtTz.'", "'.$userTz.'"), "'.SurveyQuestionResponsesUtils::getDateTimeFormat().'")
    WHEN sq.type = "Checkbox" THEN sqr.answer_bool
    ELSE sqr.answer 
END
FROM
    surveyquestionresponses sqr
JOIN
    surveyquestions sq 
ON
    sq.id = sqr.surveyquestion_id
LEFT JOIN
    surveyquestionoptions_surveyquestionresponses sqosqr 
ON
    sqosqr.surveyq10d4sponses_idb = sqr.id
LEFT JOIN 
    surveyquestionoptions sqo
ON
    sqo.id = sqosqr.surveyq72c7options_ida 
WHERE
    sqr.deleted = 0
    AND {t}.id = sqr.id';

// This field is used in KReporter to unify answers of any type in a single field
// STIC#457
$dictionary['SurveyQuestionResponses']['fields']['answer_unified'] = array(
    'name' => 'answer_unified',
    'vname' => 'LBL_KREPORTER_ANSWER_UNIFIED',
    'type' => 'kreporter',
    'source' => 'non-db',
    'kreporttype' => 'varchar',
    'eval' => array(
        'presentation' => array(
            'eval' => $mainQuery,
        ),
        'selection' => array(
            'contains' => '('.$mainQuery.') LIKE \'%{p1}%\'',
            'notcontains' => '('.$mainQuery.') NOT LIKE \'%{p1}%\'',
            'equals' => '('.$mainQuery.') = \'{p1}\'',
            'notequal' => '('.$mainQuery.') <> \'{p1}\'',
            'starts' => '('.$mainQuery.') LIKE \'{p1}%\'',
            'notstarts' => '('.$mainQuery.') NOT LIKE \'{p1}%\'',
            'isnull' => '('.$mainQuery.') IS NULL',
            'isempty' => '('.$mainQuery.') = \'\'',
            'isemptyornull' => '('.$mainQuery.') = \'\' OR ('.$mainQuery.') IS NULL',
            'isnotempty' => '('.$mainQuery.') <> \'\' AND ('.$mainQuery.') IS NOT NULL',
        ),
    ),
);