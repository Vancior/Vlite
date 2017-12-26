<?php
/**
 * Created by PhpStorm.
 * User: Vancior
 * Date: 2017/12/26
 * Time: 16:02
 */

use \Vlite\Model;

class CommentController extends BaseController
{
  public function create($issue_id)
  {
    $model_issue = new Model('issue');
    $model_comment = new Model('comment');
    $this->output['status'] = 'success';

    if (is_string($issue_id))
      $issue_id = intval($issue_id);

    session_start();
    if (!isset($_SESSION['user_info'])) {
      $this->output['status'] = 'failed';
      $this->output['message'] = 'not login';
      return;
    }

    $issue_info = $model_issue->where(['id' => $issue_id])->select();
    if (empty($issue_info)) {
      $this->output['status'] = 'failed';
      $this->output['message'] = 'invalid issue id';
      return;
    }
    $issue_info = $issue_info[0];

    $insert = [];
    $insert['content'] = $_POST['content'];
    $insert['sponsor'] = $_SESSION['user_info']->id;
    $insert['comment_time'] = date('Y-m-d H:i:s');
    $insert['issue'] = $issue_id;
    $insert['project'] = intval($issue_info->project);
    $insert['owner'] = intval($issue_info->sponsor);
    $insert['is_read'] = 0;

    if (!$model_comment->insert($insert)) {
      $this->output['status'] = 'failed';
      $this->output['message'] = 'db error';
    }
  }
}