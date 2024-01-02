<?php

class SurveyQuestionResponsesUtils {
    /**
     * It returns the DateTime format of the user needed for the MySQL function DATE_FORMAT
     *
     * @return date
     */
    static function getDateTimeFormat() {
        global $current_user, $timedate;

        $time_format = $timedate->getCalFormat($timedate->get_time_format($current_user));
        $date_format = $timedate->getCalFormat($timedate->get_date_format($current_user));
        $time_separator = ":";
        $match = array();
        if (preg_match('/\d+([^\d])\d+([^\d]*)/s', $time_format, $match)) {
            $time_separator = $match[1];
        }
        if (!isset($match[2]) || $match[2] == '') {
            return $date_format . ' ' . "%H" . $time_separator . "%i";
        } else {
            $pm = $match[2] == "pm" ? "%P" : "%p";
            return $date_format . ' ' . "%H" . $time_separator . "%i" . $pm;
        }
    }
    
    /**
     * It returns the Date format of the user needed for the MySQL function DATE_FORMAT
     *
     * @return date
     */
    static function getDateFormat() {
        global $current_user, $timedate;

        $date_format = $timedate->getCalFormat($timedate->get_date_format($current_user));
        return $date_format;
    }
}
