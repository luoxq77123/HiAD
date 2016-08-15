<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'HiAD',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.controllers.BaseController',
        'application.components.*',
        'application.helpers.*'
    ),
    'language' => 'zh_cn',
    'timeZone' => 'Asia/Shanghai',
    'charset' => 'utf-8',
    'defaultController' => 'backend',
    
    // application components
    'components' => array(
        'db' => require(dirname(__FILE__).'/db_main.php'),
        'db_stat_site' => require(dirname(__FILE__).'/db_stat_site.php'),
        'db_stat_client' => require(dirname(__FILE__).'/db_stat_client.php'),
        'db_stat_sitemate' => require(dirname(__FILE__).'/db_stat_sitemate.php'),
        'db_stat_clientmate' => require(dirname(__FILE__).'/db_stat_clientmate.php'),
        'db_stat_video' => require(dirname(__FILE__).'/db_stat_video.php'),
        'db_stat_thrid' => require(dirname(__FILE__).'/db_stat_thrid.php'),
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '/' => 'user/login'
            ),
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'trace, info, error, warning, xdebug',
                ),
                array(
                    'class'=>'CWebLogRoute',
                    'levels'=>'trace, info, error, warning, xdebug',
                    'categories'=>'cool.*,system.db.*',
                ),
            ),
        ),
        'session' => array(
            'class' => 'system.web.CHttpSession',
            'autoStart' => true,
            'timeout' => 1800,
            'sessionName' => 'frontend',
            'cookieParams' => array('lifetime' => '0', 'path' => '/', 'domain' => '', 'httponly' => '1'),
            'cookieMode' => 'only',
        ),
        'memcache' => array(
            'class' => 'CMemCache',
            'servers' => array(
                array(
                    'host' => '127.0.0.1',
                    'port' => 11211,
                    'weight' => 80,
                )
            ),
        ),
        'authority' => array(
            'class' => 'application.components.Authority',
        ),
        'oplog' => array(
            'class' => 'application.components.Oplog',
        )
    ),
    'modules' => array('interface','network'),
        // application-level parameters that can be accessed
        // using Yii::app()->params['paramName']
        //'params'=>require(dirname(__FILE__).'/params.php'),
);