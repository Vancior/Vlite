<?php
/**
 * Created by PhpStorm.
 * User: Vancior
 * Date: 2017/10/21
 * Time: 0:25
 */

use Vlite\Model;

class HomeController extends BaseController
{
  public function home($num)
  {
    $model = new Model('test');
    $this->output = ['aaa' => 111, 'bbb' => [123, 123]];
  }
}