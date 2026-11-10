<?php
// STIC-Custom AAES - 20261109 - Attendance ticker labels, Basque (IMPL-AAES-001, Fase C)
// END STIC-Custom AAES

// Screen titles and chrome
$app_strings['LBL_AAES_TICKER_TITLE'] = 'Asistentzia-pasagailua';
$app_strings['LBL_AAES_TICKER_HOME'] = 'Hasiera';
$app_strings['LBL_AAES_TICKER_BACK_MODULE'] = 'Itzuli Asistentzia modulura';
$app_strings['LBL_AAES_TICKER_CHANGE_PROJECT'] = 'Proiektua aldatu';
$app_strings['LBL_AAES_TICKER_S1_TITLE'] = 'Proiektu-hautatzailea';
$app_strings['LBL_AAES_TICKER_S2_TITLE'] = 'Proiektuaren saioak';
$app_strings['LBL_AAES_TICKER_S3_TITLE'] = 'Saioaren pasagailua';

// Screen 1: project selector (window = AAES_TICKER_SEARCH_DAYS setting)
$app_strings['LBL_AAES_TICKER_S1_SUBTITLE'] = 'Saioak dituzten proiektuak gaur edo azken %s egunetan';
$app_strings['LBL_AAES_TICKER_NO_PROJECTS'] = 'Abian den aktibitaterik gabeko proiekturik ez.';
$app_strings['LBL_AAES_TICKER_SEARCH_SESSIONS'] = 'Saio-bilauntzira joan';
$app_strings['LBL_AAES_TICKER_TODAY'] = 'Gaur';
$app_strings['LBL_AAES_TICKER_LAST_DAYS'] = 'Azken %s egun';

// Screen 2: sessions + searcher
$app_strings['LBL_AAES_TICKER_S2_PROJECT'] = 'Proiektuaren saioak: %s';
$app_strings['LBL_AAES_TICKER_ALL_PROJECTS'] = 'Proiektu guztiak';
$app_strings['LBL_AAES_TICKER_TODAY_SESSIONS'] = 'Gaurko saioak';
$app_strings['LBL_AAES_TICKER_NO_TODAY'] = 'Gaur ez dago saiorik.';
$app_strings['LBL_AAES_TICKER_SEARCHER'] = 'Azken %s egunetako saio-bilauntzia';
$app_strings['LBL_AAES_TICKER_SEARCH_FROM'] = 'Noiztik';
$app_strings['LBL_AAES_TICKER_SEARCH_TO'] = 'Noizeraino';
$app_strings['LBL_AAES_TICKER_SEARCH_Q'] = 'Testua (saioa edo ekitaldia)';
$app_strings['LBL_AAES_TICKER_SEARCH_EVENT'] = 'Ekitaldia';
$app_strings['LBL_AAES_TICKER_SEARCH_ALL_EVENTS'] = 'Guztiak';
$app_strings['LBL_AAES_TICKER_SEARCH_BUTTON'] = 'Bilatu';
$app_strings['LBL_AAES_TICKER_ATTENDEES'] = 'Alerdiak: %s/%s';
$app_strings['LBL_AAES_TICKER_NO_RESULTS'] = 'Iragazki horiekin ez dago saiorik.';
$app_strings['LBL_AAES_TICKER_PAGE_OF'] = '%s. orria, guztira %s';
$app_strings['LBL_AAES_TICKER_NEXT'] = 'Hurrengoa';
$app_strings['LBL_AAES_TICKER_PREV'] = 'Aurrekoa';

