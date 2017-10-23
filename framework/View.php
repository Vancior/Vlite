<?php
/**
 * Framework View
 * Created by PhpStorm.
 * User: Vancior
 * Date: 2017/10/21
 * Time: 17:14
 */

namespace Vlite;

class View
{
  const VIEW_BASE_PATH = '/app/views/';

  public $view;
  public $data;

  private function __construct($view)
  {
    $this->view = $view;
  }

  public function __call($method, $params)
  {
    if (preg_match('/^with/', $method) == 1)
      return $this->with((substr($method, 4)), $params[0]);

    throw new \BadMethodCallException("Method [$method] not found");
  }

  public static function make($viewName = null)
  {
    if (empty($viewName))
      throw new \InvalidArgumentException('View Name Empty');

    $filePath = self::getFilePath($viewName);
//    echo $filePath;
//    exit;
    if (is_file($filePath)) {
      return new View($filePath);
    } else {
      throw new \UnexpectedValueException('View file not found');
    }
  }

  public function with($key, $value = null)
  {
    $this->data[$key] = $value;
    return $this;
  }

  private static function getFilePath($viewName)
  {
    $filePath = str_replace('.', '/', $viewName);
    return BASE_PATH . self::VIEW_BASE_PATH . $filePath . '.php';
  }
}