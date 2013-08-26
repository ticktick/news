<?php

class Base_Config {

    const TYPE_TPL_PATH = 'tpl_path';
    const TYPE_DEFAULT_LAYOUT = 'default_layout';
    const TYPE_DB_SOURCE = 'db_source';
    const TYPE_DB_FILE_PATH = 'db_file_path';
    const TYPE_DB_MYSQL_CONNECTION_PARAMS = 'db_mysql_connection_params';
    const TYPE_DB_MYSQL_CHARSET = 'db_mysql_charset';
    const TYPE_DB_MULTISOURCE = 'db_multisource';
    const TYPE_PROFILER_ENABLED = 'profiler_enabled';

    private $config = array(
        self::TYPE_TPL_PATH => 'App/Templates/',
        self::TYPE_DEFAULT_LAYOUT => 'layout.phtml',
        self::TYPE_DB_SOURCE => 'Base_DB_DriverMultiSource',
        self::TYPE_DB_FILE_PATH => 'db_files',
        self::TYPE_DB_MYSQL_CONNECTION_PARAMS => array(
            'mysql:host=localhost;port=3306;dbname=news',
            'dblogin',
            'dbpass'
        ),
        self::TYPE_DB_MYSQL_CHARSET => 'cp1251',
        self::TYPE_DB_MULTISOURCE => array(
            'read' => 'Base_DB_DriverMysql',
            //'read' => 'Base_DB_DriverFile',
            'write' => array(
                'Base_DB_DriverFile',
                'Base_DB_DriverMysql',
            ),
        ),
        self::TYPE_PROFILER_ENABLED => true,
    );

    public function getConfigSection($section){
        return isset($this->config[$section]) ? $this->config[$section] : array();
    }
}