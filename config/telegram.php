<?php


return [
    'bots' => [
        'mybot'    =>  [
            'token'                 => env('TELEGRAM_BOT_TOKEN'),
            'async_requests'        => true,
            'base_bot_url' => 'http://127.0.0.1:8000/telegram',
            // 'commands'    => [
            //         App\Telegram\Commands\StartCommand::class,
            //     ],
            // 'commands' => ['admin', 'help', 'info'],
            'commands' => [
                Telegram\Bot\Commands\HelpCommand::class,
            ],
        ],
        'default' => 'mybot'
    ]
];
