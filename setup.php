<?php

$uri = $container['uri'];

$paths = $uri->paths();

$theme = array_shift($paths);

$user       = ['user'];
$blueprints = ['user://blueprints', 'system/blueprints'];
$config     = ['user://config', 'system/config'];
$plugins    = ['user://plugins'];
$plugin     = ['user://plugins'];
$themes     = ['user://themes'];
$cache      = ['cache'];
$log        = ['logs'];
$page       = ['user://pages'];

if ($theme) {
  array_unshift($config, "user://config_{$theme}");

  $container['themes_preview'] = $theme;
} else {
  $container['themes_preview'] = false;
}

/*
$config = ['user://config', 'system/config'];
if (\Tracy\Debugger::detectDebugMode(['127.0.0.1'])) {
    array_unshift($config, 'user://localhost/config');
}
*/

return [
    'environment' => $theme,
    'streams' => [
        'schemes' => [
            'user' => [
                'type' => 'ReadOnlyStream',
                'prefixes' => [
                    '' => $user,
                ]
            ],
            'blueprints' => [
                'type' => 'ReadOnlyStream',
                'prefixes' => [
                    '' => $blueprints,
                ]
            ],
            'config' => [
                'type' => 'ReadOnlyStream',
                'prefixes' => [
                    '' => $config,
                ]
            ],
            'plugins' => [
                'type' => 'ReadOnlyStream',
                'prefixes' => [
                    '' => $plugins,
                ]
            ],
            'plugin' => [
                'type' => 'ReadOnlyStream',
                'prefixes' => [
                    '' => $plugin,
                ]
            ],
            'themes' => [
                'type' => 'ReadOnlyStream',
                'prefixes' => [
                    '' => $themes,
                ]
            ],
            'cache' => [
                'type' => 'Stream',
                'prefixes' => [
                    '' => $cache
                ]
            ],
            'log' => [
                'type' => 'Stream',
                'prefixes' => [
                    '' => $log
                ]
            ]
        ]
    ]
];