// Screen 3: roster + actions
$app_strings['LBL_AAES_TICKER_BACK_SESSIONS'] = 'Itzuli saioetara';
$app_strings['LBL_AAES_TICKER_LOCAL_SEARCH'] = 'Iragazi izenez...';
$app_strings['LBL_AAES_TICKER_ENROLLED_SECTION'] = 'Matrikulatutako pertsonak';
$app_strings['LBL_AAES_TICKER_OUT_SECTION'] = 'Erroldatik kanpo';
$app_strings['LBL_AAES_TICKER_OUT_SHORT'] = 'Errolda kanpo';
$app_strings['LBL_AAES_TICKER_PENDING'] = 'Zain';
$app_strings['LBL_AAES_TICKER_CAME'] = 'Etorri da';
$app_strings['LBL_AAES_TICKER_MARK_ALL'] = 'Denak etorri dira';
$app_strings['LBL_AAES_TICKER_MARK_ALL_CONFIRM'] = '%s pertsona «Bai» bezala markatuko dira. Jarraitu nahi duzu?';
$app_strings['LBL_AAES_TICKER_MARK_ALL_OK'] = '%s pertsona markatu dira «Bai» bezala.';
$app_strings['LBL_AAES_TICKER_MARK_OK'] = 'Asistentzia erregistratua: «Bai».';
$app_strings['LBL_AAES_TICKER_STATUS_OK'] = 'Egoera eguneratu: «%s».';
$app_strings['LBL_AAES_TICKER_OUT_OK'] = 'Asistentzia erregistratua erroldatik kanpoko pertsona batentzat. Bere matrikularen egoera ez da aldatu.';
$app_strings['LBL_AAES_TICKER_UNDO'] = 'Desegin';
$app_strings['LBL_AAES_TICKER_UNDO_CONFIRM'] = '%s-ren markatzea desegin?';
$app_strings['LBL_AAES_TICKER_UNDO_OK'] = 'Asistentzia ezabatua.';
$app_strings['LBL_AAES_TICKER_UNDO_CLEAR_OK'] = 'Markatzea deseginda: asistentzia berriro egoerarik gabe dago.';
$app_strings['LBL_AAES_TICKER_C_ENROLLED'] = 'Matrikulatuak: %s';
$app_strings['LBL_AAES_TICKER_C_MARKED'] = 'Markatuak: %s';
$app_strings['LBL_AAES_TICKER_C_OUT'] = 'Erroldatik kanpo: %s';

// Attendance statuses: the four DEFAULT SinergiaCRM statuses (DD-2)
$app_strings['LBL_AAES_TICKER_ST_YES'] = 'Bai';
$app_strings['LBL_AAES_TICKER_ST_PARTIAL'] = 'Partzialki';
$app_strings['LBL_AAES_TICKER_ST_NO_JUST'] = 'Ez, justifikatuta';
$app_strings['LBL_AAES_TICKER_ST_NO_JUST_S'] = 'Ez just.';
$app_strings['LBL_AAES_TICKER_ST_NO_UNJUST'] = 'Ez, justifikatu gabe';
$app_strings['LBL_AAES_TICKER_ST_NO_UNJUST_S'] = 'Ez just. gabe';

// Errors / AJAX / security
$app_strings['LBL_AAES_TICKER_NOT_FOUND'] = 'Saioa edo erregistroa ez da aurkitu.';
$app_strings['LBL_AAES_TICKER_FUTURE'] = 'Saio hau oraindik ez da ospatu: ezin da pasatu.';
$app_strings['LBL_AAES_TICKER_INVALID_STATUS'] = 'Egoera ez da baliozkoa.';
$app_strings['LBL_AAES_TICKER_DISABLED_DAY'] = 'Pertsona honek asteko egun hau desaktibatuta du bere matrikulan.';
$app_strings['LBL_AAES_TICKER_UNKNOWN_OP'] = 'Pantaila ezezaguna.';
$app_strings['LBL_AAES_TICKER_UNKNOWN_ACTION'] = 'Ekintza ezezaguna.';
$app_strings['LBL_AAES_TICKER_CSRF'] = 'Eskaera ez da baliozkoa. Birkargatu orrialdea eta saiatu berriro.';
$app_strings['LBL_AAES_TICKER_AJAX_ERROR'] = 'Ezin izan da eragiketa osatu. Saiatu berriro.';
$app_strings['LBL_AAES_TICKER_PING_OK'] = 'Pasagailuak behar bezala erantzuten du.';
