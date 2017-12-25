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

    session_start();
    if (!isset($_SESSION['user_info'])) {
      $this->output['status'] = 'failed';
      $this->output['message'] = 'not login';
      return;
    }

    $todos = $model_todo->where(['owner' => $_SESSION['user_info']->id])->order('project asc')->select();

    if (empty($todos)) {
      $this->output['empty'] = true;
    } else {
      $pre_id = 0;
      $project = [];
      foreach ($todos as $item) {
        $cur_id = $item->project;
        if ($pre_id != $cur_id) {
          if (!empty($project))
            $this->output[] = $project;
          $project = ['project_id' => $cur_id];
          $project['todo_list'] = [];
          $pre_id = $cur_id;
        }
        $this->output[] = $project; // the last one
        $item->todo_id = $item->id;
        unset($item->id);
        $item->issue_id = $item->issue;
        unset($item->issue);
        $item->project_id = $item->project;
        unset($item->project);
        $project['todo_list'][] = $item;
      }
    }
  }
}