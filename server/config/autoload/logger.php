<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

$appEnv    =   env('APP_ENV','dev');
if ($appEnv == 'dev'){
    $handle =   [
        'handler' => [
            'class' => Monolog\Handler\StreamHandler::class,
            'constructor' => [
                'stream' => 'php://stdout',
            ],
        ],
        'formatter' => [
            'class' => Monolog\Formatter\LineFormatter::class,
            'constructor' => [
                'format' => null,
                'allowInlineLineBreaks' => true,
                'includeStacktraces' => true,
            ],
        ],
    ];
}else{
    $handle =   [
        'handlers'=>    [
            [
                'class' => Monolog\Handler\StreamHandler::class,
                'constructor' => [
                    'stream' => BASE_PATH . '/runtime/logs/hyperf-info.log',
                    'level' => Monolog\Logger::INFO,
                ],
                'formatter' => [
                    'class' => Monolog\Formatter\LineFormatter::class,
                    'constructor' => [
                        'format' => null,
                        'dateFormat' => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ],
//            [
//                'class' => Monolog\Handler\StreamHandler::class,
//                'constructor' => [
//                    'stream' => BASE_PATH . '/runtime/logs/hyperf-debug.log',
//                    'level' => Monolog\Logger::DEBUG,
//                ],
//                'formatter' => [
//                    'class' => Monolog\Formatter\LineFormatter::class,
//                    'constructor' => [
//                        'format' => null,
//                        'dateFormat' => 'Y-m-d H:i:s',
//                        'allowInlineLineBreaks' => true,
//                    ],
//                ],
//            ],
            [
                'class' => Monolog\Handler\StreamHandler::class,
                'constructor' => [
                    'stream' => BASE_PATH . '/runtime/logs/hyperf-error.log',
                    'level' => Monolog\Logger::ERROR,
                ],
                'formatter' => [
                    'class' => Monolog\Formatter\LineFormatter::class,
                    'constructor' => [
                        'format' => null,
                        'dateFormat' => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ],
        ]
    ];
}
return [
    'default' => $handle,
    'db' => [
        'handler' => [
            'class' => App\Common\Handler\Logger\DbHandler::class,
            'constructor' => [],
        ],
        'formatter' => [
            'class' => Monolog\Formatter\JsonFormatter::class,
            'constructor' => [
                'format' => null,
                'dateFormat' => 'Y-m-d H:i:s',
                'allowInlineLineBreaks' => true,
                'batchMode' => Monolog\Formatter\JsonFormatter::BATCH_MODE_JSON,
                'appendNewline' => true,
            ],
        ],
    ],
];
