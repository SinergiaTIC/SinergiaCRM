<?php
$manifest = [
    'acceptable_sugar_versions' => ['regex_matches' => ['(.*?)\\.(.*?)\\.(.*?)']],
    'author' => 'STIC-Custom OC',
    'readme' => 'README.md',
    'license' => 'LICENSE.txt',
    'description' => 'Text-to-Speech functionality using Deepgram Aura-2 API for SinergiaCRM',
    'is_uninstallable' => true,
    'published_date' => '2026-07-22',
    'name' => 'TTS Text-to-Speech',
    'type' => 'module',
    'version' => '1.0.0',
    'key' => 'stic',
    'remove_tables' => 'prompt',
];

$installdefs = [
    'id' => 'stic_tts_deepgram',
    'copy' => [
        ['from' => '<basepath>/custom/', 'to' => 'custom/'],
    ],
    'scripts' => [
        'post_install' => '<basepath>/scripts/post_install.php',
        'post_uninstall' => '<basepath>/scripts/post_uninstall.php',
    ],
];
