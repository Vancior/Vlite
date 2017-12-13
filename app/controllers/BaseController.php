<?php
/**
 * Created by PhpStorm.
 * User: Vancior
 * Date: 2017/10/21
 * Time: 17:11
 */

use Vlite\View;

class BaseController
{
  public $view;
  public $output = [];

  public function __construct()
  {

  }

  public function __destruct()
  {
//    $view = $this->view;
//
//    if ($view instanceof View) {
//      extract($view->data);
//      require $view->view;
//    }
    header('Content-Type:application/json;charset=utf-8');
    if ($this->output === true)
      header('HTTP/1.1 200 OK');
    elseif ($this->output === false)
      header('HTTP/1.1 400 Bad Request');
    else
      echo json_encode($this->output);
  }
}