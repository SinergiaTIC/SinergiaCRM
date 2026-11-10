<?php
// STIC-Custom AAES - 20261109 - Attendance ticker labels, Spanish (IMPL-AAES-001, Fase B)
// END STIC-Custom AAES

// Screen titles and chrome
$app_strings['LBL_AAES_TICKER_TITLE'] = 'Pasador de asistencias';
$app_strings['LBL_AAES_TICKER_HOME'] = 'Inicio';
$app_strings['LBL_AAES_TICKER_BACK_MODULE'] = 'Volver a Asistencias';
$app_strings['LBL_AAES_TICKER_CHANGE_PROJECT'] = 'Cambiar de proyecto';
$app_strings['LBL_AAES_TICKER_S1_TITLE'] = 'Selector de proyecto';
$app_strings['LBL_AAES_TICKER_S2_TITLE'] = 'Sesiones del proyecto';
$app_strings['LBL_AAES_TICKER_S3_TITLE'] = 'Pasador de la sesión';

// Screen 1: project selector (window = AAES_TICKER_SEARCH_DAYS setting)
$app_strings['LBL_AAES_TICKER_S1_SUBTITLE'] = 'Proyectos con sesiones de hoy o de los últimos %s días';
$app_strings['LBL_AAES_TICKER_NO_PROJECTS'] = 'Ningún proyecto con actividad en curso.';
$app_strings['LBL_AAES_TICKER_SEARCH_SESSIONS'] = 'Ir al buscador de sesiones';
$app_strings['LBL_AAES_TICKER_TODAY'] = 'Hoy';
$app_strings['LBL_AAES_TICKER_LAST_DAYS'] = 'Últimos %s días';

// Screen 2: sessions + searcher
$app_strings['LBL_AAES_TICKER_S2_PROJECT'] = 'Sesiones del proyecto: %s';
$app_strings['LBL_AAES_TICKER_ALL_PROJECTS'] = 'Todos los proyectos';
$app_strings['LBL_AAES_TICKER_TODAY_SESSIONS'] = 'Sesiones de hoy';
$app_strings['LBL_AAES_TICKER_NO_TODAY'] = 'Ninguna sesión hoy.';
$app_strings['LBL_AAES_TICKER_SEARCHER'] = 'Buscador de sesiones de los últimos %s días';
$app_strings['LBL_AAES_TICKER_SEARCH_FROM'] = 'Desde';
$app_strings['LBL_AAES_TICKER_SEARCH_TO'] = 'Hasta';
$app_strings['LBL_AAES_TICKER_SEARCH_Q'] = 'Texto (sesión o evento)';
$app_strings['LBL_AAES_TICKER_SEARCH_EVENT'] = 'Evento';
$app_strings['LBL_AAES_TICKER_SEARCH_ALL_EVENTS'] = 'Todos';
$app_strings['LBL_AAES_TICKER_SEARCH_BUTTON'] = 'Buscar';
$app_strings['LBL_AAES_TICKER_ATTENDEES'] = 'Asistentes: %s/%s';
$app_strings['LBL_AAES_TICKER_NO_RESULTS'] = 'Ninguna sesión con esos filtros.';
$app_strings['LBL_AAES_TICKER_PAGE_OF'] = 'Página %s de %s';
$app_strings['LBL_AAES_TICKER_NEXT'] = 'Siguiente';
$app_strings['LBL_AAES_TICKER_PREV'] = 'Anterior';

// Screen 3: roster + actions
$app_strings['LBL_AAES_TICKER_BACK_SESSIONS'] = 'Volver a las sesiones';
$app_strings['LBL_AAES_TICKER_LOCAL_SEARCH'] = 'Filtrar por nombre...';
$app_strings['LBL_AAES_TICKER_ENROLLED_SECTION'] = 'Personas inscritas';
$app_strings['LBL_AAES_TICKER_OUT_SECTION'] = 'Fuera del censo';
$app_strings['LBL_AAES_TICKER_OUT_SHORT'] = 'Fuera censo';
$app_strings['LBL_AAES_TICKER_PENDING'] = 'Pendiente';
$app_strings['LBL_AAES_TICKER_CAME'] = 'Ha venido';
$app_strings['LBL_AAES_TICKER_MARK_ALL'] = 'Han venido todos';
$app_strings['LBL_AAES_TICKER_MARK_ALL_CONFIRM'] = 'Se marcarán %s personas como «Sí». ¿Continuar?';
$app_strings['LBL_AAES_TICKER_MARK_ALL_OK'] = 'Marcadas %s personas como «Sí».';
$app_strings['LBL_AAES_TICKER_MARK_OK'] = 'Asistencia registrada: «Sí».';
$app_strings['LBL_AAES_TICKER_STATUS_OK'] = 'Estado actualizado: «%s».';
$app_strings['LBL_AAES_TICKER_OUT_OK'] = 'Asistencia registrada para una persona fuera del censo. Su inscripción no cambia de estado.';
$app_strings['LBL_AAES_TICKER_UNDO'] = 'Deshacer';
$app_strings['LBL_AAES_TICKER_UNDO_CONFIRM'] = '¿Deshacer el marcaje de %s?';
$app_strings['LBL_AAES_TICKER_UNDO_OK'] = 'Asistencia borrada.';
$app_strings['LBL_AAES_TICKER_UNDO_CLEAR_OK'] = 'Marcaje deshecho: la asistencia vuelve a estar sin estado.';
$app_strings['LBL_AAES_TICKER_C_ENROLLED'] = 'Inscritos: %s';
$app_strings['LBL_AAES_TICKER_C_MARKED'] = 'Marcados: %s';
$app_strings['LBL_AAES_TICKER_C_OUT'] = 'Fuera del censo: %s';

// Attendance statuses: the four DEFAULT SinergiaCRM statuses (DD-2)
$app_strings['LBL_AAES_TICKER_ST_YES'] = 'Sí';
$app_strings['LBL_AAES_TICKER_ST_PARTIAL'] = 'Parcial';
$app_strings['LBL_AAES_TICKER_ST_NO_JUST'] = 'No, justificado';
$app_strings['LBL_AAES_TICKER_ST_NO_JUST_S'] = 'No just.';
$app_strings['LBL_AAES_TICKER_ST_NO_UNJUST'] = 'No, injustificado';
$app_strings['LBL_AAES_TICKER_ST_NO_UNJUST_S'] = 'No injust.';

// Errors / AJAX / security
$app_strings['LBL_AAES_TICKER_NOT_FOUND'] = 'Sesión o registro no encontrado.';
$app_strings['LBL_AAES_TICKER_FUTURE'] = 'Esta sesión aún no se ha celebrado: no se puede pasar.';
$app_strings['LBL_AAES_TICKER_INVALID_STATUS'] = 'Estado no válido.';
$app_strings['LBL_AAES_TICKER_DISABLED_DAY'] = 'Esta persona tiene este día de la semana desactivado en su inscripción.';
$app_strings['LBL_AAES_TICKER_UNKNOWN_OP'] = 'Pantalla desconocida.';
$app_strings['LBL_AAES_TICKER_UNKNOWN_ACTION'] = 'Acción desconocida.';
$app_strings['LBL_AAES_TICKER_CSRF'] = 'La solicitud no es válida. Recargue la página y vuelva a intentarlo.';
$app_strings['LBL_AAES_TICKER_AJAX_ERROR'] = 'No se ha podido completar la operación. Vuelva a intentarlo.';
$app_strings['LBL_AAES_TICKER_PING_OK'] = 'El Pasador responde correctamente.';
