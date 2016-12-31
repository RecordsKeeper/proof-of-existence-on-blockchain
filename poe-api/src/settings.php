<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],
        'multichain_ip' => '52.90.200.114',
        'multichain_rpc_port' => '9540',
        'multichain_rpc_user' => 'multichainrpc',
        'multichain_rpc_password' => 'EEwTda1ogNMSXnpMV8qWtTobKfZ2EM2618UybDSGvwJ9',

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
    ],
];
