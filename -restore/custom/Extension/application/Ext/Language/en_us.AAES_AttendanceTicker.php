<?php
// STIC-Custom AAES - 20261109 - Attendance ticker labels, English (IMPL-AAES-001, Fase C)
// END STIC-Custom AAES

// Screen titles and chrome
$app_strings['LBL_AAES_TICKER_TITLE'] = 'Attendance Ticker';
$app_strings['LBL_AAES_TICKER_HOME'] = 'Home';
$app_strings['LBL_AAES_TICKER_BACK_MODULE'] = 'Back to Attendances';
$app_strings['LBL_AAES_TICKER_CHANGE_PROJECT'] = 'Change project';
$app_strings['LBL_AAES_TICKER_S1_TITLE'] = 'Project selector';
$app_strings['LBL_AAES_TICKER_S2_TITLE'] = 'Project sessions';
$app_strings['LBL_AAES_TICKER_S3_TITLE'] = 'Session roster';

// Screen 1: project selector (window = AAES_TICKER_SEARCH_DAYS setting)
$app_strings['LBL_AAES_TICKER_S1_SUBTITLE'] = 'Projects with sessions today or in the last %s days';
$app_strings['LBL_AAES_TICKER_NO_PROJECTS'] = 'No projects with activity in progress.';
$app_strings['LBL_AAES_TICKER_SEARCH_SESSIONS'] = 'Go to the session searcher';
$app_strings['LBL_AAES_TICKER_TODAY'] = 'Today';
$app_strings['LBL_AAES_TICKER_LAST_DAYS'] = 'Last %s days';

// Screen 2: sessions + searcher
$app_strings['LBL_AAES_TICKER_S2_PROJECT'] = 'Project sessions: %s';
$app_strings['LBL_AAES_TICKER_ALL_PROJECTS'] = 'All projects';
$app_strings['LBL_AAES_TICKER_TODAY_SESSIONS'] = "Today's sessions";
$app_strings['LBL_AAES_TICKER_NO_TODAY'] = 'No sessions today.';
$app_strings['LBL_AAES_TICKER_SEARCHER'] = 'Session searcher (last %s days)';
$app_strings['LBL_AAES_TICKER_SEARCH_FROM'] = 'From';
$app_strings['LBL_AAES_TICKER_SEARCH_TO'] = 'To';
$app_strings['LBL_AAES_TICKER_SEARCH_Q'] = 'Text (session or event)';
$app_strings['LBL_AAES_TICKER_SEARCH_EVENT'] = 'Event';
$app_strings['LBL_AAES_TICKER_SEARCH_ALL_EVENTS'] = 'All';
$app_strings['LBL_AAES_TICKER_SEARCH_BUTTON'] = 'Search';
$app_strings['LBL_AAES_TICKER_ATTENDEES'] = 'Attendees: %s/%s';
$app_strings['LBL_AAES_TICKER_NO_RESULTS'] = 'No sessions with these filters.';
$app_strings['LBL_AAES_TICKER_PAGE_OF'] = 'Page %s of %s';
$app_strings['LBL_AAES_TICKER_NEXT'] = 'Next';
$app_strings['LBL_AAES_TICKER_PREV'] = 'Previous';

// Screen 3: roster + actions
$app_strings['LBL_AAES_TICKER_BACK_SESSIONS'] = 'Back to sessions';
$app_strings['LBL_AAES_TICKER_LOCAL_SEARCH'] = 'Filter by name...';
$app_strings['LBL_AAES_TICKER_ENROLLED_SECTION'] = 'Enrolled people';
$app_strings['LBL_AAES_TICKER_OUT_SECTION'] = 'Out of census';
$app_strings['LBL_AAES_TICKER_OUT_SHORT'] = 'Out cens';
$app_strings['LBL_AAES_TICKER_PENDING'] = 'Pending';
$app_strings['LBL_AAES_TICKER_CAME'] = 'Came';
$app_strings['LBL_AAES_TICKER_MARK_ALL'] = 'All came';
$app_strings['LBL_AAES_TICKER_MARK_ALL_CONFIRM'] = '%s people will be marked as «Yes». Continue?';
$app_strings['LBL_AAES_TICKER_MARK_ALL_OK'] = '%s people marked as «Yes».';
$app_strings['LBL_AAES_TICKER_MARK_OK'] = 'Attendance registered: «Yes».';
$app_strings['LBL_AAES_TICKER_STATUS_OK'] = 'Status updated: «%s».';
$app_strings['LBL_AAES_TICKER_OUT_OK'] = 'Attendance registered for an out-of-census person. Their registration status is unchanged.';
$app_strings['LBL_AAES_TICKER_UNDO'] = 'Undo';
$app_strings['LBL_AAES_TICKER_UNDO_CONFIRM'] = 'Undo the marking of %s?';
$app_strings['LBL_AAES_TICKER_UNDO_OK'] = 'Attendance deleted.';
$app_strings['LBL_AAES_TICKER_UNDO_CLEAR_OK'] = 'Marking undone: the attendance is back to no status.';
$app_strings['LBL_AAES_TICKER_C_ENROLLED'] = 'Enrolled: %s';
$app_strings['LBL_AAES_TICKER_C_MARKED'] = 'Marked: %s';
$app_strings['LBL_AAES_TICKER_C_OUT'] = 'Out of census: %s';

// Attendance statuses: the four DEFAULT SinergiaCRM statuses (DD-2)
$app_strings['LBL_AAES_TICKER_ST_YES'] = 'Yes';
$app_strings['LBL_AAES_TICKER_ST_PARTIAL'] = 'Partial';
$app_strings['LBL_AAES_TICKER_ST_NO_JUST'] = 'No, excused';
$app_strings['LBL_AAES_TICKER_ST_NO_JUST_S'] = 'No exc.';
$app_strings['LBL_AAES_TICKER_ST_NO_UNJUST'] = 'No, unexcused';
$app_strings['LBL_AAES_TICKER_ST_NO_UNJUST_S'] = 'No unexc.';

// Errors / AJAX / security
$app_strings['LBL_AAES_TICKER_NOT_FOUND'] = 'Session or registration not found.';
$app_strings['LBL_AAES_TICKER_FUTURE'] = 'This session has not taken place yet: it cannot be passed.';
$app_strings['LBL_AAES_TICKER_INVALID_STATUS'] = 'Invalid status.';
$app_strings['LBL_AAES_TICKER_DISABLED_DAY'] = 'This person has this weekday disabled in their registration.';
$app_strings['LBL_AAES_TICKER_UNKNOWN_OP'] = 'Unknown screen.';
$app_strings['LBL_AAES_TICKER_UNKNOWN_ACTION'] = 'Unknown action.';
$app_strings['LBL_AAES_TICKER_CSRF'] = 'Invalid request. Reload the page and try again.';
$app_strings['LBL_AAES_TICKER_AJAX_ERROR'] = 'The operation could not be completed. Try again.';
$app_strings['LBL_AAES_TICKER_PING_OK'] = 'The Ticker responds correctly.';
