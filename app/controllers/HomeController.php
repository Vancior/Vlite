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
    $this->output = ['aaa' => 111, 'bbb' => ['ccc' => [111, 111], 'ddd' => [123, 123]]];
  }
}