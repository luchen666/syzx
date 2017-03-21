<?php
define("APP_PATH", realpath(dirname(__FILE__) . "/.."));
define("MODELS_PATH", realpath(dirname(__FILE__) . "/../Application/Models"));

$app  = new Yaf_Application(APP_PATH . "/conf/application.ini","develop");
//$app->getDispatcher()->catchException(true);
$app->bootstrap()->run();
?>