<?php
/**
 * Created by PhpStorm.
 * User: Vancior
 * Date: 2017/10/21
 * Time: 0:25
 */

use Vlite\View;

class HomeController extends BaseController
{
  public function home()
  {
    $this->view = View::make('home')->withtitle('Welcome!')
        ->withsubTitle('Issue/Problem Report Project.');
//    var_dump($this->view);
//    exit;
  }
}