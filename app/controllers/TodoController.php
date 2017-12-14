<?php
/**
 * Created by PhpStorm.
 * User: Vancior
 * Date: 2017/12/14
 * Time: 20:19
 */

use Vlite\Model;

class TodoController extends BaseController
{
  public function todo()
  {
    $model_todo = new Model('todo');

    if (!isset($_SESSION)) {
      $this->output['status'] = 'failed';
      $this->output['message'] = 'not login';
      return;
    }

    $todos = $model_todo->where(['owner' => $_SESSION['user_info']->id]);

    if (empty($todos)) {
      $this->output['empty'] = true;
    } else {
      foreach ($todos as $item) {
        $project_id = $item->project;
        // TODO: encode data into json
      }
    }
  }
}