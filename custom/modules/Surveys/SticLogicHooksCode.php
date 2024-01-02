<?php

class SurveysLogicHooks
{

    public function after_save(&$bean, $event, $arguments)
    {

        // If mass duplicate
        if ($_REQUEST['mass_duplicate']) {
            global $db;
            include_once 'SticInclude/Utils.php';

            // *****************************************
            // 1) Duplicate related SurveyQuestions records
            $surveyQuestionsQuery = "SELECT
                                    id
                                FROM
                                    surveyquestions
                                WHERE
                                    deleted = 0
                                AND survey_id='{$bean->fromId}'";

            $surveyQuestionsResults = $db->query($surveyQuestionsQuery);

            while ($rowQuestions = $db->fetchByAssoc($surveyQuestionsResults)) {

                // Change the parent_id field to point to the new bean id
                $surveyQuestionsChanges = ['survey_id' => $bean->id];
                SticUtils::duplicateBeanRecord('SurveyQuestions', $rowQuestions['id'], $surveyQuestionsChanges);

                // *****************************************
                // 2) For each SurveyQuestion, duplicate related SurveQuestionOptions records
                $surveyQuestionOptionsQuery = "SELECT
                                    id
                                FROM
                                    surveyquestionoptions
                                WHERE
                                    deleted = 0
                                    AND survey_question_id = '{$rowQuestions['id']}'
                                    ";

                $surveyQuestionOptionsResults = $db->query($surveyQuestionOptionsQuery);

                while ($surveyQuestionOptionsRow = $db->fetchByAssoc($surveyQuestionOptionsResults)) {
                    // Change the survey_question_id field to point to the new bean id
                    $surveyQuestionOptionsChanges = ['survey_question_id' => $rowQuestions['id']];
                    SticUtils::duplicateBeanRecord('SurveyQuestionOptions', $surveyQuestionOptionsRow['id'], $surveyQuestionOptionsChanges);
                }
            }
        }
    }
}
