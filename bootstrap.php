<?php
/**
 * Created by PhpStorm.
 * User: Vancior
 * Date: 2017/10/21
 * Time: 17:21
 */

define('DEBUG', false);
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
  $path = BASE_PATH . '/html/' . $file . '.html';
  if (file_exists($path))
    require_once $path;
  else
    echo 'access invalid';
}

function js($file)
{
  $path = BASE_PATH . '/js/' . $file;
  if (file_exists($path)) {
    header('Content-Type: text/javascript');
    require_once $path;
  }
  else
    echo 'access invalid';
}

function css($file)
{
  $path = BASE_PATH . '/css/' . $file;
  if (file_exists($path)) {
    header('Content-Type: text/css');
    require_once $path;
  }
  else
    echo 'access invalid';
}

function image($file)
{
  $path = BASE_PATH . '/images/' . $file;
  if (file_exists($path)) {
    header('Content-Type: text/css');
    require_once $path;
  }
  else
    echo 'access invalid';
}