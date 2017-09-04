<?php
define("ROOT", __dir__);
define("CPATH", ROOT.'/controllers/');
define("DEBUG", true);

require ROOT.'/vendor/autoload.php';
$GLOBALS['config']=require ROOT.'/config.php';

$log = new Monolog\Logger('websocket');
$log->pushHandler(new Monolog\Handler\StreamHandler('app.log', Monolog\Logger::INFO));

if(DEBUG)$log->info('before init websocket server.');

$app=new \sheld\websocket_router\Bootstrap();
$app->startServer();