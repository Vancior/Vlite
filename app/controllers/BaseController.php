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

  public function __construct()
  {
  }

  public function __destruct()
  {
    $view = $this->view;

    if ($view instanceof View) {
      extract($view->data);
      require $view->view;
    }
  }
}