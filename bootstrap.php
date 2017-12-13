<?php
/**
 * Created by PhpStorm.
 * User: Vancior
 * Date: 2017/10/21
 * Time: 17:21
 */

define('DEBUG', true);
define('BASE_PATH', str_replace('\\', '/', __DIR__));
define('BASE_CONFIG_PATH', BASE_PATH . '/config');

require_once BASE_PATH . '/vendor/autoload.php';

$whoops = new \Whoops\Run;
if (DEBUG) {
  $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
} else {
  $whoops->pushHandler(function ($e) {
    var_dump($e);
  });
}
$whoops->register();

session_save_path(BASE_PATH . '/session');

$db = new \Vlite\Db();

function html($file)
{
  require_once BASE_PATH . '/html/' . $file . '.html';
}