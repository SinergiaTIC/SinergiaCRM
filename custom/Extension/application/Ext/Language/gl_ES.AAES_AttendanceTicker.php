<?php
// STIC-Custom AAES - 20261109 - Attendance ticker labels, Galician (IMPL-AAES-001, Fase C)
// END STIC-Custom AAES

// Screen titles and chrome
$app_strings['LBL_AAES_TICKER_TITLE'] = 'Relevo de asistencias';
$app_strings['LBL_AAES_TICKER_HOME'] = 'Inicio';
$app_strings['LBL_AAES_TICKER_BACK_MODULE'] = 'Voltar a Asistencias';
$app_strings['LBL_AAES_TICKER_CHANGE_PROJECT'] = 'Cambiar de proxecto';
$app_strings['LBL_AAES_TICKER_S1_TITLE'] = 'Selector de proxecto';
$app_strings['LBL_AAES_TICKER_S2_TITLE'] = 'Sesións do proxecto';
$app_strings['LBL_AAES_TICKER_S3_TITLE'] = 'Relevo da sesión';

// Screen 1: project selector (window = AAES_TICKER_SEARCH_DAYS setting)
$app_strings['LBL_AAES_TICKER_S1_SUBTITLE'] = 'Proxectos con sesións hoxe ou nos últimos %s días';
$app_strings['LBL_AAES_TICKER_NO_PROJECTS'] = 'Ningún proxecto con actividade en curso.';
$app_strings['LBL_AAES_TICKER_SEARCH_SESSIONS'] = 'Ir ao buscador de sesións';
$app_strings['LBL_AAES_TICKER_TODAY'] = 'Hoxe';
$app_strings['LBL_AAES_TICKER_LAST_DAYS'] = 'Últimos %s días';

// Screen 2: sessions + searcher
$app_strings['LBL_AAES_TICKER_S2_PROJECT'] = 'Sesións do proxecto: %s';
$app_strings['LBL_AAES_TICKER_ALL_PROJECTS'] = 'Todos os proxectos';
$app_strings['LBL_AAES_TICKER_TODAY_SESSIONS'] = 'Sesións de hoxe';
$app_strings['LBL_AAES_TICKER_NO_TODAY'] = 'Ningunha sesión hoxe.';
$app_strings['LBL_AAES_TICKER_SEARCHER'] = 'Buscador de sesións dos últimos %s días';
$app_strings['LBL_AAES_TICKER_SEARCH_FROM'] = 'Desde';
$app_strings['LBL_AAES_TICKER_SEARCH_TO'] = 'Ata';
$app_strings['LBL_AAES_TICKER_SEARCH_Q'] = 'Texto (sesión ou evento)';
$app_strings['LBL_AAES_TICKER_SEARCH_EVENT'] = 'Evento';
$app_strings['LBL_AAES_TICKER_SEARCH_ALL_EVENTS'] = 'Todos';
$app_strings['LBL_AAES_TICKER_SEARCH_BUTTON'] = 'Buscar';
$app_strings['LBL_AAES_TICKER_ATTENDEES'] = 'Asistentes: %s/%s';
$app_strings['LBL_AAES_TICKER_NO_RESULTS'] = 'Ningunha sesión con eses filtros.';
$app_strings['LBL_AAES_TICKER_PAGE_OF'] = 'Páxina %s de %s';
$app_strings['LBL_AAES_TICKER_NEXT'] = 'Seguinte';
$app_strings['LBL_AAES_TICKER_PREV'] = 'Anterior';

// Screen 3: roster + actions
$app_strings['LBL_AAES_TICKER_BACK_SESSIONS'] = 'Voltar ás sesións';
$app_strings['LBL_AAES_TICKER_LOCAL_SEARCH'] = 'Filtrar por nome...';
$app_strings['LBL_AAES_TICKER_ENROLLED_SECTION'] = 'Persoas inscritas';
$app_strings['LBL_AAES_TICKER_OUT_SECTION'] = 'Fóra do censo';
$app_strings['LBL_AAES_TICKER_OUT_SHORT'] = 'Fóra censo';
$app_strings['LBL_AAES_TICKER_PENDING'] = 'Pendente';
$app_strings['LBL_AAES_TICKER_CAME'] = 'Veu';
$app_strings['LBL_AAES_TICKER_MARK_ALL'] = 'Veñen todos';
$app_strings['LBL_AAES_TICKER_MARK_ALL_CONFIRM'] = 'Marcaranse %s persoas como «Si». Queres continuar?';
$app_strings['LBL_AAES_TICKER_MARK_ALL_OK'] = 'Marcadas %s persoas como «Si».';
$app_strings['LBL_AAES_TICKER_MARK_OK'] = 'Asistencia rexistrada: «Si».';
$app_strings['LBL_AAES_TICKER_STATUS_OK'] = 'Estado actualizado: «%s».';
$app_strings['LBL_AAES_TICKER_OUT_OK'] = 'Asistencia rexistrada para unha persoa fóra do censo. A súa inscrición non cambia de estado.';
$app_strings['LBL_AAES_TICKER_UNDO'] = 'Desfacer';
$app_strings['LBL_AAES_TICKER_UNDO_CONFIRM'] = 'Desfacer o marcado de %s?';
$app_strings['LBL_AAES_TICKER_UNDO_OK'] = 'Asistencia borrada.';
$app_strings['LBL_AAES_TICKER_UNDO_CLEAR_OK'] = 'Marcado desfeito: a asistencia queda sen estado.';
$app_strings['LBL_AAES_TICKER_C_ENROLLED'] = 'Inscritos: %s';
$app_strings['LBL_AAES_TICKER_C_MARKED'] = 'Marcados: %s';
$app_strings['LBL_AAES_TICKER_C_OUT'] = 'Fóra do censo: %s';

// Attendance statuses: the four DEFAULT SinergiaCRM statuses (DD-2)
$app_strings['LBL_AAES_TICKER_ST_YES'] = 'Si';
$app_strings['LBL_AAES_TICKER_ST_PARTIAL'] = 'Parcial';
$app_strings['LBL_AAES_TICKER_ST_NO_JUST'] = 'Non, xustificado';
$app_strings['LBL_AAES_TICKER_ST_NO_JUST_S'] = 'Non xust.';
$app_strings['LBL_AAES_TICKER_ST_NO_UNJUST'] = 'Non, inxustificado';
$app_strings['LBL_AAES_TICKER_ST_NO_UNJUST_S'] = 'Non inxust.';

// Errors / AJAX / security
$app_strings['LBL_AAES_TICKER_NOT_FOUND'] = 'Sesión ou rexistro non atopado.';
$app_strings['LBL_AAES_TICKER_FUTURE'] = 'Esta sesión aínda non se celebrou: non se pode pasar.';
$app_strings['LBL_AAES_TICKER_INVALID_STATUS'] = 'Estado non válido.';
$app_strings['LBL_AAES_TICKER_DISABLED_DAY'] = 'Esta persoa ten este día da semana desactivado na súa inscrición.';
$app_strings['LBL_AAES_TICKER_UNKNOWN_OP'] = 'Pantalla descoñecida.';
$app_strings['LBL_AAES_TICKER_UNKNOWN_ACTION'] = 'Acción descoñecida.';
$app_strings['LBL_AAES_TICKER_CSRF'] = 'A solicitude non é válida. Recarga a páxina e volve tentalo.';
$app_strings['LBL_AAES_TICKER_AJAX_ERROR'] = 'Non se puido completar a operación. Volve tentalo.';
$app_strings['LBL_AAES_TICKER_PING_OK'] = 'O Relevo responde correctamente.';
