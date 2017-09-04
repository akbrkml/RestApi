<?php
require './libs/NotORM.php';
include_once dirname(__FILE__) . '/Constant.php';
        
$dsn  = DB_METHOD.DB_NAME;
$pdo  = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
$con  = new NotORM($pdo);
