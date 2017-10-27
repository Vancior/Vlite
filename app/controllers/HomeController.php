<?php
/**
 * Created by PhpStorm.
 * User: Vancior
 * Date: 2017/10/21
 * Time: 0:25
 */

use Vlite\View;
use Vlite\Model;

class HomeController extends BaseController
{
  public function home()
  {
    $model = new Model('user');
    $condition = [
        'AND' => [
            'aaaaa[>]' => 12341,
            'bbbbbb[<>]' => ['asdlkjf', 'sdfsdf'],
            'OR' => [
                '2341234' => 23423,
                'sadlkj' => ['lksdjl', 123445]
            ]]];
    $model->where(['value' => 1])->delete();
    $this->view = View::make('home')->withtitle('Welcome')
        ->withsubTitle('Issue/Problem Report Project.')
        ->withwhere($model->where(['value' => 'ssdf'])->delete());
//    var_dump($this->view);
//    exit;
  }
}