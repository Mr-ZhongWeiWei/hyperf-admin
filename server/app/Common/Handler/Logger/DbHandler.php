<?php

namespace App\Common\Handler\Logger;

use Hyperf\DbConnection\Db;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Handler\FormattedRecord;

/**
 * Class DbHandler
 * @package App\Common\Handler\Logger
 */
class DbHandler extends AbstractProcessingHandler
{

    /**
     * @param array $record
     * @author: Zhong Weiwei
     * @Date: 17:09  2022/4/5
     */
    protected function write(array $record): void
    {
        go(function () use ($record){
            Db::table('logs')->insert([
                'message'   =>  $record['message'],
                'context'   =>  $record['context'] ? json_encode($record['context'], JSON_UNESCAPED_UNICODE) : '',
                'level'     =>  $record['level'],
                'level_name'=>  $record['level_name'],
                'channel'   =>  $record['channel'],
                'writetime' =>  $record['datetime']->format('Y-m-d H:i:s')
            ]);
        });
    }
}
