<?php

return array(
    'redis' => array(
        'host' => '127.0.0.1',
        'username' => '',
        'password' => '',
        'database' => 'db',
        'port' => 6379,
        'queryLimit' => 5
    ),
    'mongodb' => array(
        'connection_string'=> 'mongodb://127.0.0.1:27017',
        'host' => 'localhost',
        'username' => '',
        'password' => '',
        'database' => 'db',
        'defaultCollection' => 'tasks'
    )
);
