<?php
// STIC-Custom AAES - 20261109 - Module Loader manifest for the AAES Attendance Ticker (IMPL-AAES-001, Fase D)
// Standard Module Loader manifest: copies every ticker file into place (all of
// them live in custom/Extension + custom/include/AAES, nothing else). After
// installing, the entity must run Quick Repair & Rebuild so the installer
// compiles the ActionFileMap and Menus extensions.
// END STIC-Custom AAES

$manifest = array(
    'name' => 'AAES Attendance Ticker pasador de asistencias',
    'description' => 'Pasador de asistencias genérico: vista integrada en el módulo de Asistencias '
        . 'para pasar lista de sesiones desde móvil/tablet (marcado rápido, cambio de estado, '
        . 'masivo "Han venido todos", deshacer y sección "fuera del censo"). '
        . 'Todo en custom/Extension + custom/include/AAES; sin campos ni estados nuevos.',
    'author' => 'SinergiaTIC - AES (Atención a Entidades Socias)',
    'published_date' => '2026-11-09',
    'version' => '1.0.0',
    'type' => 'module',
    'icon' => '',
    'is_uninstallable' => true,
    'types' => array('module'),
    'acceptable_versions' => array('2.13.*'),
    'dependencies' => array(),
);

$installdefs = array(
    'id' => 'AAES_AttendanceTicker_1_0_0',
    'readme' => 'install/README-instalacion.md',
    'copy' => array(
        // Handler: ONE ENTRY PER FILE (per-file copies are robust; a
        // whole-directory copy entry failed on at least one production
        // instance when the package arrived corrupted, and directory copies
        // give poorer diagnostics)
        array('from' => '<basepath>/custom/include/AAES/AttendanceTicker/AAES_AttendanceTickerQueries.php', 'to' => 'custom/include/AAES/AttendanceTicker/AAES_AttendanceTickerQueries.php'),
        array('from' => '<basepath>/custom/include/AAES/AttendanceTicker/AAES_AttendanceTickerActions.php', 'to' => 'custom/include/AAES/AttendanceTicker/AAES_AttendanceTickerActions.php'),
        array('from' => '<basepath>/custom/include/AAES/AttendanceTicker/AAES_AttendanceTickerUtils.php', 'to' => 'custom/include/AAES/AttendanceTicker/AAES_AttendanceTickerUtils.php'),
        array('from' => '<basepath>/custom/include/AAES/AttendanceTicker/AAES_AttendanceTickerView.php', 'to' => 'custom/include/AAES/AttendanceTicker/AAES_AttendanceTickerView.php'),
        // Aplicación: etiquetas de la app (5 idiomas)
        array('from' => '<basepath>/custom/Extension/application/Ext/Language/ca_ES.AAES_AttendanceTicker.php', 'to' => 'custom/Extension/application/Ext/Language/ca_ES.AAES_AttendanceTicker.php'),
        array('from' => '<basepath>/custom/Extension/application/Ext/Language/es_ES.AAES_AttendanceTicker.php', 'to' => 'custom/Extension/application/Ext/Language/es_ES.AAES_AttendanceTicker.php'),
        array('from' => '<basepath>/custom/Extension/application/Ext/Language/en_us.AAES_AttendanceTicker.php', 'to' => 'custom/Extension/application/Ext/Language/en_us.AAES_AttendanceTicker.php'),
        array('from' => '<basepath>/custom/Extension/application/Ext/Language/gl_ES.AAES_AttendanceTicker.php', 'to' => 'custom/Extension/application/Ext/Language/gl_ES.AAES_AttendanceTicker.php'),
        array('from' => '<basepath>/custom/Extension/application/Ext/Language/eu_ES.AAES_AttendanceTicker.php', 'to' => 'custom/Extension/application/Ext/Language/eu_ES.AAES_AttendanceTicker.php'),
        // Módulo: registro de la acción integrada + menú + etiquetas del módulo
        array('from' => '<basepath>/custom/Extension/modules/stic_Attendances/Ext/ActionFileMap/AAES_AttendanceTicker.php', 'to' => 'custom/Extension/modules/stic_Attendances/Ext/ActionFileMap/AAES_AttendanceTicker.php'),
        array('from' => '<basepath>/custom/Extension/modules/stic_Attendances/Ext/Menus/AAES_AttendanceTicker.php', 'to' => 'custom/Extension/modules/stic_Attendances/Ext/Menus/AAES_AttendanceTicker.php'),
        array('from' => '<basepath>/custom/Extension/modules/stic_Attendances/Ext/Language/ca_ES.lang.AAES_AttendanceTicker.php', 'to' => 'custom/Extension/modules/stic_Attendances/Ext/Language/ca_ES.lang.AAES_AttendanceTicker.php'),
        array('from' => '<basepath>/custom/Extension/modules/stic_Attendances/Ext/Language/es_ES.lang.AAES_AttendanceTicker.php', 'to' => 'custom/Extension/modules/stic_Attendances/Ext/Language/es_ES.lang.AAES_AttendanceTicker.php'),
        array('from' => '<basepath>/custom/Extension/modules/stic_Attendances/Ext/Language/en_us.lang.AAES_AttendanceTicker.php', 'to' => 'custom/Extension/modules/stic_Attendances/Ext/Language/en_us.lang.AAES_AttendanceTicker.php'),
        array('from' => '<basepath>/custom/Extension/modules/stic_Attendances/Ext/Language/gl_ES.lang.AAES_AttendanceTicker.php', 'to' => 'custom/Extension/modules/stic_Attendances/Ext/Language/gl_ES.lang.AAES_AttendanceTicker.php'),
        array('from' => '<basepath>/custom/Extension/modules/stic_Attendances/Ext/Language/eu_ES.lang.AAES_AttendanceTicker.php', 'to' => 'custom/Extension/modules/stic_Attendances/Ext/Language/eu_ES.lang.AAES_AttendanceTicker.php'),
    ),
);
