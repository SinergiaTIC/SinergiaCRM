<?php
// STIC-Custom AAES - 20261109 - Attendance ticker labels, Catalan (IMPL-AAES-001, Fase B)
// END STIC-Custom AAES

// Screen titles and chrome
$app_strings['LBL_AAES_TICKER_TITLE'] = 'Pasador d\'assistències';
$app_strings['LBL_AAES_TICKER_HOME'] = 'Inici';
$app_strings['LBL_AAES_TICKER_BACK_MODULE'] = 'Tornar a Assistències';
$app_strings['LBL_AAES_TICKER_CHANGE_PROJECT'] = 'Canviar de projecte';
$app_strings['LBL_AAES_TICKER_S1_TITLE'] = 'Selector de projecte';
$app_strings['LBL_AAES_TICKER_S2_TITLE'] = 'Sessions del projecte';
$app_strings['LBL_AAES_TICKER_S3_TITLE'] = 'Pasador de la sessió';

// Screen 1: project selector (window = AAES_TICKER_SEARCH_DAYS setting)
$app_strings['LBL_AAES_TICKER_S1_SUBTITLE'] = 'Projectes amb sessions avui o els últims %s dies';
$app_strings['LBL_AAES_TICKER_NO_PROJECTS'] = 'Cap projecte amb activitat en curs.';
$app_strings['LBL_AAES_TICKER_SEARCH_SESSIONS'] = 'Anar al cercador de sessions';
$app_strings['LBL_AAES_TICKER_TODAY'] = 'Avui';
$app_strings['LBL_AAES_TICKER_LAST_DAYS'] = 'Últims %s dies';

// Screen 2: sessions + searcher
$app_strings['LBL_AAES_TICKER_S2_PROJECT'] = 'Sessions del projecte: %s';
$app_strings['LBL_AAES_TICKER_ALL_PROJECTS'] = 'Tots els projectes';
$app_strings['LBL_AAES_TICKER_TODAY_SESSIONS'] = 'Sessions d\'avui';
$app_strings['LBL_AAES_TICKER_NO_TODAY'] = 'Cap sessió avui.';
$app_strings['LBL_AAES_TICKER_SEARCHER'] = 'Cercador de sessions dels últims %s dies';
$app_strings['LBL_AAES_TICKER_SEARCH_FROM'] = 'Des de';
$app_strings['LBL_AAES_TICKER_SEARCH_TO'] = 'Fins a';
$app_strings['LBL_AAES_TICKER_SEARCH_Q'] = 'Text (sessió o esdeveniment)';
$app_strings['LBL_AAES_TICKER_SEARCH_EVENT'] = 'Esdeveniment';
$app_strings['LBL_AAES_TICKER_SEARCH_ALL_EVENTS'] = 'Tots';
$app_strings['LBL_AAES_TICKER_SEARCH_BUTTON'] = 'Cercar';
$app_strings['LBL_AAES_TICKER_ATTENDEES'] = 'Assistents: %s/%s';
$app_strings['LBL_AAES_TICKER_NO_RESULTS'] = 'Cap sessió amb aquests filtres.';
$app_strings['LBL_AAES_TICKER_PAGE_OF'] = 'Pàgina %s de %s';
$app_strings['LBL_AAES_TICKER_NEXT'] = 'Següent';
$app_strings['LBL_AAES_TICKER_PREV'] = 'Anterior';

// Screen 3: roster + actions
$app_strings['LBL_AAES_TICKER_BACK_SESSIONS'] = 'Tornar a les sessions';
$app_strings['LBL_AAES_TICKER_LOCAL_SEARCH'] = 'Filtrar per nom...';
$app_strings['LBL_AAES_TICKER_ENROLLED_SECTION'] = 'Persones inscrites';
$app_strings['LBL_AAES_TICKER_OUT_SECTION'] = 'Fora del cens';
$app_strings['LBL_AAES_TICKER_OUT_SHORT'] = 'Fora cens';
$app_strings['LBL_AAES_TICKER_PENDING'] = 'Pendent';
$app_strings['LBL_AAES_TICKER_CAME'] = 'Ha vingut';
$app_strings['LBL_AAES_TICKER_MARK_ALL'] = 'Han vingut tots';
$app_strings['LBL_AAES_TICKER_MARK_ALL_CONFIRM'] = 'Es marcaran %s persones com a «Sí». Voleu continuar?';
$app_strings['LBL_AAES_TICKER_MARK_ALL_OK'] = 'Marcades %s persones com a «Sí».';
$app_strings['LBL_AAES_TICKER_MARK_OK'] = 'Assistència registrada: «Sí».';
$app_strings['LBL_AAES_TICKER_STATUS_OK'] = 'Estat actualitzat: «%s».';
$app_strings['LBL_AAES_TICKER_OUT_OK'] = 'Assistència registrada per a una persona fora del cens. La seva inscripció no canvia d\'estat.';
$app_strings['LBL_AAES_TICKER_UNDO'] = 'Desfer';
$app_strings['LBL_AAES_TICKER_UNDO_CONFIRM'] = 'Desfer el marcatge de %s?';
$app_strings['LBL_AAES_TICKER_UNDO_OK'] = 'Assistència esborrada.';
$app_strings['LBL_AAES_TICKER_UNDO_CLEAR_OK'] = 'Marcatge desfet: l\'assistència torna a estar sense estat.';
$app_strings['LBL_AAES_TICKER_C_ENROLLED'] = 'Inscrits: %s';
$app_strings['LBL_AAES_TICKER_C_MARKED'] = 'Marcats: %s';
$app_strings['LBL_AAES_TICKER_C_OUT'] = 'Fora del cens: %s';

// Attendance statuses: the four DEFAULT SinergiaCRM statuses (DD-2)
$app_strings['LBL_AAES_TICKER_ST_YES'] = 'Sí';
$app_strings['LBL_AAES_TICKER_ST_PARTIAL'] = 'Parcial';
$app_strings['LBL_AAES_TICKER_ST_NO_JUST'] = 'No, justificat';
$app_strings['LBL_AAES_TICKER_ST_NO_JUST_S'] = 'No just.';
$app_strings['LBL_AAES_TICKER_ST_NO_UNJUST'] = 'No, injustificat';
$app_strings['LBL_AAES_TICKER_ST_NO_UNJUST_S'] = 'No injust.';

// Errors / AJAX / security
$app_strings['LBL_AAES_TICKER_NOT_FOUND'] = 'Sessió o registre no trobat.';
$app_strings['LBL_AAES_TICKER_FUTURE'] = 'Aquesta sessió encara no s\'ha celebrat: no es pot passar.';
$app_strings['LBL_AAES_TICKER_INVALID_STATUS'] = 'Estat no vàlid.';
$app_strings['LBL_AAES_TICKER_DISABLED_DAY'] = 'Aquesta persona té aquest dia de la setmana desactivat en la seva inscripció.';
$app_strings['LBL_AAES_TICKER_UNKNOWN_OP'] = 'Pantalla desconeguda.';
$app_strings['LBL_AAES_TICKER_UNKNOWN_ACTION'] = 'Acció desconeguda.';
$app_strings['LBL_AAES_TICKER_CSRF'] = 'La sol·licitud no és vàlida. Recarregueu la pàgina i torneu-ho a provar.';
$app_strings['LBL_AAES_TICKER_AJAX_ERROR'] = 'No s\'ha pogut completar l\'operació. Torneu-ho a provar.';
$app_strings['LBL_AAES_TICKER_PING_OK'] = 'El Pasador respon correctament.';
