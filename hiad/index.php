<?php
// change the following paths if necessary
$yii=dirname(__FILE__).'/../../framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';


// remove the following line when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',false);
function p($arr){
	echo '<pre>'.print_r($arr,true);
}
function to_array($obj){
    $result=array();
        foreach($obj as $model){
        $result[]=$model->attributes;
    }
    return $result;
}
require_once($yii);
Yii::createWebApplication($config)->run();
