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
  public function home($num)
  {
//    $model = new Model('test');
//    $condition = [
//        'AND' => [
//            'aaaaa[>]' => 12341,
//            'bbbbbb[<>]' => ['asdlkjf', 'sdfsdf'],
//            'OR' => [
//                '2341234' => 23423,
//                'sadlkj' => ['lksdjl', 123445]
//            ]]];
//    $model->where(['value' => 1])->delete();
//    $this->view = View::make('home')->withtitle('Welcome')
//        ->withsubTitle('Issue/Problem Report Project.')
//        ->withwhere($model->where(['value' => 'ssdf'])->delete());
//    var_dump($model->where(['id' => 1])->select());
//    echo json_encode($model->field('id')->select());
//    echo json_encode($model->select());
//    echo json_encode($model->where(['value' => 44444])->delete());
//    echo json_encode($model->insertAll([['value' => 44444], ['value' => 5555]]));
//    echo json_encode(["1" => ["12"], "2" => ["123"], "4" => ["12314"]]);
//    $result = $model->where(['value' => 44444])->delete();
//    if ($result === true)
//      header('HTTP/1.1 200 OK');
//    elseif ($result === false)
//      header('HTTP/1.1 400 Bad Request');
//    $this->output = $model->select();
    $this->output = [$num];
    $model = new Model('test');
    $this->output = $model->where(['id' => [1, 2, 3]])->select();
  }
}